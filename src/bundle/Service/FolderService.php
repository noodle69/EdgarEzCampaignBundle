<?php

namespace Edgar\EzCampaignBundle\Service;

use Edgar\EzCampaign\Values\Core\Folder;
use Welp\MailchimpBundle\Exception\MailchimpException;

/**
 * Class FolderService
 *
 * @package Edgar\EzCampaignBundle\Service
 */
class FolderService extends BaseService
{
    /**
     * Retrieve Campaign Folder by Folder ID
     *
     * @param int $campaignFolderID Campaign Folder ID
     * @return array Campaign Folder informations
     * @throws MailchimpException MailChimpException
     */
    public function get($campaignFolderID)
    {
        $campaignFolder = $this->mailChimp->get('/campaign-folders/' . $campaignFolderID, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaignFolder;
    }

    /**
     * Create a new Campaign Folder
     *
     * @param string $name Campaign Folder name
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function post(Folder $folder)
    {
        $return = $this->mailChimp->post('/campaign-folders', [
            'name' => $folder->getName(),
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Update Campaign Folder
     *
     * @param int $campaignFolderID
     * @param string $name
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function patch($campaignFolderID, $name)
    {
        $return = $this->mailChimp->patch('/campaign-folders/' . $campaignFolderID, [
            'name' => $name,
        ]);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    /**
     * Delete Campaign Folder
     *
     * @param string $folderId Campaign Folder ID
     * @return array MailChimp service informations
     * @throws MailchimpException MailChimpException
     */
    public function delete(string $folderId)
    {
        $return = $this->mailChimp->delete('/campaign-folders/' . $folderId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }

    public function map(array $folder): Folder
    {
        $folder = new Folder([
            'id' => $folder['id'],
            'name' => $folder['name'],
        ]);

        return $folder;
    }
}
