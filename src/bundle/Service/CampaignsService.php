<?php

namespace Edgar\EzCampaignBundle\Service;

use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class CampaignsService
 *
 * @package Edgar\EzCampaignBundle\Service
 */
class CampaignsService extends BaseService
{
    /**
     * List Campgigns
     *
     * @param int $offset search offset
     * @param int $count search limit
     * @return array List of Campaigns
     * @throws MailchimpException MailChimpException
     */
    public function get($offset = 0, $count = 10)
    {
        $campaigns = $this->mailChimp->get('/campaigns', array(
            'offset' => $offset,
            'count' => $count
        ));

        if (!$this->mailChimp->success()) {
            $campaigns = array(
                'campaigns' => array(),
                'total_items' => 0
            );
        }

        return $campaigns;
    }

    /**
     * Search Campgian
     *
     * @param string $query search query
     * @return array List of Campaigns
     * @param string $email member email
     * @throws MailchimpException MailChimpException
     */
    public function search($query)
    {
        $campaigns = $this->mailChimp->get('/search-campaigns', array(
            'query' => $query,
            'fields' => 'id,settings.title'
        ));

        if (!$this->mailChimp->success()) {
            $campaigns = array(
                'campaigns' => array(),
                'total_items' => 0
            );
        }

        return $campaigns;
    }
}
