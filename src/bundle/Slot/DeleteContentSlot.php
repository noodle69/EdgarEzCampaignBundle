<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class DeleteContentSlot extends BaseSlot
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\DeleteContentSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
