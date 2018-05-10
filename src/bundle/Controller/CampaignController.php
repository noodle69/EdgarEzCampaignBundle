<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\CampaignCreateData;
use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Data\Mapper\CampaignMapper;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
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

    /** @var CampaignMapper  */
    protected $campaignMapper;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        CampaignsService $campaignsService,
        CampaignService $campaignService,
        CampaignMapper $campaignCreateMapper,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->campaignsService = $campaignsService;
        $this->campaignMapper = $campaignCreateMapper;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    public function campaignsAction()
    {
        $campaigns = $this->campaignsService->get();

        $deleteCampaignsForm = $this->formFactory->deleteCampaigns(
            new CampaignsDeleteData($this->getCampaignsNumbers($campaigns['campaigns']))
        );

        return $this->render('@EdgarEzCampaign/campaign/campaigns.html.twig', [
            'campaigns' => $campaigns,
            'form_campaigns_delete' => $deleteCampaignsForm->createView(),
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
            $result = $this->submitHandler->handle($form, function (CampaignCreateData $data) {
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
        }

        $form = $this->formFactory->updateCampaign(
            new CampaignUpdateData($campaign)
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (CampaignUpdateData $data) use ($campaign) {
                $this->campaignService->patch($campaign);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /** @Desc("Campaign '%name%' updated.") */
                        'campaign.update.success',
                        ['%name%' => $campaign['title']],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
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
}
