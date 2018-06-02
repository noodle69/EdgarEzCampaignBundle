<?php

namespace Edgar\EzCampaignBundle\Controller;

use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;

abstract class BaseController extends Controller
{
    /** @var PermissionResolver */
    protected $permissionResolver;

    /**
     * BaseController constructor.
     *
     * @param PermissionResolver $permissionResolver
     */
    public function __construct(PermissionResolver $permissionResolver)
    {
        $this->permissionResolver = $permissionResolver;
    }

    public function performAccessCheck()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        try {
            if (!$this->permissionResolver->hasAccess('campaign', 'campaign')) {
                $this->throwException();
            }
        } catch (InvalidArgumentException $e) {
            $this->throwException();
        }
    }

    private function throwException()
    {
        $exception = $this->createAccessDeniedException();
        $exception->setAttributes('campaign');
        $exception->setSubject('campaign');

        throw $exception;
    }
}
