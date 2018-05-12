<?php

namespace Edgar\EzCampaign\Form\Factory;

use Edgar\EzCampaign\Data\CampaignCreateData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Data\FolderCreateData;
use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Data\ListCreateData;
use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Data\ListUpdateData;
use Edgar\EzCampaign\Data\ReportsData;
use Edgar\EzCampaign\Form\Type\CampaignCreateType;
use Edgar\EzCampaign\Form\Type\CampaignUpdateType;
use Edgar\EzCampaign\Form\Type\FolderCreateType;
use Edgar\EzCampaign\Form\Type\FoldersDeleteType;
use Edgar\EzCampaign\Form\Type\ListCreateType;
use Edgar\EzCampaign\Form\Type\CampaignsDeleteType;
use Edgar\EzCampaign\Form\Type\ListsDeleteType;
use Edgar\EzCampaign\Form\Type\ListUpdateType;
use Edgar\EzCampaign\Form\Type\ReportsType;
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

    public function deleteFolders(
        FoldersDeleteData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(FoldersDeleteType::class);

        return $this->formFactory->createNamed($name, FoldersDeleteType::class, $data);
    }

    public function deleteLists(
        ListsDeleteData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ListsDeleteType::class);

        return $this->formFactory->createNamed($name, ListsDeleteType::class, $data);
    }

    /**
     * @param CampaignCreateData|null $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function createCampaign(
        ?CampaignCreateData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            CampaignCreateType::class,
            $data ?? new CampaignCreateData()
        );
    }

    /**
     * @param FolderCreateData|null $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function createFolder(
        ?FolderCreateData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(FolderCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            FolderCreateType::class,
            $data ?? new FolderCreateData()
        );
    }

    /**
     * @param ListCreateData|null $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function createList(
        ?ListCreateData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ListCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            ListCreateType::class,
            $data ?? new ListCreateData()
        );
    }

    /**
     * @param CampaignUpdateData $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function updateCampaign(
        CampaignUpdateData $data,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: sprintf('update-campaign-%d', $data->getId());

        return $this->formFactory->createNamed($name, CampaignUpdateType::class, $data);
    }

    /**
     * @param ListUpdateData $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function updateList(
        ListUpdateData $data,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: sprintf('update-list-%d', $data->getId());

        return $this->formFactory->createNamed($name, ListUpdateType::class, $data);
    }

    /**
     * @param FolderCreateData|null $data
     * @param null|string $name
     *
     * @return FormInterface
     */
    public function reportsChooseCampaign(
        ?ReportsData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ReportsType::class);

        return $this->formFactory->createNamed(
            $name,
            ReportsType::class,
            $data ?? new ReportsData()
        );
    }
}
