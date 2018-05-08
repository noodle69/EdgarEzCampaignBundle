<?php

namespace Edgar\EzCampaign\Data\Mapper;

use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;
use Edgar\EzCampaign\Data\CampaignListCreateData;
use Edgar\EzCampaign\Data\CampaignListUpdateData;
use Edgar\EzCampaign\Values\API\CampaignList;

class CampaignListMapper  implements FormDataMapperInterface
{
    /**
     * Maps a ValueObject from eZ content repository to a data usable as underlying form data (e.g. create/update struct).
     *
     * @param ValueObject|CampaignList $campaignList
     * @param array $params
     *
     * @return CampaignListCreateData|CampaignListUpdateData
     */
    public function mapToFormData(ValueObject $campaignList, array $params = [])
    {
        if (!$this->isCampaignListNew($campaignList)) {
            $data = new CampaignListUpdateData([
                'id' => $campaignList->id,
                'name' => $campaignList->name,
                'company' => $campaignList->company,
                'address' => $campaignList->address,
                'city' => $campaignList->city,
                'state' => $campaignList->state,
                'zip' => $campaignList->zip,
                'country' => $campaignList->country,
                'permission_reminder' => $campaignList->permission_reminder,
                'from_name' => $campaignList->from_name,
                'from_email' => $campaignList->from_email,
                'subject' => $campaignList->subject,
                'language' => $campaignList->language
            ]);
        } else {
            $data = new CampaignListCreateData(['campaignList' => $campaignList]);
        }

        return $data;
    }

    private function isCampaignListNew(CampaignList $campaignList)
    {
        return $campaignList->id === null;
    }
}
