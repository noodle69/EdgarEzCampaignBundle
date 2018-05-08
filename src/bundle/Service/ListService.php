<?php

namespace Edgar\EzCampaignBundle\Service;

use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class ListService
 *
 * @package Edgar\EzCampaignBundle\Service
 */
class ListService extends BaseService
{
    /**
     * Retrive Campaign List information
     *
     * @param string $listID Campaign List ID
     * @return array Campaign List information
     * @throws MailchimpException MailChimpException
     */
    public function get($listID)
    {
        $list = $this->mailChimp->get('/lists/' . $listID, array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $list;
    }

    /**
     * Create new Campaign List
     *
     * @param string $name Campaign List name
     * @param string $company Campaign List contact company information
     * @param string $address Campaign List contact address information
     * @param string $city Campaign List contact city information
     * @param string $state Campaign List contact state information
     * @param string $zip Campaign List contact zip information
     * @param string $country Campaign List contact country information
     * @param string $permission_reminder Campaign List permission reminder
     * @param string $from_name Campaign List default from name
     * @param string $from_email Campaign List default from email
     * @param string $subject Campaign List default subject
     * @param string $language Campaign List default language
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function post(
        $name, $company, $address, $city, $state, $zip, $country, $permission_reminder,
        $from_name, $from_email, $subject, $language
    )
    {
        $return = $this->mailChimp->post('/lists', array(
            'name' => $name,
            'contact' => array(
                'company' => $company,
                'address1' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country
            ),
            'permission_reminder' => $permission_reminder,
            'campaign_defaults' => array(
                'from_name' => $from_name,
                'from_email' => $from_email,
                'subject' => $subject,
                'language' => $language
            ),
            'email_type_option' => true
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Update Campaign List information
     *
     * @param string $listID Campaign List ID
     * @param string $name Campaign List name
     * @param string $company Campaign List contact company information
     * @param string $address Campaign List contact address information
     * @param string $city Campaign List contact city information
     * @param string $state Campaign List contact state information
     * @param string $zip Campaign List contact zip information
     * @param string $country Campaign List contact country information
     * @param string $permission_reminder Campaign List permission reminder
     * @param string $from_name Campaign List default from name
     * @param string $from_email Campaign List default from email
     * @param string $subject Campaign List default subject
     * @param string $language Campaign List default language
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function patch(
        $listID, $name, $company, $address, $city, $state, $zip, $country, $permission_reminder,
        $from_name, $from_email, $subject, $language
    )
    {
        $return = $this->mailChimp->patch('/lists/' . $listID, array(
            'name' => $name,
            'contact' => array(
                'company' => $company,
                'address1' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country
            ),
            'permission_reminder' => $permission_reminder,
            'campaign_defaults' => array(
                'from_name' => $from_name,
                'from_email' => $from_email,
                'subject' => $subject,
                'language' => $language
            ),
            'email_type_option' => true
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Delete Campaign List
     *
     * @param string $listID Campaign List ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function delete($listID)
    {
        $return = $this->mailChimp->delete('/lists/' . $listID, array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Subscribe to Campaign List
     *
     * @param string $listID Campaign List ID
     * @param string $email member email
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function subscribe($listID, $email)
    {
        $return = $this->mailChimp->post('/lists/' . $listID, array(
            'members' => array(
                array(
                    'email_address' => $email,
                    'status' => 'subscribed'
                )
            ),
            'update_existing' => true
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Unsubscribe to Campaign List
     *
     * @param string $listID Campaign List ID
     * @param string $email member email
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function unsubscribe($listID, $email)
    {
        $return = $this->mailChimp->post('/lists/' . $listID, array(
            'members' => array(
                array(
                    'email_address' => $email,
                    'status' => 'unsubscribed'
                )
            ),
            'update_existing' => true
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }
}
