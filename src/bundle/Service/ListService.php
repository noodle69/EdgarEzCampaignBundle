<?php

namespace Edgar\EzCampaignBundle\Service;

use Edgar\EzCampaign\Values\Core\CampaignList;
use Edgar\EzCampaign\Values\ListCreateStruct;
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
    public function post(ListCreateStruct $list)
    {
        $countrySelected = key($list->country->countries);

        $return = $this->mailChimp->post('/lists', array(
            'name' => $list->name,
            'contact' => array(
                'company' => $list->company,
                'address1' => $list->address,
                'city' => $list->city,
                'state' => $list->state,
                'zip' => $list->zip,
                'country' => $countrySelected,
            ),
            'permission_reminder' => $list->permission_reminder,
            'campaign_defaults' => array(
                'from_name' => $list->from_name,
                'from_email' => $list->from_email,
                'subject' => $list->subject,
                'language' => $list->language
            ),
            'email_type_option' => false
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
    public function patch(array $list)
    {
        $return = $this->mailChimp->patch('/lists/' . $list['id'], array(
            'name' => $list['name'],
            'contact' => array(
                'company' => $list['contact']['company'],
                'address1' => $list['contact']['address1'],
                'city' => $list['contact']['city'],
                'state' => $list['contact']['state'],
                'zip' => $list['contact']['zip'],
                'country' => $list['contact']['country']
            ),
            'permission_reminder' => $list['permission_reminder'],
            'campaign_defaults' => array(
                'from_name' => $list['campaign_defaults']['from_name'],
                'from_email' => $list['campaign_defaults']['from_email'],
                'subject' => $list['campaign_defaults']['subject'],
                'language' => $list['campaign_defaults']['language']
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

    public function map(array $list): CampaignList
    {
        $list = new CampaignList([
            'id' => $list['id'],
            'name' => $list['name'],
            'company' => $list['contact']['company'],
            'address' => $list['contact']['address1'],
            'city' => $list['contact']['city'],
            'state' => $list['contact']['state'],
            'zip' => $list['contact']['zip'],
            'country' => $list['contact']['country'],
            'permission_reminder' => $list['permission_reminder'],
            'from_name' => $list['campaign_defaults']['from_name'],
            'from_email' => $list['campaign_defaults']['from_email'],
            'subject' => $list['campaign_defaults']['subject'],
            'language' => $list['campaign_defaults']['language']
        ]);

        return $list;
    }
}
