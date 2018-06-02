<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class UpdateLocationSlot extends BaseSlot
{
    /**
     * @param Signal $signal
     */
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\UpdateLocationSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
