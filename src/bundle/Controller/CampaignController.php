<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Data\Mapper\CampaignMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaign\Values\Core\CampaignContent;
use Edgar\EzCampaign\Values\Core\Schedule;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\FoldersService;
use Edgar\EzCampaignBundle\Service\ListService;
use Edgar\EzCampaignBundle\Service\ListsService;
use eZ\Publish\Core\MVC\Symfony\Routing\UrlAliasRouter;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class CampaignController extends Controller
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var CampaignService  */
    protected $campaignService;

    /** @var CampaignsService  */
    protected $campaignsService;

    /** @var FolderService  */
    protected $folderService;

    /** @var FoldersService  */
    protected $foldersService;

    /** @var ListService  */
    protected $listService;

    /** @var ListsService  */
    protected $listsService;

    /** @var CampaignMapper  */
    protected $campaignMapper;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    /** @var UrlAliasRouter  */
    private $urlAliasRouter;

    /** @var int */
    private $defaultPaginationLimit;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        CampaignsService $campaignsService,
        CampaignService $campaignService,
        FoldersService $foldersService,
        FolderService $folderService,
        ListService $listService,
        ListsService $listsService,
        CampaignMapper $campaignCreateMapper,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        UrlAliasRouter $urlAliasRouter,
        int $defaultPaginationLimit
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->campaignsService = $campaignsService;
        $this->folderService = $folderService;
        $this->foldersService = $foldersService;
        $this->listService = $listService;
        $this->listsService = $listsService;
        $this->campaignMapper = $campaignCreateMapper;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
        $this->urlAliasRouter = $urlAliasRouter;
        $this->defaultPaginationLimit = $defaultPaginationLimit;
    }

    public function campaignsAction(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;
        $allCampaigns = $this->campaignsService->get(0, 0);

        $pagerfanta = new Pagerfanta(
            new ArrayAdapter($allCampaigns['campaigns'])
        );

        $pagerfanta->setMaxPerPage($this->defaultPaginationLimit);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        $campaigns = $this->campaignsService->get($this->defaultPaginationLimit * ($page - 1), $this->defaultPaginationLimit);

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
        ]);
    }

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
                        if ($campaign !== false) {
                            $this->campaignService->delete($campaignId);

                            $this->notificationHandler->success(
                                $this->translator->trans(
                                /** @Desc("Campaign '%name%' removed.") */
                                    'campaigns.delete.success',
                                    ['%name%' => $campaign['settings']['title']],
                                    'edgarezcampaign'
                                )
                            );
                        } else {
                            $this->notificationHandler->warning(
                                $this->translator->trans(
                                /** @Desc("Campaign '%id%' doesn't exists.") */
                                    'campaigns.delete.warning',
                                    ['%id%' => $campaignId],
                                    'edgarezcampaign'
                                )
                            );
                        }
                    } catch (MailchimpException $e) {
                        $this->notificationHandler->error(
                            $this->translator->trans(
                            /** @Desc("Error when deleting campaign.") */
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

    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->createCampaign();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (Campaign $data) use ($form) {
                try {
                    $campaign = $this->campaignService->post($data);

                    if ($data->getSite() && $data->getContent()) {
                        $url = $this->urlAliasRouter->generate(
                            $data->getContent(),
                            ['siteaccess' => $data->getSite()->getIdentifier()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );

                        $this->campaignService->putContent($campaign['id'], $url);
                    }

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /** @Desc("Campaign '%name%' created.") */
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

    public function editAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->updateCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campaignId = $campaign->getId();
            $result = $this->submitHandler->handle($form, function (CampaignUpdateData $campaign) use ($form, $campaignId) {
                try {
                    $this->campaignService->patch($campaignId, $campaign);

                    if ($campaign->site && $campaign->content) {
                        $url = $this->urlAliasRouter->generate(
                            $campaign->content,
                            ['siteaccess' => $campaign->site->getIdentifier()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );

                        $this->campaignService->putContent($campaignId, $url);
                    }

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /** @Desc("Campaign '%name%' updated.") */
                            'campaign.update.success',
                            ['%name%' => $campaign->title],
                            'edgarezcampaign'
                        )
                    );

                    return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
                } catch (MailchimpException $e) {
                    $this->notifyError($e);

                    return $this->render('@EdgarEzCampaign/campaign/campaign/edit.html.twig', [
                        'form' => $form->createView(),
                        'actionUrl' => $this->generateUrl('edgar.campaign.campaign.edit', ['campaignId' => $campaignId]),
                        'campaign' => $campaign,
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

    public function deleteAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->deleteCampaign($campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function () use ($campaign) {
                $this->campaignService->delete($campaign->getId());

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("Campaign '%name%' updated.") */
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
                        /** @Desc("Campaign '%name%' sended.") */
                            'campaign.send.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /** @Desc("Failed to send Campaign '%name%'.") */
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
                        /** @Desc("Campaign '%name%' sended.") */
                            'campaign.send.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /** @Desc("Failed to send Campaign '%name%'.") */
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
                        /** @Desc("Campaign '%name%' schedule canceled.") */
                            'campaign.schedule.cancel.success',
                            ['%name%' => $campaign->getTitle()],
                            'edgarezcampaign'
                        )
                    );
                } catch (MailchimpException $e) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                        /** @Desc("Failed to cancel Campaign '%name%' schedule.") */
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

    public function createContentAction(Request $request, Campaign $campaign): Response
    {
        $form = $this->formFactory->createContent();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignContent $data) use ($campaign) {
                try {
                    $url = $this->urlAliasRouter->generate(
                        $data->getContent(),
                        ['siteaccess' => $data->getSite()->getIdentifier()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    $this->campaignService->putContent($campaign->getId(), $url);

                    $this->notificationHandler->success(
                        $this->translator->trans(
                        /** @Desc("Content has been associated to Campaign '%name%'.") */
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

    public function contentAction(Request $request, Campaign $campaign): Response
    {
        $campaignContent = $this->campaignService->getContent($campaign->getId());

        $response = new Response();
        $response->setContent($campaignContent['html']);

        return $response;
    }

    private function getCampaignsNumbers(array $campaigns): array
    {
        $campaignsNumbers = [];
        foreach ($campaigns as $campaign) {
            if ($campaign['id'] !== false) {
                $campaignsNumbers[] = $campaign['id'];
            }
        }

        return array_combine($campaignsNumbers, array_fill_keys($campaignsNumbers, false));
    }

    private function getFoldersNumbers(array $folders): array
    {
        $foldersNumbers = [];
        foreach ($folders as $folder) {
            if ($folder['id'] !== false) {
                $foldersNumbers[] = $folder['id'];
            }
        }

        return array_combine($foldersNumbers, array_fill_keys($foldersNumbers, false));
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
