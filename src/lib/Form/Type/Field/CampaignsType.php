<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaignBundle\Service\CampaignsService;
use Edgar\EzCampaignBundle\Service\FolderService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignsType extends AbstractType
{
    /** @var CampaignsService */
    protected $campaignsService;

    /** @var FolderService  */
    protected $folderService;

    /**
     * CampaignsType constructor.
     * @param CampaignsService $campaignsService
     * @param FolderService $folderService
     */
    public function __construct(
        CampaignsService $campaignsService,
        FolderService $folderService
    ) {
        $this->campaignsService = $campaignsService;
        $this->folderService = $folderService;
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choice_loader' => new CallbackChoiceLoader(function () {
                    $folders = [];
                    $campaignsChoices = [];
                    $campaigns = $this->campaignsService->get(0, 0);

                    foreach ($campaigns['campaigns'] as $campaign) {
                        if (!isset($folders[$campaign['settings']['folder_id']])) {
                            $folder = $this->folderService->get($campaign['settings']['folder_id']);
                            $folders[$campaign['settings']['folder_id']] = $folder['name'];

                            $campaignsChoices[$folder['name']][$campaign['settings']['title']] = (object) $campaign;
                        }
                    }

                    return $campaignsChoices;
                }),
                'choice_value' => 'id',
            ]);
    }
}
