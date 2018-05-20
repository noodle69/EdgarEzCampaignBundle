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
     */
    public function countCampaigns(string $folderId): int
    {
        $return = $this->mailChimp->get('/campaigns?folder_id=' . $folderId, []);

        if ($this->mailChimp->success()) {
            return $return['total_items'];
        }

        return 0;
    }
}
