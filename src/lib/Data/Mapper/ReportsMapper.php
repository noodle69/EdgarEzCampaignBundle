<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Data\ReportsData;
use Edgar\EzCampaign\Values\CampaignCreateStruct;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;
use Edgar\EzCampaign\Data\CampaignCreateData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Values\API\Campaign;

class ReportsMapper implements FormDataMapperInterface
{
    /**
     * Maps a ValueObject from eZ content repository to a data usable as underlying form data (e.g. create/update struct).
     *
     * @param ValueObject|Campaign $campaign
     * @param array $params
     *
     * @return CampaignCreateData|CampaignUpdateData
     */
    public function mapToFormData(ValueObject $campaign, array $params = []): ReportsData
    {
        $data = new ReportsData([
            'campaign' => $campaign,
        ]);

        return $data;
    }
}
