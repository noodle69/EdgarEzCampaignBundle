<?php

namespace Edgar\EzCampaignBundle\Converter;

use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzCampaignBundle\Service\FolderService;
use eZ\Bundle\EzPublishCoreBundle\Converter\RepositoryParamConverter;

class FolderParamConverter extends RepositoryParamConverter
{
    /** @var FolderService  */
    private $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    protected function getSupportedClass()
    {
        return Folder::class;
    }

    protected function getPropertyName()
    {
        return 'folderId';
    }

    public function loadValueObject($id)
    {
        $folder = $this->folderService->get($id);

        $folderData = new Folder();
        $folderData->setId($folder['id']);
        $folderData->setName($folder['name']);

        return $folderData;
    }
}
