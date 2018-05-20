<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Data\ReportsData;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;

class ReportsMapper implements FormDataMapperInterface
{
    /**
     * @param ValueObject $campaign
     * @param array $params
     *
     * @return ReportsData
     */
    public function mapToFormData(ValueObject $campaign, array $params = []): ReportsData
    {
        $data = new ReportsData([
            'campaign' => $campaign,
        ]);

        return $data;
    }
}
