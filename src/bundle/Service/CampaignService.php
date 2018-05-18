<?php

namespace Edgar\EzCampaignBundle\Service;

use DrewM\MailChimp\MailChimp;
use Edgar\EzCampaign\Data\CampaignUpdateData;
use Edgar\EzCampaign\Values\Core\Campaign;
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

    /** @var FolderService  */
    protected $folderService;

    /**
     * CampaignService constructor.
     *
     * @param MailChimp   $mailChimp MailChimp service
     * @param ListService $listService Campaign List service
     */
    public function __construct(
        MailChimp $mailChimp,
        ListService $listService,
        FolderService $folderService
    ) {
        parent::__construct($mailChimp);
        $this->listService = $listService;
        $this->folderService = $folderService;
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
    public function post(Campaign $campaign)
    {
        $return = $this->mailChimp->post('/campaigns', [
            'type' => 'regular',
            'recipients' => [
                'list_id' => $campaign->getListId(),
            ],
            'settings' => [
                'subject_line' => $campaign->getSubjectLine(),
                'title' => $campaign->getTitle(),
                'from_name' => $campaign->getFromName(),
                'reply_to' => $campaign->getReplyTo(),
                'folder_id' => $campaign->getFolderId(),
            ],
        ]);

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
    public function patch(string $campaignId, CampaignUpdateData $campaign)
    {
        $return = $this->mailChimp->patch('/campaigns/' . $campaignId, [
            'type' => 'regular',
            'recipients' => [
                'list_id' => $campaign->list_id->id,
            ],
            'settings' => [
                'subject_line' => $campaign->subject_line,
                'title' => $campaign->title,
                'fromName' => $campaign->from_name,
                'reply_to' => $campaign->reply_to,
                'folder_id' => $campaign->folder_id->id,
            ],
        ]);

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
        $return = $this->mailChimp->delete('/campaigns/' . $campaignID, []);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/test', [
            'test_emails' => [$email],
            'send_type' => 'html',
        ]);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/send', []);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/pause', []);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/resume', []);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/schedule', [
            'schedule_time' => $scheduleTime,
        ]);

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
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/unschedule', []);

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

    public function putContent(string $campaignId, string $url)
    {
        $url = 'http://www.smile.fr';
        $return = $this->mailChimp->put('/campaigns/' . $campaignId . '/content', [
            'url' => $url,
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    public function map(array $campaign): Campaign
    {
        $campaign = new Campaign([
            'id' => $campaign['id'],
            'list_id' => $campaign['recipients']['list_id'],
            'folder_id' => $campaign['settings']['folder_id'],
            'subject_line' => $campaign['settings']['subject_line'],
            'title' => $campaign['settings']['title'],
            'from_name' => $campaign['settings']['from_name'],
            'reply_to' => $campaign['settings']['reply_to'],
        ]);

        return $campaign;
    }
}
