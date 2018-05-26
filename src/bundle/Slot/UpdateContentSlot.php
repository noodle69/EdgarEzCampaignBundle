<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class UpdateContentSlot extends BaseSlot
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\UpdateContentSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
