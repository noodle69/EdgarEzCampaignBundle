<?php

namespace Edgar\EzCampaign\Menu\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class ConfigureMenuEvent extends Event
{
    const CAMPAIGN_CREATE_SIDEBAR_RIGHT = 'edgar.menu.campaign_create.sidebar_right';
    const CAMPAIGN_EDIT_SIDEBAR_RIGHT = 'edgar.menu.campaign_edit.sidebar_right';
    const CAMPAIGN_VIEW_SIDEBAR_RIGHT = 'edgar.menu.campaign_view.sidebar_right';
    const LIST_CREATE_SIDEBAR_RIGHT = 'edgar.menu.list_create.sidebar_right';
    const LIST_EDIT_SIDEBAR_RIGHT = 'edgar.menu.list_edit.sidebar_right';
    const LIST_VIEW_SIDEBAR_RIGHT = 'edgar.menu.list_view.sidebar_right';

    /** @var FactoryInterface */
    private $factory;

    /** @var ItemInterface */
    private $menu;

    /** @var array|null */
    private $options;

    /**
     * @param FactoryInterface $factory
     * @param ItemInterface    $menu
     * @param array            $options
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, array $options = [])
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->options = $options;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }
}
