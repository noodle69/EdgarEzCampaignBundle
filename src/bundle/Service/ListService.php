<?php

namespace Edgar\EzCampaignBundle\Service;

use Edgar\EzCampaign\Values\Core\CampaignList;

class ListService extends BaseService
{
    /**
     * @param string $listID
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function get(string $listID): array
    {
        $list = $this->mailChimp->get('/lists/' . $listID, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $list;
    }

    /**
     * @param CampaignList $list
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function post(CampaignList $list): array
    {
        $return = $this->mailChimp->post('/lists', [
            'name' => $list->getName(),
            'contact' => [
                'company' => $list->getCompany(),
                'address1' => $list->getAddress(),
                'address2' => $list->getAddress2() ?? '',
                'city' => $list->getCity(),
                'state' => $list->getState(),
                'zip' => $list->getZip(),
                'country' => $list->getCountry(),
                'phone' => $list->getPhone() ?? '',
            ],
            'permission_reminder' => $list->getPermissionReminder(),
            'use_archive_bar' => $list->getUseArchiveBar() ? true : false,
            'campaign_defaults' => [
                'from_name' => $list->getFromName(),
                'from_email' => $list->getFromEmail(),
                'subject' => $list->getSubject(),
                'language' => $list->getLanguage(),
            ],
            'email_type_option' => false,
            'notify_on_subscribe' => $list->getNotifyOnSubscribe() ?? '',
            'notify_on_unsubscribe' => $list->getNotifyOnUnsubscribe() ?? '',
            'visibility' => $list->getVisibility(),
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $listId
     * @param CampaignList $list
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function patch(string $listId, CampaignList $list): array
    {
        $return = $this->mailChimp->patch('/lists/' . $listId, [
            'name' => $list->getName(),
            'contact' => [
                'company' => $list->getCompany(),
                'address1' => $list->getAddress(),
                'address2' => $list->getAddress2() ?? '',
                'city' => $list->getCity(),
                'state' => $list->getState(),
                'zip' => $list->getZip(),
                'country' => $list->getCountry(),
                'phone' => $list->getPhone() ?? '',
            ],
            'permission_reminder' => $list->getPermissionReminder(),
            'use_archive_bar' => $list->getUseArchiveBar(),
            'campaign_defaults' => [
                'from_name' => $list->getFromName(),
                'from_email' => $list->getFromEmail(),
                'subject' => $list->getSubject(),
                'language' => $list->getLanguage(),
            ],
            'email_type_option' => false,
            'notify_on_subscribe' => $list->getNotifyOnSubscribe() ?? '',
            'notify_on_unsubscribe' => $list->getNotifyOnUnsubscribe() ?? '',
            'visibility' => $list->getVisibility(),
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $listID
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function delete(string $listID): bool
    {
        $return = $this->mailChimp->delete('/lists/' . $listID, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $listID
     * @param string $email
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function subscribe(string $listID, string $email): array
    {
        $return = $this->mailChimp->post('/lists/' . $listID, [
            'members' => [
                [
                    'email_address' => $email,
                    'status' => 'subscribed',
                ],
            ],
            'update_existing' => true,
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $listID
     * @param string $email
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function unsubscribe(string $listID, string $email): array
    {
        $return = $this->mailChimp->post('/lists/' . $listID, [
            'members' => [
                [
                    'email_address' => $email,
                    'status' => 'unsubscribed',
                ],
            ],
            'update_existing' => true,
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }
}
