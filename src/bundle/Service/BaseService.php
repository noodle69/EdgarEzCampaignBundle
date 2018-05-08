<?php

namespace Edgar\EzCampaignBundle\Service;

use DrewM\MailChimp\MailChimp;
use Welp\MailchimpBundle\Exception\MailchimpException;

Abstract class BaseService
{
    /** @var MailChimp $mailChimp MailChimp service */
    protected $mailChimp;

    /**
     * BaseService constructor.
     *
     * @param MailChimp $mailChimp MailChimp service
     */
    public function __construct(MailChimp $mailChimp)
    {
        $this->mailChimp = $mailChimp;
    }

    /**
     * Thorw a MailChimp Exception
     *
     * @param array $errorResponse array of error Response
     * @throws MailchimpException MailChimpException
     */
    protected function throwMailchimpError(array $errorResponse)
    {
        $errorArray = json_decode($errorResponse['body'], true);
        if (array_key_exists('errors', $errorArray)) {
            throw new MailchimpException(
                $errorArray['status'],
                $errorArray['detail'],
                $errorArray['type'],
                $errorArray['title'],
                $errorArray['errors'],
                $errorArray['instance']
            );
        } else {
            throw new MailchimpException(
                $errorArray['status'],
                $errorArray['detail'],
                $errorArray['type'],
                $errorArray['title'],
                null,
                $errorArray['instance']
            );
        }
    }
}
