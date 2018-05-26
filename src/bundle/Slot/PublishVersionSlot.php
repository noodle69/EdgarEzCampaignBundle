<?php

namespace Edgar\EzCampaignBundle\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class PublishVersionSlot extends BaseSlot
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\PublishVersionSignal) {
            return;
        }

        $this->updateCampaignContent($signal->contentId);
    }
}
