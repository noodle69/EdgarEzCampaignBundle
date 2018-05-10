<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Values\ListCreateStruct;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;
use Edgar\EzCampaign\Data\ListCreateData;
use Edgar\EzCampaign\Data\ListUpdateData;
use Edgar\EzCampaign\Values\API\CampaignList;

class ListMapper  implements FormDataMapperInterface
{
    /**
     * Maps a ValueObject from eZ content repository to a data usable as underlying form data (e.g. create/update struct).
     *
     * @param ValueObject|CampaignList $campaignList
     * @param array $params
     *
     * @return ListCreateData|ListUpdateData
     */
    public function mapToFormData(ValueObject $campaignList, array $params = [])
    {
        if (!$this->isCampaignListNew($campaignList)) {
            $data = new ListUpdateData([
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
            $data = new ListCreateData(['campaignList' => $campaignList]);
        }

        return $data;
    }

    private function isCampaignListNew(CampaignList $campaignList)
    {
        return $campaignList->id === null;
    }

    public function reverseMap($data): ListCreateStruct
    {
        if (!$data instanceof ListCreateData) {
            throw new InvalidArgumentException('data', 'must be an instance of ' . CampaignCreateData::class);
        }

        return new ListCreateStruct([
            'name' => $data->name,
            'company' => $data->company,
            'address' => $data->address,
            'city' => $data->city,
            'state' => $data->state,
            'zip' => $data->zip,
            'country' => $data->country,
            'permission_reminder' => $data->permission_reminder,
            'from_name' => $data->from_name,
            'from_email' => $data->from_email,
            'subject' => $data->subject,
            'language' => $data->language,
        ]);
    }
}
