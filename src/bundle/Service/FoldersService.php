<?php

namespace Edgar\EzCampaignBundle\Service;

use Welp\MailchimpBundle\Exception\MailchimpException;

class FoldersService extends BaseService
{
    /**
     * Retrive a list of Campaign Folder
     *
     * @param int $offset search offset
     * @param int $count maximum folder count
     * @return array List of Campaign Folders
     * @throws MailchimpException MailChimpException
     */
    public function get($offset = 0, $count = 10)
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

    public function countCampaigns(string $folderId): int
    {
        $return = $this->mailChimp->get('/campaigns?folder_id=' . $folderId, []);

        if ($this->mailChimp->success()) {
            return $return['total_items'];
        }

        return 0;
    }
}
