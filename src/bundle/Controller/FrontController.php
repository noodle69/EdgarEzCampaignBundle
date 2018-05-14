<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaignBundle\Service\CampaignService;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use Edgar\EzCampaign\Data\SubscribeData;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class FrontController extends Controller
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var CampaignService  */
    protected $campaignService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        CampaignService $campaignService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->campaignService = $campaignService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    public function subscribeAction(Request $request, string $campaignId): Response
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

            return new RedirectResponse($request->server->get('SCRIPT_URI'));
        }

        $form = $this->formFactory->subscribe();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (SubscribeData $subscribe) use ($campaignId, $request) {
                $this->campaignService->subscribe($campaignId, $subscribe->email);

                return new RedirectResponse($request->server->get('SCRIPT_URI'));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('@EdgarEzCampaign/campaign/campaign/subscribe.html.twig', [
            'form' => $form->createView(),
            'actionUrl' => $this->generateUrl('edgar.campaign.campaign.subscribe', ['campaignId' => $campaignId]),
            'campaign' => $campaign,
        ]);
    }
}