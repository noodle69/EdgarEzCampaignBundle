<?php

namespace Edgar\EzCampaign\FieldType\DataTransformer;

use Edgar\EzCampaign\FieldType\Campaign\Value;
use Symfony\Component\Form\DataTransformerInterface;

class CampaignValueTransformer implements DataTransformerInterface
{
    /**
     * @var array
     */
    protected $campaigns;

    /**
     * CampaignValueTransformer constructor.
     * @param array $campaigns
     */
    public function __construct(array $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    /**
     * @param mixed $value
     * @return mixed|null
     */
    public function transform($value)
    {
        if (!$value instanceof Value) {
            return null;
        }

        return current($value->campaigns)['id'];
    }

    /**
     * @param mixed $value
     * @return Value|mixed|null
     */
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
