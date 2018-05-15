<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\CampaignCreateData;
use Edgar\EzCampaign\Data\CampaignDeleteData;
use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Data\Mapper\CampaignMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\FoldersService;
use Edgar\EzCampaignBundle\Service\ListService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class CampaignController
 *
 * @package Edgar\EzCampaignBundle\Controller
 */
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

    /** @var CampaignMapper  */
    protected $campaignMapper;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

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
        CampaignMapper $campaignCreateMapper,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        int $defaultPaginationLimit
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->campaignsService = $campaignsService;
        $this->folderService = $folderService;
        $this->foldersService = $foldersService;
        $this->listService = $listService;
        $this->campaignMapper = $campaignCreateMapper;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
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

        $deleteFoldersForm = $this->formFactory->deleteFolders(
            new FoldersDeleteData($this->getFoldersNumbers($folders['folders']))
        );

        return $this->render('@EdgarEzCampaign/campaign/campaigns.html.twig', [
            'pager' => $pagerfanta,
            'campaigns' => $campaigns,
            'form_campaigns_delete' => $deleteCampaignsForm->createView(),
            'folders' => $folders,
            'form_folder_create' => $formFolderCreate->createView(),
            'fomr_folders_delete' => $deleteFoldersForm->createView()
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
            $result = $this->submitHandler->handle($form, function (CampaignCreateData $data) use ($form) {
                try {
                    $campaignCreateStruct = $this->campaignMapper->reverseMap($data);
                    $campaign = $this->campaignService->post($campaignCreateStruct);

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

    public function editAction(Request $request, string $campaignId): Response
    {
        try {
            $campaign = $this->campaignService->get($campaignId);
            $campaign = $this->campaignService->map($campaign);
            $campaignData = (new CampaignMapper())->mapToFormData($campaign);

            if ($campaign === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Campaign does not exists.") */
                        'campaign.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );
            }
        } catch (MailchimpException $e) {
            $this->notificationHandler->error(
                $this->translator->trans(
                /** @Desc("Failed to retrieve Campaign.") */
                    'campaign.update.error',
                    [],
                    'edgarezcampaign'
                )
            );

            return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
        }

        $form = $this->formFactory->updateCampaign($campaignData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignUpdateData $campaign) use ($form, $campaignId) {
                try {
                    $this->campaignService->patch($campaignId, $campaign);

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
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.edit', ['campaignId' => $campaignId]),
            'campaign' => $campaign,
        ]);
    }

    public function viewAction(Request $request, string $campaignId): Response
    {
        try {
            $campaign = $this->campaignService->get($campaignId);
            $campaign = $this->campaignService->map($campaign);
            $campaignData = new CampaignDeleteData(['id' => $campaignId]);

            if ($campaign === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Campaign does not exists.") */
                        'campaign.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );
            }
        } catch (MailchimpException $e) {
            $this->notificationHandler->error(
                $this->translator->trans(
                /** @Desc("Failed to retrieve Campaign.") */
                    'campaign.update.error',
                    [],
                    'edgarezcampaign'
                )
            );

            return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
        }

        $campaignDeleteType = $this->formFactory->deleteCampaign($campaignData);

        return $this->render('@EdgarEzCampaign/campaign/campaign/view.html.twig', [
            'form_delete' => $campaignDeleteType->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.delete', ['campaignId' => $campaignId]),
            'list' => $this->listService->get($campaign->getListId()),
            'folder' => $this->folderService->get($campaign->getFolderId()),
            'campaign' => $campaign,
        ]);
    }

    public function deleteAction(Request $request, string $campaignId): Response
    {
        try {
            $campaign = $this->campaignService->get($campaignId);
            $campaign = $this->campaignService->map($campaign);
            $campaignData = new CampaignDeleteData(['id' => $campaignId]);

            if ($campaign === false) {
                $this->notificationHandler->warning(
                    $this->translator->trans(
                    /** @Desc("Campaign does not exists.") */
                        'campaign.update.warning',
                        [],
                        'edgarezcampaign'
                    )
                );
            }
        } catch (MailchimpException $e) {
            $this->notificationHandler->error(
                $this->translator->trans(
                /** @Desc("Failed to retrieve Campaign.") */
                    'campaign.update.error',
                    [],
                    'edgarezcampaign'
                )
            );

            return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
        }

        $form = $this->formFactory->deleteCampaign($campaignData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignDeleteData $campaignDeleteData) use ($campaign) {
                $this->campaignService->delete($campaign->id);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("Campaign '%name%' updated.") */
                        'campaign.delete.success',
                        ['%name%' => $campaign->title],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaign.view', ['campaignId' => $campaignId]));
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
