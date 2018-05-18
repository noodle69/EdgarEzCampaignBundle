<?php

namespace Edgar\EzCampaign\Values\Core;

class Folder extends \Edgar\EzCampaign\Values\API\Folder
{
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $folderId): self
    {
        $this->id = $folderId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
