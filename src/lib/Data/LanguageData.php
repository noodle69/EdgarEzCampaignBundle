<?php

namespace Edgar\EzCampaign\Data;

class LanguageData
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $identifier
     *
     * @return LanguageData
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return LanguageData
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getSite();
    }
}
