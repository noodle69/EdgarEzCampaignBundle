<?php

namespace Edgar\EzCampaign\Menu\Admin\Campaign;

use Edgar\EzCampaign\Menu\Event\ConfigureMenuEvent;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaignBundle\Service\CampaignService;
use eZ\Publish\API\Repository\Exceptions as ApiExceptions;
use EzSystems\EzPlatformAdminUi\Menu\AbstractBuilder;
use EzSystems\EzPlatformAdminUi\Menu\MenuItemFactory;
use InvalidArgumentException;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class CampaignViewRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    const STATUS_SAVE = 'save';
    const STATUS_SENT = 'sent';
    const STATUS_PAUSED = 'paused';
    const STATUS_SCHEDULE = 'schedule';

    /* Menu items */
    const ITEM__EDIT = 'campaign_view__sidebar_right__edit';
    const ITEM__SEND = 'campaign_view__sidebar_right__send';
    const ITEM__SCHEDULE = 'campaign_view__sidebar_right__schedule';
    const ITEM__CANCEL_SCHEDULE = 'campaign_view__sidebar_right__cancel_schedule';
    const ITEM__REPORTS = 'campaign_view__sidebar_right__reports';
    const ITEM__REMOVE = 'campaign_view__sidebar_right__remove';

    /** @var CampaignService  */
    protected $campaignService;

    protected $status;

    public function __construct(
        MenuItemFactory $factory,
        EventDispatcherInterface $eventDispatcher,
        CampaignService $campaignService
    ) {
        parent::__construct($factory, $eventDispatcher);
        $this->campaignService = $campaignService;
        $this->status = [
            self::STATUS_SAVE,
            self::STATUS_SENT,
            self::STATUS_PAUSED,
            self::STATUS_SCHEDULE,
        ];
    }

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return ConfigureMenuEvent::CAMPAIGN_VIEW_SIDEBAR_RIGHT;
    }

    /**
     * @param array $options
     *
     * @return ItemInterface
     *
     * @throws ApiExceptions\InvalidArgumentException
     * @throws ApiExceptions\BadStateException
     * @throws InvalidArgumentException
     */
    public function createStructure(array $options): ItemInterface
    {
        /** @var Campaign $campaign */
        $campaignId = $options['campaign_id'];

        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $this->factory->createItem('root');

        if (!isset($options['campaign_id'])) {
            return $menu;
        }

        try {
            $campaign = $this->campaignService->get($options['campaign_id']);
        } catch (MailchimpException $e) {
            return $menu;
        }

        $menu->addChild(
            $this->createMenuItem(
            self::ITEM__EDIT,
                [
                    'route' => 'edgar.campaign.campaign.edit',
                    'routeParameters' => [
                        'campaignId' => $campaignId,
                    ],
                    'extras' => ['icon' => 'edit'],
                ]
            )
        );

        if (in_array($campaign['status'], $this->status) && $campaign['content_type'] == 'url'
            && $campaign['recipients']['list_is_active'] && $campaign['recipients']['recipient_count'] > 0
        ) {
            if ($campaign['status'] != self::STATUS_SCHEDULE) {
                $menu->addChild(
                    $this->createMenuItem(
                        self::ITEM__SEND,
                        [
                            'attributes' => [
                                'data-toggle' => 'modal',
                                'data-target' => '#campaign-send-modal',
                            ],
                            'extras' => ['icon' => 'mail'],
                        ]
                    )
                );

                $menu->addChild(
                    $this->createMenuItem(
                        self::ITEM__SCHEDULE,
                        [
                            'attributes' => [
                                'data-toggle' => 'modal',
                                'data-target' => '#campaign-schedule-modal',
                            ],
                            'extras' => ['icon' => 'schedule'],
                        ]
                    )
                );
            } else {
                $menu->addChild(
                    $this->createMenuItem(
                        self::ITEM__CANCEL_SCHEDULE,
                        [
                            'attributes' => [
                                'data-toggle' => 'modal',
                                'data-target' => '#campaign-cancel-schedule-modal',
                            ],
                            'extras' => ['icon' => 'circle-close'],
                        ]
                    )
                );
            }

            $menu->addChild(
                $this->createMenuItem(
                    self::ITEM__REPORTS,
                    [
                        'route' => 'edgar.campaign.reports',
                        'routeParameters' => [
                            'campaignId' => $campaignId,
                        ],
                        'extras' => ['icon' => 'stats'],
                    ]
                )
            );
        }

        $menu->addChild(
            $this->createMenuItem(
                self::ITEM__REMOVE,
                [
                    'attributes' => [
                        'data-toggle' => 'modal',
                        'data-target' => '#trash-campaign-campaign-modal',
                    ],
                    'extras' => ['icon' => 'trash'],
                ]
            )
        );

        return $menu;
    }

    /**
     * @return Message[]
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM__EDIT, 'menu'))->setDesc('Edit'),
            (new Message(self::ITEM__SEND, 'menu'))->setDesc('Send'),
            (new Message(self::ITEM__SCHEDULE, 'menu'))->setDesc('Schedule'),
            (new Message(self::ITEM__CANCEL_SCHEDULE, 'menu'))->setDesc('Cancel schedule'),
            (new Message(self::ITEM__REPORTS, 'menu'))->setDesc('Reports'),
            (new Message(self::ITEM__REMOVE, 'menu'))->setDesc('Remove'),
        ];
    }
}
