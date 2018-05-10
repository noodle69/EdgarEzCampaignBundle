<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\ListCreateData;
use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Data\ListUpdateData;
use Edgar\EzCampaign\Data\Mapper\ListMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\ListService;
use Edgar\EzCampaignBundle\Service\ListsService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class ListController extends Controller
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var ListService */
    protected $listService;

    /** @var ListsService  */
    protected $listsService;

    /** @var ListMapper  */
    protected $listMapper;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        ListService $listService,
        ListsService $listsService,
        ListMapper $listMapper,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->listService = $listService;
        $this->listsService = $listsService;
        $this->listMapper = $listMapper;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    public function listsAction()
    {
        $lists = $this->listsService->get();

        $deleteListsForm = $this->formFactory->deleteLists(
            new ListsDeleteData($this->getListsNumbers($lists['lists']))
        );

        return $this->render('@EdgarEzCampaign/campaign/lists.html.twig', [
            'lists' => $lists,
            'form_lists_delete' => $deleteListsForm->createView(),
        ]);
    }

    public function bulkDeleteAction(Request $request): Response
    {
        $form = $this->formFactory->deleteLists(
            new ListsDeleteData()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ListsDeleteData $data) {
                foreach ($data->getLists() as $listId => $selected) {
                    try {
                        $list = $this->listService->get($listId);
                        if ($list !== false) {
                            $this->listService->delete($listId);

                            $this->notificationHandler->success(
                                $this->translator->trans(
                                /** @Desc("List '%name%' removed.") */
                                    'lists.delete.success',
                                    ['%name%' => $list['name']],
                                    'edgarezcampaign'
                                )
                            );
                        } else {
                            $this->notificationHandler->warning(
                                $this->translator->trans(
                                /** @Desc("List '%id%' doesn't exists.") */
                                    'lists.delete.warning',
                                    ['%id%' => $listId],
                                    'edgarezcampaign'
                                )
                            );
                        }
                    } catch (MailchimpException $e) {
                        $this->notificationHandler->error(
                            $this->translator->trans(
                            /** @Desc("Error when deleting list.") */
                                'lists.delete.error',
                                [],
                                'edgarezcampaign'
                            )
                        );
                    }
                }
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->redirect($this->generateUrl('edgar.campaign.lists'));
    }

    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->createList();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ListCreateData $data) {
                $listCreateStruct = $this->listMapper->reverseMap($data);
                $list = $this->listService->post($listCreateStruct);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("List '%name%' created.") */
                        'list.create.success',
                        ['%name%' => $list['name']],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/list/create.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.list.create'),
        ]);
    }

    public function editAction(Request $request, string $listId): Response
    {
        try {
            $list = $this->listService->get($listId);
            $list = $this->listService->map($list);
            $listData = (new ListMapper())->mapToFormData($list);

            if ($list === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Subscription list does not exists.") */
                        'list.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );
            }
        } catch (MailchimpException $e) {
            $this->notificationHandler->error(
                $this->translator->trans(
                /** @Desc("Failed to retrieve Subscription list.") */
                    'list.update.error',
                    [],
                    'edgarezcampaign'
                )
            );
        }

        $form = $this->formFactory->updateList($listData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ListUpdateData $data) use ($list) {
                $this->listService->patch($list);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("Subscription list '%name%' updated.") */
                        'list.update.success',
                        ['%name%' => $list['name']],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/list/edit.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.list.edit', ['listId' => $listId]),
            'list' => $list,
        ]);
    }

    private function getListsNumbers(array $lists): array
    {
        $listsNumbers = [];
        foreach ($lists as $list) {
            if ($list['id'] !== false) {
                $listsNumbers[] = $list['id'];
            }
        }

        return array_combine($listsNumbers, array_fill_keys($listsNumbers, false));
    }
}
