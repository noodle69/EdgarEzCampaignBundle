<?php

namespace Edgar\EzCampaignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzCampaign.
 *
 * @ORM\Entity(repositoryClass="Edgar\EzCampaign\Repository\EdgarEzCampaignRepository")
 * @ORM\Table(name="edgar_ez_campaign")
 */
class EdgarEzCampaign
{
    /**
     * @var string
     *
     * @ORM\Column(name="campaign_id", type="string", nullable=false)
     * @ORM\Id
     */
    private $campaignId;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", nullable=false)
     */
    private $site;

    /**
     * @var int
     *
     * @ORM\Column(name="location_id", type="integer", nullable=false)
     */
    private $locationId;

    public function setCampaignId(string $campaignId): self
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function setLocationId(int $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getCampaignId(): string
    {
        return $this->campaignId;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }
}
