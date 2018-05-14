<?php

namespace Edgar\EzCampaign\Menu\Admin\Campaign;

use Edgar\EzCampaign\Menu\Event\ConfigureMenuEvent;
use Edgar\EzCampaign\Values\Core\CampaignList;
use eZ\Publish\API\Repository\Exceptions as ApiExceptions;
use EzSystems\EzPlatformAdminUi\Menu\AbstractBuilder;
use InvalidArgumentException;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;

class ListViewRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /* Menu items */
    const ITEM__REMOVE = 'list_view__sidebar_right__remove';

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return ConfigureMenuEvent::LIST_VIEW_SIDEBAR_RIGHT;
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
        $listId = $options['list_id'];

        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $this->factory->createItem('root');

        $menu->setChildren([
            self::ITEM__REMOVE => $this->createMenuItem(
                self::ITEM__REMOVE,
                [
                    'attributes' => [
                        'data-toggle' => 'modal',
                        'data-target' => '#trash-campaign-list-modal',
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
            (new Message(self::ITEM__REMOVE, 'menu'))->setDesc('Remove'),
        ];
    }
}
