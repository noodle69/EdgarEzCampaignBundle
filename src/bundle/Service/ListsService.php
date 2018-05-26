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
            'sort_field' => 'date_created',
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
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function countCampaigns(string $listId): int
    {
        $args = [
            'offset' => 0,
            'count' => 1,
            'list_id' => $listId,
        ];

        $campaigns = $this->mailChimp->get('/campaigns', $args);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaigns['total_items'];
    }
}
