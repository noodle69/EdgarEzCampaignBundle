<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\FilterFolderData;
use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Data\Mapper\FilterFolderMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaign\Values\Core\CampaignContent;
use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzCampaign\Values\Core\Schedule;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\FoldersService;
use Edgar\EzCampaignBundle\Service\ListService;
use Edgar\EzCampaignBundle\Service\ListsService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class CampaignController extends BaseController
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var CampaignService */
    protected $campaignService;

    /** @var CampaignsService */
    protected $campaignsService;

    /** @var FolderService */
    protected $folderService;

    /** @var FoldersService */
    protected $foldersService;

    /** @var ListService */
    protected $listService;

    /** @var ListsService */
    protected $listsService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var int */
    private $defaultPaginationLimit;

    /** @var PermissionResolver */
    protected $permissionResolver;

    /**
     * CampaignController constructor.
     *
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param CampaignsService $campaignsService
     * @param CampaignService $campaignService
     * @param FoldersService $foldersService
     * @param FolderService $folderService
     * @param ListService $listService
     * @param ListsService $listsService
     * @param SubmitHandler $submitHandler
     * @param FormFactory $formFactory
     * @param RouterInterface $router
     * @param PermissionResolver $permissionResolver
     * @param int $defaultPaginationLimit
     */
    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        CampaignsService $campaignsService,
        CampaignService $campaignService,
        FoldersService $foldersService,
        FolderService $folderService,
        ListService $listService,
        ListsService $listsService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        RouterInterface $router,
        PermissionResolver $permissionResolver,
        int $defaultPaginationLimit
    ) {
        parent::__construct($permissionResolver);
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->campaignsService = $campaignsService;
        $this->folderService = $folderService;
        $this->foldersService = $foldersService;
        $this->listService = $listService;
        $this->listsService = $listsService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->defaultPaginationLimit = $defaultPaginationLimit;
    }

    /**
     * @param Request $request
     * @param Folder|null $folder
     *
     * @return Response
     */
    public function campaignsAction(Request $request, ?Folder $folder): Response
    {
        $page = $request->query->get('page') ?? 1;
        $allCampaigns = $this->campaignsService->get(0, 0, $folder ? $folder->getId() : null);

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allCampaigns['campaigns'])
        );

        $pagerfanta->setMaxPerPage($this->defaultPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $campaigns = $this->campaignsService->get(
            $this->defaultPaginationLimit * ($page - 1),
            $this->defaultPaginationLimit,
            $folder ? $folder->getId() : null
        );

        $deleteCampaignsForm = $this->formFactory->deleteCampaigns(
            new CampaignsDeleteData($this->getCampaignsNumbers($campaigns['campaigns']))
        );

        $formFolderCreate = $this->formFactory->createFolder();
        $formFolderCreate->handleRequest($request);

        $folders = $this->foldersService->get(0, 0);
        $lists = $this->listsService->get(0, 1);

        $deleteFoldersForm = $this->formFactory->deleteFolders(
            new FoldersDeleteData($this->getFoldersNumbers($folders['folders']))
        );

        $sendForm = $this->formFactory->sendCampaign();
        $scheduleForm = $this->formFactory->scheduleCampaign();
        $cancelScheduleForm = $this->formFactory->cancelScheduleCampaign();

        $filterFolderData = (new FilterFolderMapper())->mapToFormData($folder);

        $folderFilterForm = $this->formFactory->filterFolder($filterFolderData);
        $folderFilterForm->handleRequest($request);

        if ($folderFilterForm->isSubmitted() && $folderFilterForm->isValid()) {
            $result = $this->submitHandler->handle($folderFilterForm, function (FilterFolderData $data) {
                return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', [
                    'folderId' => $data->getFolder()->id,
                ]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/campaigns.html.twig', [
            'pager' => $pagerfanta,
            'campaigns' => $campaigns,
            'form_campaigns_delete' => $deleteCampaignsForm->createView(),
            'folders' => $folders,
            'lists' => $lists,
            'form_folder_create' => $formFolderCreate->createView(),
            'fomr_folders_delete' => $deleteFoldersForm->createView(),
            'form_send' => $sendForm->createView(),
            'form_schedule' => $scheduleForm->createView(),
            'form_cancel_schedule' => $cancelScheduleForm->createView(),
            'form_folder_filter' => $folderFilterForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request): Response
    {
        $form = $this->formFactory->deleteCampaigns(
            new CampaignsDeleteData()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignsDeleteData $data) {
                foreach ($data->getCampaigns() as $campaignId => $selected) {
                    try {
                        $campaign = $this->campaignService->get($campaignId);
                        if (false !== $campaign) {
                            $this->campaignService->delete($campaignId);

                            $this->notificationHandler->success(
                                $this->translator->trans(
                                /* @Desc("Campaign '%name%' removed.") */
                                    'campaigns.delete.success',
                                    ['%name%' => $campaign['settings']['title']],
                                    'edgarezcampaign'
                                )
                            );
                        } else {
                            $this->notificationHandler->warning(
                                $this->translator->trans(
                                /* @Desc("Campaign '%id%' doesn't exists.") */
                                    'campaigns.delete.warning',
                                    ['%id%' => $campaignId],
                                    'edgarezcampaign'
                                )
                            );
                        }
                    } catch (MailchimpException $e) {
                        $this->notificationHandler->error(
                            $this->translator->trans(
                            /* @Desc("Error when deleting campaign.") */
                                'campaigns.delete.error',
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

        return $this->redirect($this->generateUrl('edgar.campaign.campaigns'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->createCampaign();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (Campaign $data) use ($form) {
                try {
                    $campaign = $this->campaignService->post($data);

                    if ($data->getSite() && $data->getContent()) {
                        $url = $this->router->generate(
                            'edgar.campaign.view',
                            [
                                'locationId' => $data->getContent()->id,
                                'site' => $data->getSite()->getIdentifier(),
                                'siteaccess' => $data->getSite()->getIdentifier(),
                            ],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );

                        $this->campaignService->putContent(
                            $campaign['id'],
                            $url,
                            $data->getContent()->id,
                            $data->getSite()->getIdentifier()
                        );
                    }

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Campaign '%name%' created.") */
                            'campaign.create.success',
                            ['%name%' => $campaign['settings']['title']],
                            'edgarezcampaign'
                        )
                    );

                    return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return $this->render('@EdgarEzCampaign/campaign/campaign/create.html.twig', [
                        'form' => $form->createView(),
                        'actionUrl' => $this->generateUrl('edgar.campaign.campaign.create'),
                    ]);
                }
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/campaign/create.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.create'),
        ]);
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function editAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->updateCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campaignId = $campaign->getId();
            $result = $this->submitHandler->handle($form, function (Campaign $data) use ($form, $campaignId) {
                try {
                    $this->campaignService->patch($campaignId, $data);

                    if ($data->getSite() && $data->getContent()) {
                        $url = $this->router->generate(
                            'edgar.campaign.view',
                            [
                                'locationId' => $data->getContent()->id,
                                'site' => $data->getSite()->getIdentifier(),
                                'siteaccess' => $data->getSite()->getIdentifier(),
                            ],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );

                        $this->campaignService->putContent(
                            $campaignId,
                            $url,
                            $data->getContent()->id,
                            $data->getSite()->getIdentifier()
                        );
                    }

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Campaign '%name%' updated.") */
                            'campaign.update.success',
                            ['%name%' => $data->getTitle()],
                            'edgarezcampaign'
                        )
                    );

                    return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return $this->render('@EdgarEzCampaign/campaign/campaign/edit.html.twig', [
                        'form' => $form->createView(),
                        'actionUrl' => $this->generateUrl('edgar.campaign.campaign.edit', ['campaignId' => $campaignId]),
                        'campaign' => $data,
                    ]);
                }
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/campaign/edit.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.edit', ['campaignId' => $campaign->getId()]),
            'campaign' => $campaign,
        ]);
    }

    /**
     * @param Campaign $campaign
     *
     * @return Response
     *
     * @throws MailchimpException
     */
    public function viewAction(Campaign $campaign): Response
    {
        $campaignContent = $this->campaignService->getContent($campaign->getId());
        $campaignCreateContent = $this->formFactory->createContent();

        $campaignDeleteType = $this->formFactory->deleteCampaign($campaign);

        $sendForm = $this->formFactory->sendCampaign();
        $scheduleForm = $this->formFactory->scheduleCampaign();
        $cancelScheduleForm = $this->formFactory->cancelScheduleCampaign();

        return $this->render('@EdgarEzCampaign/campaign/campaign/view.html.twig', [
            'form_delete' => $campaignDeleteType->createView(),
            'form_send' => $sendForm->createView(),
            'form_schedule' => $scheduleForm->createView(),
            'form_cancel_schedule' => $cancelScheduleForm->createView(),
            'form_create_content' => $campaignCreateContent->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.delete', ['campaignId' => $campaign->getId()]),
            'actionUrlSend' => $this->generateUrl('edgar.campaign.send', ['campaignId' => $campaign->getId()]),
            'actionUrlSchedule' => $this->generateUrl('edgar.campaign.schedule', ['campaignId' => $campaign->getId()]),
            'actionUrlCancelSchedule' => $this->generateUrl('edgar.campaign.cancel.schedule', ['campaignId' => $campaign->getId()]),
            'actionUrlCreateContent' => $this->generateUrl('edgar.campaign.create_content', ['campaignId' => $campaign->getId()]),
            'list' => $campaign->getList(),
            'folder' => $campaign->getFolder(),
            'campaign' => $campaign,
            'campaign_content' => $campaignContent,
        ]);
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function deleteAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->deleteCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function () use ($campaign) {
                $this->campaignService->delete($campaign->getId());

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /* @Desc("Campaign '%name%' updated.") */
                        'campaign.delete.success',
                        ['%name%' => $campaign->getTitle()],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function sendAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->sendCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function () use ($campaign) {
                try {
                    $this->campaignService->send($campaign->getId());

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Campaign '%name%' sended.") */
                            'campaign.send.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /* @Desc("Failed to send Campaign '%name%'.") */
                            'campaign.send.error',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                }

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function scheduleAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->scheduleCampaign();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (Schedule $data) use ($campaign) {
                try {
                    $this->campaignService->schedule($campaign->getId(), $data);

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Campaign '%name%' scheduled.") */
                            'campaign.schedule.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /* @Desc("Failed to schedule Campaign '%name%'.") */
                            'campaign.schedule.error',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                }

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function cancelScheduleAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->cancelScheduleCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function () use ($campaign) {
                try {
                    $this->campaignService->cancelSchedule($campaign->getId());

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Campaign '%name%' schedule canceled.") */
                            'campaign.schedule.cancel.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /* @Desc("Failed to cancel Campaign '%name%' schedule.") */
                            'campaign.cancel.schedule.error',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                }

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     */
    public function createContentAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->createContent();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignContent $data) use ($campaign) {
                try {
                    $url = $this->router->generate(
                        'edgar.campaign.view',
                        [
                            'locationId' => $data->getContent()->id,
                            'site' => $data->getSite()->getIdentifier(),
                            'siteaccess' => $data->getSite()->getIdentifier(),
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    $this->campaignService->putContent(
                        $campaign->getId(),
                        $url,
                        $data->getContent()->id,
                        $data->getSite()->getIdentifier()
                    );

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /* @Desc("Content has been associated to Campaign '%name%'.") */
                            'campaign.create.content.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );

                    return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
                }
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaign->getId()]));
    }

    /**
     * @param Request $request
     * @param Campaign $campaign
     *
     * @return Response
     *
     * @throws MailchimpException
     */
    public function contentAction(Request $request, Campaign $campaign): Response
    {
        $campaignContent = $this->campaignService->getContent($campaign->getId());

        $response = new Response();
        $response->setContent($campaignContent['html']);

        return $response;
    }

    /**
     * @param array $campaigns
     *
     * @return array
     */
    private function getCampaignsNumbers(array $campaigns): array
    {
        $campaignsNumbers = [];
        foreach ($campaigns as $campaign) {
            if (false !== $campaign['id']) {
                $campaignsNumbers[] = $campaign['id'];
            }
        }

        return array_combine($campaignsNumbers, array_fill_keys($campaignsNumbers, false));
    }

    /**
     * @param array $folders
     *
     * @return array
     */
    private function getFoldersNumbers(array $folders): array
    {
        $foldersNumbers = [];
        foreach ($folders as $folder) {
            if (false !== $folder['id']) {
                $foldersNumbers[] = $folder['id'];
            }
        }

        return array_combine($foldersNumbers, array_fill_keys($foldersNumbers, false));
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
