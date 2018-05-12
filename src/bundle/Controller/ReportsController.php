<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\Mapper\CampaignMapper;
use Edgar\EzCampaign\Data\Mapper\ReportsMapper;
use Edgar\EzCampaign\Data\ReportsData;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\ReportsService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class ReportsController extends Controller
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var ReportsService  */
    private $reportsService;

    /** @var CampaignService  */
    private $campaignService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        ReportsService $reportsService,
        CampaignService $campaignService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->reportsService = $reportsService;
        $this->campaignService = $campaignService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    public function viewAction(Request $request, ?string $campaignId): Response
    {
        $reportsData = null;

        if ($campaignId) {
            try {
                $campaign = $this->campaignService->get($campaignId);
                $campaign = $this->campaignService->map($campaign);
                $reportsData = (new ReportsMapper())->mapToFormData($campaign);

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
        }

        $form = $this->formFactory->reportsChooseCampaign($reportsData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ReportsData $data) {
                return new RedirectResponse($this->generateUrl('edgar.campaign.reports', [
                    'campaignId' => $data->campaign->id,
                ]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/reports.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
