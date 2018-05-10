<?php

namespace Edgar\EzCampaign\Form\Factory;

use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Form\Type\CampaignsDeleteType;
use Edgar\EzCampaign\Form\Type\ListsDeleteType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormFactory
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function deleteCampaigns(
        CampaignsDeleteData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignsDeleteType::class);

        return $this->formFactory->createNamed($name, CampaignsDeleteType::class, $data);
    }

    public function deleteLists(
        ListsDeleteData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ListsDeleteType::class);

        return $this->formFactory->createNamed($name, ListsDeleteType::class, $data);
    }
}
