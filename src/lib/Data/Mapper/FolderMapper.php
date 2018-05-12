<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Values\FolderCreateStruct;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;
use Edgar\EzCampaign\Data\FolderCreateData;
use Edgar\EzCampaign\Data\FolderUpdateData;
use Edgar\EzCampaign\Values\API\Folder;

class FolderMapper implements FormDataMapperInterface
{
    /**
     * Maps a ValueObject from eZ content repository to a data usable as underlying form data (e.g. create/update struct).
     *
     * @param ValueObject|Folder $campaignFolder
     * @param array $params
     *
     * @return FolderCreateData|FolderUpdateData
     */
    public function mapToFormData(ValueObject $campaignFolder, array $params = [])
    {
        if (!$this->isCampaignFolderNew($campaignFolder)) {
            $data = new FolderUpdateData(['campaignFolder' => $campaignFolder]);
        } else {
            $data = new FolderCreateData(['campaignFolder' => $campaignFolder]);
        }

        return $data;
    }

    private function isCampaignFolderNew(Folder $campaignFolder)
    {
        return $campaignFolder->id === null;
    }

    public function reverseMap($data): FolderCreateStruct
    {
        if (!$data instanceof FolderCreateData) {
            throw new InvalidArgumentException('data', 'must be an instance of ' . CampaignCreateData::class);
        }

        return new FolderCreateStruct([
            'name' => $data->name,
        ]);
    }
}
