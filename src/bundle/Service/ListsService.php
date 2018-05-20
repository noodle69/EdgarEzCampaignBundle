<?php

namespace Edgar\EzCampaignBundle\Service;

class ListsService extends BaseService
{
    /**
     * @param int $offset
     * @param int $count
     *
     * @return array
     */
    public function get($offset = 0, $count = 10): array
    {
        $lists = $this->mailChimp->get('/lists', [
            'offset' => $offset,
            'count' => $count,
        ]);

        if (!$this->mailChimp->success()) {
            $lists = [
                'lists' => [],
                'total_items' => 0,
            ];
        }

        return $lists;
    }

    /**
     * @param string $listId
     *
     * @return int
     */
    public function countCampaigns(string $listId): int
    {
        $return = $this->mailChimp->get('/campaigns?list_id=' . $listId, []);

        if ($this->mailChimp->success()) {
            return $return['total_items'];
        }

        return 0;
    }
}
