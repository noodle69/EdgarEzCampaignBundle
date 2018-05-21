<?php

namespace Edgar\EzCampaignBundle\Converter;

use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzCampaignBundle\Service\FolderService;
use eZ\Bundle\EzPublishCoreBundle\Converter\RepositoryParamConverter;
use Welp\MailchimpBundle\Exception\MailchimpException;

class FolderParamConverter extends RepositoryParamConverter
{
    /** @var FolderService */
    private $folderService;

    /**
     * FolderParamConverter constructor.
     *
     * @param FolderService $folderService
     */
    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @return string
     */
    protected function getSupportedClass(): string
    {
        return Folder::class;
    }

    /**
     * @return string
     */
    protected function getPropertyName(): string
    {
        return 'folderId';
    }

    /**
     * @param $id
     *
     * @return Folder|string
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function loadValueObject($id)
    {
        $folder = $this->folderService->get($id);

        $folderData = new Folder();
        $folderData->setId($folder['id']);
        $folderData->setName($folder['name']);

        return $folderData;
    }
}
