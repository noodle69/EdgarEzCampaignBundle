<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaignBundle\Service\CampaignsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignsType extends AbstractType
{
    /** @var CampaignsService  */
    protected $campaignsService;

    public function __construct(CampaignsService $campaignsService)
    {
        $this->campaignsService = $campaignsService;
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
                    $campaignsChoices = [];
                    $campaigns = $this->campaignsService->get(0, 0);
                    foreach ($campaigns['campaigns'] as $campaign) {
                        $campaignsChoices[$campaign['settings']['title']] = (object)$campaign;
                    }
                    return $campaignsChoices;
                }),
                'choice_value' => 'id',
            ]);
    }
}
