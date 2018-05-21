<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Data\FilterFolderData;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;

class FilterFolderMapper implements FormDataMapperInterface
{
    /**
     * @param ValueObject|null $folder
     * @param array $params
     *
     * @return FilterFolderData
     */
    public function mapToFormData(?ValueObject $folder, array $params = []): FilterFolderData
    {
        $data = new FilterFolderData([
            'folder' => $folder,
        ]);

        return $data;
    }
}
