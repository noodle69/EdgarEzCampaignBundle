<?php

namespace Edgar\EzCampaign\Form\Type\Field;

use Edgar\EzCampaign\Data\LanguageData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\Language;

class LanguageType extends AbstractType
{
    /** @var LanguageService */
    private $languageService;

    /**
     * LanguageType constructor.
     * @param LanguageService $languageService
     */
    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
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
                    $languages = [];

                    /** @var Language[] $eZLanguages */
                    $eZLanguages = $this->languageService->loadLanguages();
                    foreach ($eZLanguages as $language) {
                        $languageData = new LanguageData();
                        $languageData->setIdentifier($language->languageCode);
                        $languageData->setName($language->name);
                        $languages[$languageData->getName()] = $languageData;
                    }

                    return $languages;
                }),
                'choice_name' => 'name',
                'choice_value' => 'identifier',
            ]);
    }
}
