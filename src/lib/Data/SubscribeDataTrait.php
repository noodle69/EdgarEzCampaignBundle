<?php

namespace Edgar\EzCampaign\Data;

use Edgar\EzCampaign\Values\Core\Subscribe;

trait SubscribeDataTrait
{
    /**
     * @var Subscribe $subscribe
     */
    protected $subscribe;

    public function setSubscribe(Subscribe $subscribe)
    {
        $this->subscribe = $subscribe;
    }
}
