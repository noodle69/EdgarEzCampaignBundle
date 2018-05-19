<?php

namespace Edgar\EzCampaign\Form\Factory;

use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Data\CampaignsDeleteData;
use Edgar\EzCampaign\Data\ListsDeleteData;
use Edgar\EzCampaign\Data\ReportsData;
use Edgar\EzCampaign\Data\SubscribeData;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignCancelScheduleType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignScheduleType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignSendType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignCreateType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignDeleteType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignUpdateType;
use Edgar\EzCampaign\Form\Type\Campaign\CampaignsDeleteType;
use Edgar\EzCampaign\Form\Type\Folder\FolderCreateType;
use Edgar\EzCampaign\Form\Type\Folder\FoldersDeleteType;
use Edgar\EzCampaign\Form\Type\CampaignList\ListCreateType;
use Edgar\EzCampaign\Form\Type\CampaignList\ListDeleteType;
use Edgar\EzCampaign\Form\Type\CampaignList\ListsDeleteType;
use Edgar\EzCampaign\Form\Type\CampaignList\ListUpdateType;
use Edgar\EzCampaign\Form\Type\ReportsType;
use Edgar\EzCampaign\Form\Type\SubscribeType;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaign\Values\Core\CampaignList;
use Edgar\EzCampaign\Values\Core\Folder;
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

    public function createCampaign(
        ?Campaign $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            CampaignCreateType::class,
            $data ?? new Campaign()
        );
    }

    public function createFolder(
        ?Folder $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(FolderCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            FolderCreateType::class,
            $data ?? new Folder()
        );
    }

    public function createList(
        ?CampaignList $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ListCreateType::class);

        return $this->formFactory->createNamed(
            $name,
            ListCreateType::class,
            $data ?? new CampaignList()
        );
    }

    public function updateCampaign(
        Campaign $data,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: sprintf('update-campaign-%d', $data->getId());

        return $this->formFactory->createNamed($name, CampaignUpdateType::class, $data);
    }

    public function updateList(
        CampaignList $data,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: sprintf('update-list-%d', $data->getId());

        return $this->formFactory->createNamed($name, ListUpdateType::class, $data);
    }

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

    public function subscribe(
        ?SubscribeData $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(SubscribeType::class);

        return $this->formFactory->createNamed(
            $name,
            SubscribeType::class,
            $data ?? new SubscribeData()
        );
    }

    public function deleteCampaign(
        Campaign $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignDeleteType::class);

        return $this->formFactory->createNamed($name, CampaignDeleteType::class, $data);
    }

    public function deleteList(
        CampaignList $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(ListDeleteType::class);

        return $this->formFactory->createNamed($name, ListDeleteType::class, $data);
    }

    public function sendCampaign(
        ?Campaign $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignSendType::class);

        return $this->formFactory->createNamed($name, CampaignSendType::class, $data);
    }

    public function scheduleCampaign(
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignScheduleType::class);

        return $this->formFactory->createNamed($name, CampaignScheduleType::class, null);
    }

    public function cancelScheduleCampaign(
        ?Campaign $data = null,
        ?string $name = null
    ): FormInterface {
        $name = $name ?: StringUtil::fqcnToBlockPrefix(CampaignCancelScheduleType::class);

        return $this->formFactory->createNamed($name, CampaignCancelScheduleType::class, $data);
    }
}
