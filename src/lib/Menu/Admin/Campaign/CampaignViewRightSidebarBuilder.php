<?php

namespace Edgar\EzCampaign\Menu\Admin\Campaign;

use Edgar\EzCampaign\Menu\Event\ConfigureMenuEvent;
use Edgar\EzCampaign\Values\Core\Campaign;
use eZ\Publish\API\Repository\Exceptions as ApiExceptions;
use EzSystems\EzPlatformAdminUi\Menu\AbstractBuilder;
use InvalidArgumentException;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;

class CampaignViewRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /* Menu items */
    const ITEM__EDIT = 'campaign_view__sidebar_right__edit';
    const ITEM__REPORTS = 'campaign_view__sidebar_right__reports';
    const ITEM__REMOVE = 'campaign_view__sidebar_right__remove';

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

        $menu->setChildren([
            self::ITEM__EDIT => $this->createMenuItem(
                self::ITEM__EDIT,
                [
                    'route' => 'edgar.campaign.campaign.edit',
                    'routeParameters' => [
                        'campaignId' => $campaignId,
                    ],
                    'extras' => ['icon' => 'edit'],
                ]
            ),
            self::ITEM__REPORTS => $this->createMenuItem(
                self::ITEM__REPORTS,
                [
                    'route' => 'edgar.campaign.reports',
                    'routeParameters' => [
                        'campaignId' => $campaignId,
                    ],
                    'extras' => ['icon' => 'stats'],
                ]
            ),
            self::ITEM__REMOVE => $this->createMenuItem(
                self::ITEM__REMOVE,
                [
                    'attributes' => [
                        'data-toggle' => 'modal',
                        'data-target' => '#trash-campaign-campaign-modal',
                    ],
                    'extras' => ['icon' => 'trash'],
                ]
            ),
        ]);

        return $menu;
    }

    /**
     * @return Message[]
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM__EDIT, 'menu'))->setDesc('Edit'),
            (new Message(self::ITEM__REPORTS, 'menu'))->setDesc('Reports'),
            (new Message(self::ITEM__REMOVE, 'menu'))->setDesc('Remove'),
        ];
    }
}
