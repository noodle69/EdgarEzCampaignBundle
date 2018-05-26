<?php

namespace Edgar\EzCampaignBundle\Service;

class FoldersService extends BaseService
{
    /**
     * @param int $offset
     * @param int $count
     *
     * @return array
     */
    public function get(int $offset = 0, int $count = 10): array
    {
        $campaignFolders = $this->mailChimp->get('/campaign-folders', [
            'offset' => $offset,
            'count' => $count,
        ]);

        if (!$this->mailChimp->success()) {
            $campaignFolders = [
                'folders' => [],
                'total_items' => 0,
            ];
        }

        return $campaignFolders;
    }

    /**
     * @param string $folderId
     *
     * @return int
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function countCampaigns(string $folderId): int
    {
        $args = [
            'offset' => 0,
            'count' => 1,
            'folder_id' => $folderId,
        ];

        $campaigns = $this->mailChimp->get('/campaigns', $args);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaigns['total_items'];
    }
}
