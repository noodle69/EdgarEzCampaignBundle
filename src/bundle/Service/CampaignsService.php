<?php

namespace Edgar\EzCampaignBundle\Service;

class CampaignsService extends BaseService
{
    /**
     * @param int $offset
     * @param int $count
     * @param null|string $folderId
     * @param null|string $listId
     *
     * @return array
     */
    public function get(int $offset = 0, int $count = 10, ?string $folderId = null, ?string $listId = null): array
    {
        $args = [
            'offset' => $offset,
            'count' => $count,
            'sort_field' => 'create_time',
        ];

        if ($folderId) {
            $args['folder_id'] = $folderId;
        }

        if ($listId) {
            $args['list_id'] = $listId;
        }

        $campaigns = $this->mailChimp->get('/campaigns', $args);

        if (!$this->mailChimp->success()) {
            $campaigns = [
                'campaigns' => [],
                'total_items' => 0,
            ];
        }

        return $campaigns;
    }
}
