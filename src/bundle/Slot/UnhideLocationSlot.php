<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class UnhideLocationSlot extends BaseSlot
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\UnhideLocationSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
