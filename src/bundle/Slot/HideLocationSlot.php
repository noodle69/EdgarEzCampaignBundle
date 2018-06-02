<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class HideLocationSlot extends BaseSlot
{
    /**
     * @param Signal $signal
     */
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\LocationService\HideLocationSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
