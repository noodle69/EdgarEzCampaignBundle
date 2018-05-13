<?php

namespace Edgar\EzCampaign\FieldType\Campaign;

use Edgar\EzCampaignBundle\Service\CampaignsService;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\ValidationError;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use Welp\MailchimpBundle\Exception\MailchimpException;

class Type extends FieldType
{
    /**
     * @var array
     */
    protected $campaigns;

    public function __construct(CampaignsService $campaignsService)
    {
        try {
            $campaigns = $campaignsService->get(0, 0);
            $this->campaigns = $campaigns['campaigns'];
        } catch (MailchimpException $e) {
            $this->campaigns = [];
        }
    }

    /**
     * Returns the field type identifier for this field type.
     *
     * @return string
     */
    public function getFieldTypeIdentifier()
    {
        return 'edgarcampaign';
    }

    /**
     * Returns the name of the given field value.
     *
     * It will be used to generate content name and url alias if current field is designated
     * to be used in the content name/urlAlias pattern.
     *
     * @param Value $value
     *
     * @return string
     */
    public function getName(SPIValue $value)
    {
        return (string)$value->campaigns['settings']['title'];
    }

    /**
     * Returns the fallback default value of field type when no such default
     * value is provided in the field definition in content types.
     *
     * @return Value
     */
    public function getEmptyValue()
    {
        return new Value();
    }

    /**
     * Inspects given $inputValue and potentially converts it into a dedicated value object.
     *
     * @param array|Value $inputValue
     *
     * @return Value The potentially converted and structurally plausible value.
     */
    protected function createValueFromInput($inputValue)
    {
        if (is_array($inputValue)) {
            $inputValue = $this->fromHash($inputValue);
        }

        return $inputValue;
    }

    /**
     * Throws an exception if value structure is not of expected format.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If the value does not match the expected structure.
     *
     * @param Value $value
     */
    protected function checkValueStructure(BaseValue $value)
    {
        if (!is_array($value->campaigns)) {
            throw new InvalidArgumentType(
                '$value->campaigns',
                'array',
                $value->campaigns
            );
        }
    }

    /**
     * Validates field value against 'isMultiple' setting.
     *
     * Does not use validators.
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     *
     * @param \eZ\Publish\API\Repository\Values\ContentType\FieldDefinition $fieldDefinition The field definition of the field
     * @param Value $fieldValue The field value for which an action is performed
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validate(FieldDefinition $fieldDefinition, SPIValue $fieldValue)
    {
        $validationErrors = [];

        if ($this->isEmptyValue($fieldValue)) {
            return $validationErrors;
        }

        foreach ($fieldValue->campaigns as $id => $campaign) {
            $exists = false;
            foreach ($this->campaigns as $camp) {
                if ($id == $camp['id']) {
                    $exists = true;
                }
            }

            if (!$exists) {
                $validationErrors[] = new ValidationError(
                    "Campaign with id code '%id%' is not defined in FieldType settings.",
                    null,
                    [
                        '%id%' => $id,
                    ],
                    'edgarezcampaign'
                );
            }
        }

        return $validationErrors;
    }

    /**
     * Returns information for FieldValue->$sortKey relevant to the field type.
     *
     * @param Value $value
     *
     * @return array
     */
    protected function getSortInfo(BaseValue $value)
    {
        $campaigns = [];
        foreach ($value->campaigns as $campaign) {
            $campaigns[] = $this->transformationProcessor->transformByGroup($campaign['settings']['title'], 'lowercase');
        }

        sort($campaigns);

        return implode(',', $campaigns);
    }

    public function fromHash($hash)
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        $campaigns = [];
        foreach ($hash as $campaign) {
            foreach ($this->campaigns as $camp) {
                switch ($campaign) {
                    case $camp['settings']['title']:
                    case $camp['id']:
                        $campaigns[$camp['id']] = $camp;
                        continue 3;
                }
            }

            throw new InvalidValue($campaign);
        }

        return new Value($campaigns);
    }

    /**
     * Converts a $Value to a hash.
     *
     * @param Value $value
     *
     * @return mixed
     */
    public function toHash(SPIValue $value)
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return array_keys($value->campaigns);
    }

    /**
     * Returns whether the field type is searchable.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * Validates the fieldSettings of a FieldDefinitionCreateStruct or FieldDefinitionUpdateStruct.
     *
     * @param mixed $fieldSettings
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validateFieldSettings($fieldSettings)
    {
        $validationErrors = [];

        foreach ($fieldSettings as $name => $value) {
            if (!isset($this->settingsSchema[$name])) {
                $validationErrors[] = new ValidationError(
                    "Setting '%setting%' is unknown",
                    null,
                    [
                        '%setting%' => $name,
                    ],
                    "[$name]"
                );
                continue;
            }
        }

        return $validationErrors;
    }
}
