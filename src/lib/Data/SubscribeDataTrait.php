<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\Core\Subscribe;

trait SubscribeDataTrait
{
    /**
     * @var Subscribe
     */
    protected $subscribe;

    /**
     * @param Subscribe $subscribe
     */
    public function setSubscribe(Subscribe $subscribe)
    {
        $this->subscribe = $subscribe;
    }
}
