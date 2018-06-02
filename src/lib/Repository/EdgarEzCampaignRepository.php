<?php

namespace Edgar\EzCampaign\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Edgar\EzCampaignBundle\Entity\EdgarEzCampaign;
use eZ\Publish\API\Repository\Values\Content\Location;

class EdgarEzCampaignRepository extends EntityRepository
{
    /**
     * @param Location[] $locations
     *
     * @return EdgarEzCampaign[]
     */
    public function getCampaigns(array $locations): array
    {
        $locationIds = [];
        foreach ($locations as $location) {
            $locationIds[] = $location->id;
        }

        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder->select('c')
            ->from(EdgarEzCampaign::class, 'c')
            ->where($queryBuilder->expr()->in('c.locationId', ':locationIds'))
            ->setParameter('locationIds', $locationIds)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param string $campaignId
     * @param int $locationId
     * @param string $site
     *
     * @throws ORMException
     */
    public function save(string $campaignId, int $locationId, string $site)
    {
        /** @var EdgarEzCampaign $campaign */
        $campaign = $this->findOneBy(['campaignId' => $campaignId]);
        if (!$campaign) {
            $campaign = new EdgarEzCampaign();
            $campaign->setCampaignId($campaignId);
        }

        $campaign->setLocationId($locationId);
        $campaign->setSite($site);

        try {
            $this->getEntityManager()->persist($campaign);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * @param string $campaignId
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(string $campaignId)
    {
        /** @var EdgarEzCampaign $campaign */
        $campaign = $this->findOneBy(['campaignId' => $campaignId]);

        if ($campaign) {
            $this->getEntityManager()->remove($campaign);
            $this->getEntityManager()->flush();
        }
    }
}
