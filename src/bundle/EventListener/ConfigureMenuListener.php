<?php

namespace Edgar\EzCampaignBundle\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use JMS\TranslationBundle\Model\Message;
use Knp\Menu\ItemInterface;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM_CAMPAIGN = 'main__campaign';
    const ITEM_CAMPAIGN_CAMPAIGNS = 'main__campaign__campaigns';
    const ITEM_CAMPAIGN_LISTS = 'main__campaign__lists';

    /** @var PermissionResolver */
    private $permissionResolver;

    /**
     * ConfigureMenuListener constructor.
     *
     * @param PermissionResolver $permissionResolver
     */
    public function __construct(
        PermissionResolver $permissionResolver
    ) {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Add Audit menu.
     *
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $campaignMenu = $menu->addChild(self::ITEM_CAMPAIGN, []);

        $this->addCampaignMenuItems($campaignMenu);
    }

    /**
     * Add campaign menu items.
     *
     * @param ItemInterface $auditMenu
     */
    private function addCampaignMenuItems(ItemInterface $campaignMenu)
    {
        $menuItems = [];

        if ($this->permissionResolver->hasAccess('campaign', 'campaigns')) {
            $menuItems[self::ITEM_CAMPAIGN_CAMPAIGNS] = $campaignMenu->addChild(
                self::ITEM_CAMPAIGN_CAMPAIGNS,
                [
                    'route' => 'edgar.campaign.campaigns',
                    'extras' => ['icon' => 'newsletter'],
                ]
            );
        }

        if ($this->permissionResolver->hasAccess('campaign', 'lists')) {
            $menuItems[self::ITEM_CAMPAIGN_LISTS] = $campaignMenu->addChild(
                self::ITEM_CAMPAIGN_LISTS,
                [
                    'route' => 'edgar.campaign.lists',
                    'extras' => ['icon' => 'newsletter'],
                ]
            );
        }
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_CAMPAIGN, 'messages'))->setDesc('Campaign'),
            (new Message(self::ITEM_CAMPAIGN_CAMPAIGNS, 'messages'))->setDesc('Campaigns'),
            (new Message(self::ITEM_CAMPAIGN_LISTS, 'messages'))->setDesc('Subscription lists'),
        ];
    }
}
