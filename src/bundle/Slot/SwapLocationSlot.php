<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class SwapLocationSlot extends BaseSlot
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\SwapLocationSignal) {
            return;
        }

        $this->updateCampaignContent($signal->content1Id);
    }
}
