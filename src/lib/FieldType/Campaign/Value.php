<?php

namespace Edgar\EzCampaign\FieldType\Campaign;

use eZ\Publish\Core\FieldType\Value as BaseValue;

class Value extends BaseValue
{
    /**
     * @var array
     */
    public $campaigns = [];

    /**
     * Value constructor.
     *
     * @param array $campaigns
     */
    public function __construct(array $campaigns = [])
    {
        $this->campaigns = $campaigns;
    }

    /**
     * @see \eZ\Publish\Core\FieldType\Value
     */
    public function __toString()
    {
        return implode(
            ', ',
            array_map(
                function ($campaign) {
                    return $campaign['settings']['title'];
                },
                $this->campaigns
            )
        );
    }
}
