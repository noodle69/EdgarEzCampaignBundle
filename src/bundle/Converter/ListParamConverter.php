<?php

namespace Edgar\EzCampaignBundle\Converter;

use Edgar\EzCampaign\Values\Core\CampaignList;
use Edgar\EzCampaignBundle\Service\ListService;
use eZ\Bundle\EzPublishCoreBundle\Converter\RepositoryParamConverter;

class ListParamConverter extends RepositoryParamConverter
{
    /** @var ListService  */
    private $listService;

    public function __construct(ListService $listService)
    {
        $this->listService = $listService;
    }

    protected function getSupportedClass()
    {
        return CampaignList::class;
    }

    protected function getPropertyName()
    {
        return 'listId';
    }

    public function loadValueObject($id): CampaignList
    {
        $list = $this->listService->get($id);

        $listData = new CampaignList();
        $listData->setId($list['id']);
        $listData->setName($list['name']);
        $listData->setCompany($list['contact']['company']);
        $listData->setAddress($list['contact']['address1']);
        $listData->setAddress2($list['contact']['address2']);
        $listData->setCity($list['contact']['city']);
        $listData->setState($list['contact']['state']);
        $listData->setZip($list['contact']['zip']);
        $listData->setCountry($list['contact']['country']);
        $listData->setPhone($list['contact']['phone']);
        $listData->setPermissionReminder($list['permission_reminder']);
        $listData->setUseArchiveBar($list['use_archive_bar']);
        $listData->setFromName($list['campaign_defaults']['from_name']);
        $listData->setFromEmail($list['campaign_defaults']['from_email']);
        $listData->setSubject($list['campaign_defaults']['subject']);
        $listData->setLanguage($list['campaign_defaults']['language']);
        $listData->setNotifyOnSubscribe($list['notify_on_subscribe']);
        $listData->setNotifyOnUnSubscribe($list['notify_on_unsubscribe']);
        $listData->setVisibility($list['visibility']);

        return $listData;
    }
}
