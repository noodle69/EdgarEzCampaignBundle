<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaignBundle\Service\ListsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends AbstractType
{
    /** @var ListsService  */
    protected $listsService;

    public function __construct(ListsService $listsService)
    {
        $this->listsService = $listsService;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $listsChoices = [];
                    $lists = $this->listsService->get(0, 0);
                    foreach ($lists['lists'] as $list) {
                        $listsChoices[$list['name']] = (object)$list;
                    }
                    return $listsChoices;
                }),
                'choice_value' => 'id',
            ]);
    }
}
