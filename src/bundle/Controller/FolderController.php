<?php

namespace Edgar\EzCampaignBundle\Controller;

use Edgar\EzCampaign\Data\FoldersDeleteData;
use Edgar\EzCampaign\Form\Factory\FormFactory;
use Edgar\EzCampaign\Form\SubmitHandler;
use Edgar\EzCampaign\Values\Core\Folder;
use Edgar\EzCampaignBundle\Service\FolderService;
use Edgar\EzCampaignBundle\Service\FoldersService;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Welp\MailchimpBundle\Exception\MailchimpException;

class FolderController extends Controller
{
    /** @var NotificationHandlerInterface */
    private $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FolderService */
    protected $folderService;

    /** @var FoldersService */
    protected $foldersService;

    /** @var SubmitHandler $submitHandler */
    private $submitHandler;

    /** @var FormFactory */
    private $formFactory;

    /**
     * FolderController constructor.
     *
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param FolderService $folderService
     * @param FoldersService $foldersService
     * @param SubmitHandler $submitHandler
     * @param FormFactory $formFactory
     */
    public function __construct(
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        FolderService $folderService,
        FoldersService $foldersService,
        SubmitHandler $submitHandler,
        FormFactory $formFactory
    ) {
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->folderService = $folderService;
        $this->foldersService = $foldersService;
        $this->submitHandler = $submitHandler;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->createFolder();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (Folder $data) {
                $folder = $this->folderService->post($data);

                $this->notificationHandler->success(
                    $this->translator->trans(
                    /* @Desc("Campaign Folder '%name%' created.") */
                        'folder.create.success',
                        ['%name%' => $folder['name']],
                        'edgarezcampaign'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return new RedirectResponse($this->generateUrl('edgar.campaign.campaigns', []));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request): Response
    {
        $form = $this->formFactory->deleteFolders(
            new FoldersDeleteData()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (FoldersDeleteData $data) {
                foreach ($data->getFolders() as $folderId => $selected) {
                    try {
                        $folder = $this->folderService->get($folderId);
                        if (false !== $folder) {
                            if ($this->foldersService->countCampaigns($folderId) > 0) {
                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /* @Desc("Campaign Folder '%name%' is associated with Campaigns, so it can't be removed.") */
                                        'folder.delete.campaigns_exists',
                                        ['%name%' => $folder['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            } else {
                                $this->folderService->delete($folderId);

                                $this->notificationHandler->success(
                                    $this->translator->trans(
                                    /* @Desc("Campaign Folder '%name%' removed.") */
                                        'folder.delete.success',
                                        ['%name%' => $folder['name']],
                                        'edgarezcampaign'
                                    )
                                );
                            }
                        } else {
                            $this->notificationHandler->warning(
                                $this->translator->trans(
                                /* @Desc("Campaign Folder '%id%' doesn't exists.") */
                                    'folders.delete.warning',
                                    ['%id%' => $folderId],
                                    'edgarezcampaign'
                                )
                            );
                        }
                    } catch (MailchimpException $e) {
                        $this->notificationHandler->error(
                            $this->translator->trans(
                            /* @Desc("Error when deleting Campaign Folder.") */
                                'folders.delete.error',
                                [],
                                'edgarezcampaign'
                            )
                        );
                    }
                }

                return $this->redirect($this->generateUrl('edgar.campaign.campaigns'));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->redirect($this->generateUrl('edgar.campaign.campaigns'));
    }
}
