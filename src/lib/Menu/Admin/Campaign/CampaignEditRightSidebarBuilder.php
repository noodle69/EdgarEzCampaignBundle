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

class CampaignEditRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /* Menu items */
    const ITEM__SAVE = 'campaign_edit__sidebar_right__save';
    const ITEM__CANCEL = 'campaign_edit__sidebar_right__cancel';

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return ConfigureMenuEvent::CAMPAIGN_EDIT_SIDEBAR_RIGHT;
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
        $saveId = $options['save_id'];

        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $this->factory->createItem('root');

        $menu->setChildren([
            self::ITEM__SAVE => $this->createMenuItem(
                self::ITEM__SAVE,
                [
                    'attributes' => [
                        'class' => 'btn--trigger',
                        'data-click' => sprintf('#%s', $saveId),
                    ],
                    'extras' => ['icon' => 'save'],
                ]
            ),
            self::ITEM__CANCEL => $this->createMenuItem(
                self::ITEM__CANCEL,
                [
                    'route' => 'edgar.campaign.campaigns',
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
            (new Message(self::ITEM__SAVE, 'menu'))->setDesc('Save'),
            (new Message(self::ITEM__CANCEL, 'menu'))->setDesc('Discard changes'),
        ];
    }
}
