<?php

namespace Edgar\EzCampaignBundle\Service;

class ReportsService extends BaseService
{
    public function getAbuses(string $campaignId)
    {
        $abuses = $this->mailChimp->get('/reports/' . $campaignId . '/abuse-reports', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $abuses;
    }

    public function getAbuse(string $campaignId, string $abuseId)
    {
        $abuse = $this->mailChimp->get('/reports/' . $campaignId . '/abuse-reports/' . $abuseId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $abuse;
    }

    public function getAdvice(string $campaignId)
    {
        $advice = $this->mailChimp->get('/reports/' . $campaignId . '/advice', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $advice;
    }

    public function getOpen(string $campaignId)
    {
        $open = $this->mailChimp->get('/reports/' . $campaignId . '/open-details', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $open;
    }

    public function getClick(string $campaignId)
    {
        $links = $this->mailChimp->get('/reports/' . $campaignId . '/click-details', []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $links;
    }

    public function getuClickLink(string $campaignId, string $linkId)
    {
        $link = $this->mailChimp->get('/reports/' . $campaignId . '/click-details/' . $linkId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $link;
    }
}
