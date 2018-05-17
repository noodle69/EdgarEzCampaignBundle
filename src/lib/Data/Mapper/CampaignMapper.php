<?php

namespace Edgar\EzCampaign\Data\Mapper;

use Edgar\EzCampaign\Values\CampaignCreateStruct;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\ListService;
use eZ\Publish\API\Repository\Values\ValueObject;
use EzSystems\EzPlatformAdminUi\Exception\InvalidArgumentException;
use EzSystems\RepositoryForms\Data\Mapper\FormDataMapperInterface;
use Edgar\EzCampaign\Data\CampaignCreateData;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Values\API\Campaign;

class CampaignMapper implements FormDataMapperInterface
{
    protected $listService;

    protected $folderService;

    public function __construct(
        ListService $listService,
        FolderService $folderService
    ) {
        $this->listService = $listService;
        $this->folderService = $folderService;
    }

    /**
     * Maps a ValueObject from eZ content repository to a data usable as underlying form data (e.g. create/update struct).
     *
     * @param ValueObject|Campaign $campaign
     * @param array $params
     *
     * @return CampaignCreateData|CampaignUpdateData
     */
    public function mapToFormData(ValueObject $campaign, array $params = [])
    {
        if (!$this->isCampaignNew($campaign)) {
            $list = $this->listService->get($campaign->list_id);
            $list = $this->listService->map($list);
            $listData = (new ListMapper())->mapToFormData($list);

            $folder = $this->folderService->get($campaign->folder_id);
            $folder = $this->folderService->map($folder);
            $folderData = (new FolderMapper())->mapToFormData($folder);

            $data = new CampaignUpdateData([
                'id' => $campaign->id,
                'list_id' => $listData,
                'folder_id' => $folderData,
                'subject_line' => $campaign->subject_line,
                'title' => $campaign->title,
                'from_name' => $campaign->from_name,
                'reply_to' => $campaign->reply_to,
            ]);
        } else {
            $data = new CampaignCreateData(['campaign' => $campaign]);
        }

        return $data;
    }

    private function isCampaignNew(Campaign $campaign)
    {
        return $campaign->id === null;
    }

    public function reverseMap($data): CampaignCreateStruct
    {
        if (!$data instanceof CampaignCreateData) {
            throw new InvalidArgumentException('data', 'must be an instance of ' . CampaignCreateData::class);
        }

        return new CampaignCreateStruct([
            'list_id' => $data->list_id->id,
            'subject_line' => $data->subject_line,
            'title' => $data->title,
            'from_name' => $data->from_name,
            'reply_to' => $data->reply_to,
            'folder_id' => $data->folder_id->id,
        ]);
    }
}
