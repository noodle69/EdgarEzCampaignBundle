<?php

namespace Edgar\EzCampaignBundle\Service;

class CampaignsService extends BaseService
{
    /**
     * @param int $offset
     * @param int $count
     *
     * @return array
     */
    public function get(int $offset = 0, int $count = 10): array
    {
        $campaigns = $this->mailChimp->get('/campaigns', [
            'offset' => $offset,
            'count' => $count,
        ]);

        if (!$this->mailChimp->success()) {
            $campaigns = [
                'campaigns' => [],
                'total_items' => 0,
            ];
        }

        return $campaigns;
    }
}
