<?php

namespace Edgar\EzCampaignBundle\Service;

use Edgar\EzCampaign\Values\Core\Folder;

class FolderService extends BaseService
{
    /**
     * @param string $campaignFolderID
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function get(string $campaignFolderID): array
    {
        $campaignFolder = $this->mailChimp->get('/campaign-folders/' . $campaignFolderID, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $campaignFolder;
    }

    /**
     * @param Folder $folder
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function post(Folder $folder): array
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
     * @param string $campaignFolderID
     * @param string $name
     *
     * @return array
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function patch(string $campaignFolderID, string $name): array
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
     * @param string $folderId
     *
     * @return bool
     *
     * @throws \Welp\MailchimpBundle\Exception\MailchimpException
     */
    public function delete(string $folderId): bool
    {
        $return = $this->mailChimp->delete('/campaign-folders/' . $folderId, []);

        if (!$this->mailChimp->success()) {
            $this->throwMailchimpError($this->mailChimp->getLastResponse());
        }

        return $return;
    }
}
