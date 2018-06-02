<?php

namespace Edgar\EzCampaignBundle\Service;

class ReportsService extends BaseService
{
    /**
     * @param string $campaignId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getAbuses(string $campaignId)
    {
        $abuses = $this->mailChimp->get('/reports/' . $campaignId . '/abuse-reports', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $abuses;
    }

    /**
     * @param string $campaignId
     * @param string $abuseId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getAbuse(string $campaignId, string $abuseId)
    {
        $abuse = $this->mailChimp->get('/reports/' . $campaignId . '/abuse-reports/' . $abuseId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $abuse;
    }

    /**
     * @param string $campaignId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getAdvice(string $campaignId)
    {
        $advice = $this->mailChimp->get('/reports/' . $campaignId . '/advice', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $advice;
    }

    /**
     * @param string $campaignId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getOpen(string $campaignId)
    {
        $open = $this->mailChimp->get('/reports/' . $campaignId . '/open-details', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $open;
    }

    /**
     * @param string $campaignId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getClick(string $campaignId)
    {
        $links = $this->mailChimp->get('/reports/' . $campaignId . '/click-details', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $links;
    }

    /**
     * @param string $campaignId
     * @param string $linkId
     * @return array|false
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function getuClickLink(string $campaignId, string $linkId)
    {
        $link = $this->mailChimp->get('/reports/' . $campaignId . '/click-details/' . $linkId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $link;
    }
}
