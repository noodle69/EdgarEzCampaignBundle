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
        $lists = $this->mailChimp->get('/lists', array(
            'offset' => $offset,
            'count' => $count
        ));

        if (!$this->mailChimp->success()) {
            $lists = array(
                'lists' => array(),
                'total_items' => 0
            );
        }

        return $lists;
    }
}
