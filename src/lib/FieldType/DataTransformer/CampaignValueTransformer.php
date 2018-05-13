<?php

namespace Edgar\EzCampaign\FieldType\DataTransformer;

use Edgar\EzCampaign\FieldType\Campaign\Value;
use Symfony\Component\Form\DataTransformerInterface;

class CampaignValueTransformer implements DataTransformerInterface
{
    protected $campaigns;

    public function __construct(array $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }

        return current($value->campaigns)['id'];
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        $campaign = current($this->campaigns);
        foreach ($this->campaigns as $camp) {
            if ($camp['id'] == $value) {
                $campaign = $camp;
                break;
            }
        }

        return new Value([$value => $campaign]);
    }
}
