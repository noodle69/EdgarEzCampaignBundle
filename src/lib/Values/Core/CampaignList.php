<?php

namespace Edgar\EzCampaign\Values\Core;

class CampaignList extends \Edgar\EzCampaign\Values\API\CampaignList
{
    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
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

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPermissionReminder(): ?string
    {
        return $this->permission_reminder;
    }

    public function setPermissionReminder(string $permissionReminder): self
    {
        $this->permission_reminder = $permissionReminder;
        return $this;
    }

    public function getUseArchiveBar(): ?bool
    {
        return $this->use_archive_bar;
    }

    public function setUseArchiveBar(bool $useArchiveBar = false): self
    {
        $this->use_archive_bar = $useArchiveBar;
        return $this;
    }

    public function getFromName(): ?string
    {
        return $this->from_name;
    }

    public function setFromName(string $fromName): self
    {
        $this->from_name = $fromName;
        return $this;
    }

    public function getFromEmail(): ?string
    {
        return $this->from_email;
    }

    public function setFromEmail(string $fromEmail): self
    {
        $this->from_email = $fromEmail;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getNotifyOnSubscribe(): ?string
    {
        return $this->notify_on_subscribe;
    }

    public function setNotifyOnSubscribe(?string $notifyOnSubscribe): self
    {
        $this->notify_on_subscribe = $notifyOnSubscribe;
        return $this;
    }

    public function getNotifyOnUnsubscribe(): ?string
    {
        return $this->notify_on_unsubscribe;
    }

    public function setNotifyOnUnsubscribe(?string $notifyOnUnsubscribe): self
    {
        $this->notify_on_unsubscribe = $notifyOnUnsubscribe;
        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }
}
