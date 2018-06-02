<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class DeleteLocationSlot extends BaseSlot
{
    /**
     * @param Signal $signal
     */
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\DeleteLocationSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
