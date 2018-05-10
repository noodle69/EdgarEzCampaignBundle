<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\CampaignService;
use Edgar\EzCampaignBundle\Service\CampaignsService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
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

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        CampaignsService $campaignsService,
        CampaignService $campaignService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->campaignsService = $campaignsService;
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

    public function editAction(?int $campaignID)
    {

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
