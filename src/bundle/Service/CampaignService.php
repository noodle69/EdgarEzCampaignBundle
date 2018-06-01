<?php

namespace Edgar\EzCampaignBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use DrewM\MailChimp\MailChimp;
use Edgar\EzCampaign\Repository\EdgarEzCampaignRepository;
use Edgar\EzCampaign\Values\Core\Campaign;
use Edgar\EzCampaign\Values\Core\Schedule;
use Edgar\EzCampaignBundle\Entity\EdgarEzCampaign;

class CampaignService extends BaseService
{
    /** @var ListService $listService Campaign List service */
    protected $listService;

    /** @var EdgarEzCampaignRepository  */
    protected $campaignRepository;

    /**
     * CampaignService constructor.
     *
     * @param MailChimp $mailChimp
     * @param ListService $listService
     * @param Registry $doctrineRegistry
     */
    public function __construct(
        MailChimp $mailChimp,
        ListService $listService,
        Registry $doctrineRegistry
    ) {
        parent::__construct($mailChimp);
        $this->listService = $listService;

        $entityManager = $doctrineRegistry->getManager();
        $this->campaignRepository = $entityManager->getRepository(EdgarEzCampaign::class);
    }

    /**
     * @param string $campaignID
     * @param array $fields
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function get(string $campaignID, array $fields = []): array
    {
        $campaign = $this->mailChimp->get('/campaigns/' . $campaignID, $fields);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaign;
    }

    /**
     * @param Campaign $campaign
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function post(Campaign $campaign): array
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
     * @param string $campaignId
     * @param Campaign $campaign
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function patch(string $campaignId, Campaign $campaign): array
    {
        $return = $this->mailChimp->patch('/campaigns/' . $campaignId, [
            'type' => 'regular',
            'recipients' => [
                'list_id' => $campaign->getListId(),
            ],
            'settings' => [
                'subject_line' => $campaign->getSubjectLine(),
                'title' => $campaign->getTitle(),
                'fromName' => $campaign->getFromName(),
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
     * @param string $campaignID
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function delete(string $campaignID): bool
    {
        $return = $this->mailChimp->delete('/campaigns/' . $campaignID, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        $this->campaignRepository->remove($campaignID);

        return $return;
    }

    /**
     * @param string $campaignID
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function send(string $campaignID): bool
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/send', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $campaignID
     * @param Schedule $scheduleTime
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function schedule(string $campaignID, Schedule $scheduleTime): bool
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/schedule', [
            'schedule_time' => $scheduleTime->getScheduleTime()->value->format('Y-m-d\TH:i:s') . '+00:00',
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $campaignID
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function cancelSchedule(string $campaignID): bool
    {
        $return = $this->mailChimp->post('/campaigns/' . $campaignID . '/actions/unschedule', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $campaignID
     * @param string $email
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function subscribe(string $campaignID, string $email)
    {
        $campaign = $this->get($campaignID);

        if ($campaign) {
            $this->listService->subscribe($campaign['recipients']['list_id'], $email);
        }
    }

    /**
     * @param string $campaignId
     * @param string $url
     * @param string $locationId
     * @param string $site
     *
     * @return array
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function putContent(string $campaignId, string $url, string $locationId, string $site): array
    {
        $this->campaignRepository->save($campaignId, $locationId, $site);

        $return = $this->mailChimp->put('/campaigns/' . $campaignId . '/content', [
            'url' => $url,
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * @param string $campaignID
     * @param array $fields
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getContent(string $campaignID, array $fields = []): array
    {
        $campaignContent = $this->mailChimp->get('/campaigns/' . $campaignID . '/content', $fields);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaignContent;
    }
}
