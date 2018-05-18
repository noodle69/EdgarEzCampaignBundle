<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\FoldersService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FolderType extends AbstractType
{
    /** @var FoldersService  */
    protected $foldersService;

    /** @var FolderService  */
    protected $folderService;

    public function __construct(
        FoldersService $foldersService,
        FolderService $folderService
    ) {
        $this->foldersService = $foldersService;
        $this->folderService = $folderService;
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
                        $foldersChoices[$folder['name']] = (object)$folder;
                    }
                    return $foldersChoices;
                }),
                'data_class' => Folder::class,
                'choice_name' => 'id',
                'choice_value' => 'id',
            ]);
    }
}
