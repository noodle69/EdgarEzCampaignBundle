<?php

namespace Edgar\EzCampaignBundle\Service;

use DrewM\MailChimp\MailChimp;
use Welp\MailchimpBundle\Exception\MailchimpException;

abstract class BaseService
{
    /** @var MailChimp $mailChimp MailChimp service */
    protected $mailChimp;

    /**
     * BaseService constructor.
     *
     * @param MailChimp $mailChimp
     */
    public function __construct(MailChimp $mailChimp)
    {
        $this->mailChimp = $mailChimp;
    }

    /**
     * @param array $errorResponse
     *
     * @throws MailchimpException
     */
    protected function throwMailchimpError(array $errorResponse)
    {
        $errorArray = json_decode($errorResponse['body'], true);
        if (is_array($errorArray) && array_key_exists('errors', $errorArray)) {
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
