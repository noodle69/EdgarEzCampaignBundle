<?php

namespace Edgar\EzCampaignBundle\Service;

use DrewM\MailChimp\MailChimp;
use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class CampaignService
 *
 * @package Edgar\EzCampaignBundle\Service
 */
class CampaignService extends BaseService
{
    /** @var ListService $listService Campaign List service */
    protected $listService;

    /**
     * CampaignService constructor.
     *
     * @param MailChimp   $mailChimp MailChimp service
     * @param ListService $listService Campaign List service
     */
    public function __construct(
        MailChimp $mailChimp,
        ListService $listService
    )
    {
        parent::__construct($mailChimp);
        $this->listService = $listService;
    }

    /**
     * Retrive Campaign by ID
     *
     * @param int $campaignID Campaign ID
     * @param array $fields Fields to return
     * @return array|false Campaign informations
     * @throws MailchimpException MailChimpException
     */
    public function get($campaignID, array $fields = []): array
    {
        $campaign = $this->mailChimp->get('/campaigns/' . $campaignID, $fields);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaign;
    }

    /**
     * Create new Campaign
     *
     * @param string $listID Campaign List ID
     * @param string $subjectLine Subject line information
     * @param string $title Title information
     * @param string $fromName From name information
     * @param string $replyTo Reply to information
     * @param string $folderID Campaign Folder ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function post($listID, $subjectLine, $title, $fromName, $replyTo, $folderID)
    {
        $return = $this->mailChimp->post('/campaigns', array(
            'type' => 'regular',
            'recipients' => array(
                'list_id' => $listID
            ),
            'settings' => array(
                'subject_line' => $subjectLine,
                'title' => $title,
                'from_name' => $fromName,
                'reply_to' => $replyTo,
                'folder_id' => $folderID
            )
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Update Campaign
     *
     * @param string $campaignID Campaign ID
     * @param string $listID Campaign List ID
     * @param string $subjectLine Subject line information
     * @param string $title Title information
     * @param string $fromName From name information
     * @param string $replyTo Reply to information
     * @param string $folderID Campaign Folder ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function patch($campaignID, $listID, $subjectLine, $title, $fromName, $replyTo, $folderID)
    {
        $return = $this->mailChimp->patch('/campaigns/' . $campaignID, array(
            'recipients' => array(
                'list_id' => $listID
            ),
            'settings' => array(
                'subject_line' => $subjectLine,
                'title' => $title,
                'fromName' => $fromName,
                'reply_to' => $replyTo,
                'folder_id' => $folderID
            )
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Delete Campaign
     *
     * @param string $campaignID Campaign ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function delete($campaignID)
    {
        $return = $this->mailChimp->delete('/campaigns/' . $campaignID, array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Send Campaign test
     *
     * @param string $campaignID Campaign ID
     * @param string $email email where test email is sent
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function test($campaignID, $email)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/test', array(
            'test_emails' => array($email),
            'send_type' => 'html'
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Send Campaign
     *
     * @param string $campaignID Campaign ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function send($campaignID)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/send', array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Pause Campaign
     *
     * @param string $campaignID Campaign ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function pause($campaignID)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/pause', array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Resume Campaign
     *
     * @param string $campaignID Campaign ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function resume($campaignID)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/resume', array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Schedule Campaign
     *
     * @param string $campaignID Campaign ID
     * @param $scheduleTime
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function schedule($campaignID, $scheduleTime)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/schedule', array(
            'schedule_time' => $scheduleTime
        ));

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Remove Campaign schedule programmation
     *
     * @param string $campaignID Campaign ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function unschedule($campaignID)
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/unschedule', array());

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Subscribe to a Campaign
     *
     * @param string $campaignID Campaign ID
     * @param string $email member email
     * @throws MailchimpException MailChimpException
     */
    public function subscribe($campaignID, $email)
    {
        try {
            $campaign = $this->get($campaignID);
            if ($campaign) {
                $this->listService->subscribe($campaign['recipients']['list_id'], $email);
            }
        } catch (MailchimpException $exception) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }
    }
}
