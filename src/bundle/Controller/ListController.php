<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaign\Values\Core\CampaignList;
use Edgar\EzCampaignBundle\Service\CampaignsService;
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

    /** @var ListsService */
    protected $listsService;

    /** @var CampaignsService  */
    protected $campaignsService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    /** @var int */
    private $defaultListPaginationLimit;

    /** @var int */
    private $defaultCampaignPaginationLimit;

    /**
     * ListController constructor.
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param ListService $listService
     * @param ListsService $listsService
     * @param CampaignsService $campaignsService
     * @param SubmitHandler $submitHandler
     * @param FormFactory $formFactory
     * @param int $defaultListPaginationLimit
     * * @param int $defaultCampaignPaginationLimit
     */
    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        ListService $listService,
        ListsService $listsService,
        CampaignsService $campaignsService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        int $defaultListPaginationLimit,
        int $defaultCampaignPaginationLimit
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->listService = $listService;
        $this->listsService = $listsService;
        $this->campaignsService = $campaignsService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
        $this->defaultListPaginationLimit = $defaultListPaginationLimit;
        $this->defaultCampaignPaginationLimit = $defaultCampaignPaginationLimit;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listsAction(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;
        $allLists = $this->listsService->get(0, 0);

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allLists['lists'])
        );

        $pagerfanta->setMaxPerPage($this->defaultListPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $lists = $this->listsService->get(
            $this->defaultListPaginationLimit * ($page - 1),
            $this->defaultListPaginationLimit
        );

        $deleteListsForm = $this->formFactory->deleteLists(
            new ListsDeleteData($this->getListsNumbers($lists['lists']))
        );

        return $this->render('@EdgarEzCampaign/campaign/lists.html.twig', [
            'pager' => $pagerfanta,
            'lists' => $lists,
            'form_lists_delete' => $deleteListsForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
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
                        if (false !== $list) {
                            if ($this->listsService->countCampaigns($listId) > 0) {
                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /* @Desc("List '%name%' is associated with campaigns, it can't be removedd.") */
                                        'lists.delete.campaigns_exists',
                                        ['%name%' => $list['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            } else {
                                $this->listService->delete($listId);

                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /* @Desc("List '%name%' removed.") */
                                        'lists.delete.success',
                                        ['%name%' => $list['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            }
                        } else {
                            $this->notificationHandler->warning(
                                $this->translator->trans(
                                /* @Desc("List '%id%' doesn't exists.") */
                                    'lists.delete.warning',
                                    ['%id%' => $listId],
                                    'edgarezcampaign'
                                )
                            );
                        }
                    } catch (MailchimpException $e) {
                        $this->notificationHandler->error(
                            $this->translator->trans(
                            /* @Desc("Error when deleting list.") */
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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->createList();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignList $data) use ($form) {
                try {
                    $this->listService->post($data);

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("List '%name%' created.") */
                            'list.create.success',
                            ['%name%' => $data->getName()],
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

    /**
     * @param Request $request
     * @param CampaignList $list
     *
     * @return Response
     */
    public function editAction(Request $request, CampaignList $list): Response
    {
        $form = $this->formFactory->updateList($list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listId = $list->getId();
            $result = $this->submitHandler->handle($form, function (CampaignList $list) use ($form, $listId) {
                try {
                    $this->listService->patch($listId, $list);

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Subscription list '%name%' updated.") */
                            'list.update.success',
                            ['%name%' => $list->getName()],
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
            'actionUrl' => $this->generateUrl('edgar.campaign.list.edit', ['listId' => $list->getId()]),
            'list' => $list,
        ]);
    }

    /**
     * @param Request $request
     * @param CampaignList $list
     *
     * @return Response
     */
    public function viewAction(Request $request, CampaignList $list): Response
    {
        $listDeleteType = $this->formFactory->deleteList($list);

        $page = $request->query->get('page') ?? 1;
        $allCampaigns = $this->campaignsService->get(0, 0, null, $list->getId());

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allCampaigns['campaigns'])
        );

        $pagerfanta->setMaxPerPage($this->defaultCampaignPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $campaigns = $this->campaignsService->get(
            $this->defaultCampaignPaginationLimit * ($page - 1),
            $this->defaultCampaignPaginationLimit,
            null,
            $list->getId()
        );

        return $this->render('@EdgarEzCampaign/campaign/list/view.html.twig', [
            'form_delete' => $listDeleteType->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.list.delete', ['listId' => $list->getId()]),
            'list' => $list,
            'pager' => $pagerfanta,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * @param Request $request
     * @param CampaignList $list
     *
     * @return Response
     */
    public function deleteAction(Request $request, CampaignList $list): Response
    {
        $form = $this->formFactory->deleteList($list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function () use ($list) {
                $this->listService->delete($list->getId());

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /* @Desc("Subscription list '%name%' updated.") */
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

        return new RedirectResponse($this->generateUrl('edgar.campaign.list.view', ['listId' => $list->getId()]));
    }

    /**
     * @param array $lists
     *
     * @return array
     */
    private function getListsNumbers(array $lists): array
    {
        $listsNumbers = [];
        foreach ($lists as $list) {
            if (false !== $list['id']) {
                $listsNumbers[] = $list['id'];
            }
        }

        return array_combine($listsNumbers, array_fill_keys($listsNumbers, false));
    }

    /**
     * @param MailchimpException $e
     */
    private function notifyError(MailchimpException $e)
    {
        $errors = [];

        $errorsArray = $e->getErrors();

        foreach ($errorsArray as $error) {
            $errors[] = 'field: ' . $error['field'] . ', ' . $error['message'];
        }

        $this->notificationHandler->error(
            $this->translator->trans(
            /* @Desc("Field errors: %errors%.") */
                'edgar.campaign.error',
                ['%errors%' => implode('|', $errors)],
                'edgarezcampaign'
            )
        );
    }
}
