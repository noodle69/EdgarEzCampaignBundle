<?php

namespace Edgar\EzCampaignBundle\Service;

use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class ListsService
 *
 * @package Edgar\EzCampaignBundle\Service
 */
class ListsService extends BaseService
{
    /**
     * List Campaign Lists
     * @param int $offset search offset
     * @param int $count search limit
     * @return array List of Campaign Lists
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function get($offset = 0, $count = 10)
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

    public function countCampaigns(string $listId): int
    {
        $return = $this->mailChimp->get('/campaigns?list_id=' . $listId, []);

        if ($this->mailChimp->success()) {
            return $return['total_items'];
        }

        return 0;
    }
}
