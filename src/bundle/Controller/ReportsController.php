<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\Mapper\ReportsMapper;
use Edgar\EzCampaign\Data\ReportsData;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\ReportsService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class ReportsController extends BaseController
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var ReportsService */
    private $reportsService;

    /** @var CampaignService */
    private $campaignService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    /** @var PermissionResolver */
    protected $permissionResolver;

    /**
     * ReportsController constructor.
     *
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param ReportsService $reportsService
     * @param CampaignService $campaignService
     * @param SubmitHandler $submitHandler
     * @param FormFactory $formFactory
     * @param PermissionResolver $permissionResolver
     */
    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        ReportsService $reportsService,
        CampaignService $campaignService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory,
        PermissionResolver $permissionResolver
    ) {
        parent::__construct($permissionResolver);
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->reportsService = $reportsService;
        $this->campaignService = $campaignService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @param null|Campaign $campaign
     *
     * @return Response
     */
    public function viewAction(Request $request, ?Campaign $campaign): Response
    {
        $reportsData = null;

        if (!$campaign->getId()) {
            $reportsData = (new ReportsMapper())->mapToFormData($campaign);
        }

        $form = $this->formFactory->reportsChooseCampaign($reportsData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (ReportsData $data) {
                return new RedirectResponse($this->generateUrl('edgar.campaign.reports', [
                    'campaignId' => $data->campaign ? $data->campaign->id : null,
                ]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/reports/reports.html.twig', [
            'form' => $form->createView(),
            'campaign' => $campaign->getId() ? $campaign : null,
        ]);
    }
}
