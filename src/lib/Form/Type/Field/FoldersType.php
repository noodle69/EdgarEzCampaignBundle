<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaignBundle\Service\FoldersService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoldersType extends AbstractType
{
    /** @var FoldersService */
    protected $foldersService;

    public function __construct(FoldersService $foldersService)
    {
        $this->foldersService = $foldersService;
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
                    $foldersChoices = [];
                    $folders = $this->foldersService->get(0, 0);
                    foreach ($folders['folders'] as $folder) {
                        $foldersChoices[$folder['name']] = (object) $folder;
                    }

                    return $foldersChoices;
                }),
                'choice_value' => 'id',
            ]);
    }
}
