<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\ListCreateData;
use Edgar\EzCampaign\Data\ListDeleteData;
use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Data\ListUpdateData;
use Edgar\EzCampaign\Data\Mapper\ListMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\ListService;
use Edgar\EzCampaignBundle\Service\ListsService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
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

    /** @var int */
    private $defaultPaginationLimit;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        ListService $listService,
        ListsService $listsService,
        ListMapper $listMapper,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        int $defaultPaginationLimit
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->listService = $listService;
        $this->listsService = $listsService;
        $this->listMapper = $listMapper;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
        $this->defaultPaginationLimit = $defaultPaginationLimit;
    }

    public function listsAction(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;
        $allLists = $this->listsService->get(0, 0);

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allLists['lists'])
        );

        $pagerfanta->setMaxPerPage($this->defaultPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $lists = $this->listsService->get($this->defaultPaginationLimit * ($page - 1), $this->defaultPaginationLimit);

        $deleteListsForm = $this->formFactory->deleteLists(
            new ListsDeleteData($this->getListsNumbers($lists['lists']))
        );

        return $this->render('@EdgarEzCampaign/campaign/lists.html.twig', [
            'pager' => $pagerfanta,
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
                            if ($this->listsService->countCampaigns($listId) > 0) {
                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /** @Desc("List '%name%' is associated with campaigns, it can't be removedd.") */
                                        'lists.delete.campaigns_exists',
                                        ['%name%' => $list['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            } else {
                                $this->listService->delete($listId);

                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /** @Desc("List '%name%' removed.") */
                                        'lists.delete.success',
                                        ['%name%' => $list['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            }
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
            $result = $this->submitHandler->handle($form, function (ListCreateData $data) use ($form) {
                try {
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
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return $this->render('@EdgarEzCampaign/campaign/list/create.html.twig', [
                        'form' => $form->createView(),
                        'actionUrl' => $this->generateUrl('edgar.campaign.list.create'),
                    ]);
                }
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
            $result = $this->submitHandler->handle($form, function (ListUpdateData $list) use ($form, $listId) {
                try {
                    $this->listService->patch($listId, $list);

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /** @Desc("Subscription list '%name%' updated.") */
                            'list.update.success',
                            ['%name%' => $list->name],
                            'edgarezcampaign'
                        )
                    );

                    return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return $this->render('@EdgarEzCampaign/campaign/list/edit.html.twig', [
                        'form' => $form->createView(),
                        'actionUrl' => $this->generateUrl('edgar.campaign.list.edit', ['listId' => $listId]),
                        'list' => $list,
                    ]);
                }
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

    public function viewAction(Request $request, string $listId): Response
    {
        try {
            $list = $this->listService->get($listId);
            $list = $this->listService->map($list);
            $listData = new ListDeleteData(['id' => $list->getId()]);

            if ($listData === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Subscription list does not exists.") */
                        'list.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
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

            return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
        }

        $listDeleteType = $this->formFactory->deleteList($listData);

        return $this->render('@EdgarEzCampaign/campaign/list/view.html.twig', [
            'form_delete' => $listDeleteType->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.list.delete', ['listId' => $listId]),
            'list' => $list,
        ]);
    }

    public function deleteAction(Request $request, string $listId): Response
    {
        try {
            $list = $this->listService->get($listId);
            $list = $this->listService->map($list);
            $listData = new ListDeleteData(['id' => $list->getId()]);

            if ($listData === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Subscription list does not exists.") */
                        'list.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
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

            return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
        }

        $form = $this->formFactory->deleteList($listData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ListDeleteData $listDeleteData) use ($list) {
                $this->listService->delete($list->id);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("Subscription list '%name%' updated.") */
                        'list.delete.success',
                        ['%name%' => $list->getName()],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.lists', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.list.view', ['listId' => $listId]));
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

    private function notifyError(MailchimpException $e) {
        $errors = [];
        $errorsArray = $e->getErrors();
        foreach ($errorsArray as $error) {
            $errors[] = 'field: ' . $error['field'] . ', ' . $error['message'];
        }
        $this->notificationHandler->error(
            $this->translator->trans(
            /** @Desc("Field errors: %errors%.") */
                'edgar.campaign.error',
                ['%errors%' => implode( '|', $errors)],
                'edgarezcampaign'
            )
        );
    }
}
