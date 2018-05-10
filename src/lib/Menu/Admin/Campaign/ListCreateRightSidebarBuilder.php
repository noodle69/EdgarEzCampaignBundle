<?php

namespace Edgar\EzCampaign\Menu\Admin\Campaign;

use Edgar\EzCampaign\Menu\Event\ConfigureMenuEvent;
use eZ\Publish\API\Repository\Exceptions as ApiExceptions;
use EzSystems\EzPlatformAdminUi\Menu\AbstractBuilder;
use InvalidArgumentException;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;

class ListCreateRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /* Menu items */
    const ITEM__CREATE = 'list_create__sidebar_right__create';
    const ITEM__CANCEL = 'list_create__sidebar_right__cancel';

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return ConfigureMenuEvent::LIST_CREATE_SIDEBAR_RIGHT;
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
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $this->factory->createItem('root');

        $menu->setChildren([
            self::ITEM__CREATE => $this->createMenuItem(
                self::ITEM__CREATE,
                [
                    'attributes' => [
                        'class' => 'btn--trigger',
                        'data-click' => '#list_create_save',
                    ],
                    'extras' => ['icon' => 'publish'],
                ]
            ),
            self::ITEM__CANCEL => $this->createMenuItem(
                self::ITEM__CANCEL,
                [
                    'route' => 'edgar.campaign.lists',
                    'extras' => ['icon' => 'circle-close'],
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
            (new Message(self::ITEM__CREATE, 'message'))->setDesc('Create'),
            (new Message(self::ITEM__CANCEL, 'message'))->setDesc('Discard changes'),
        ];
    }
}
