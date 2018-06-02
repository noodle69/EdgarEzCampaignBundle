<?php

namespace Edgar\EzCampaignBundle\Converter;

use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaignBundle\Service\CampaignService;
use eZ\Bundle\EzPublishCoreBundle\Converter\RepositoryParamConverter;

class CampaignParamConverter extends RepositoryParamConverter
{
    /** @var CampaignService */
    private $campaignService;

    /** @var ListParamConverter */
    private $listParamConverter;

    /** @var FolderParamConverter */
    private $folderParamConverter;

    /**
     * CampaignParamConverter constructor.
     *
     * @param CampaignService $campaignService
     * @param ListParamConverter $listParamConverter
     * @param FolderParamConverter $folderParamConverter
     */
    public function __construct(
        CampaignService $campaignService,
        ListParamConverter $listParamConverter,
        FolderParamConverter $folderParamConverter
    ) {
        $this->campaignService = $campaignService;
        $this->listParamConverter = $listParamConverter;
        $this->folderParamConverter = $folderParamConverter;
    }

    /**
     * @return string
     */
    protected function getSupportedClass(): string
    {
        return Campaign::class;
    }

    /**
     * @return string
     */
    protected function getPropertyName(): string
    {
        return 'campaignId';
    }

    /**
     * @param $id
     *
     * @return Campaign|string
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    protected function loadValueObject($id)
    {
        $campaign = $this->campaignService->get($id);
        $list = $this->listParamConverter->loadValueObject($campaign['recipients']['list_id']);
        $folder = $this->folderParamConverter->loadValueObject($campaign['settings']['folder_id']);

        $campaignData = new Campaign();
        $campaignData->setId($campaign['id']);
        $campaignData->setListId($campaign['recipients']['list_id']);
        $campaignData->setList($list);
        $campaignData->setFolderId($campaign['settings']['folder_id']);
        $campaignData->setFolder($folder);
        $campaignData->setSubjectLine($campaign['settings']['subject_line']);
        $campaignData->setTitle($campaign['settings']['title']);
        $campaignData->setFromName($campaign['settings']['from_name']);
        $campaignData->setReplyTo($campaign['settings']['reply_to']);

        return $campaignData;
    }
}
