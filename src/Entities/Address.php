<?php
//src/Entities/Address.php

declare(strict_types=1);

namespace Webshop\Entities;

use Webshop\Entities\Place;

class Address
{
    private int $adresId;
    private string $straat;
    private string $huisNummer;
    private ?string $bus;
    private Place $place;
    private bool $actief;

    public function __construct(int $adresId, string $straat, string $huisNummer, Place $place, bool $actief, ?string $bus = null)
    {
        $this->adresId = $adresId;
        $this->straat = $straat;
        $this->huisNummer = $huisNummer;
        $this->bus = $bus;
        $this->place = $place;
        $this->actief = $actief;
    }

    // Getters
    public function getId(): int
    {
        return $this->adresId;
    }

    public function getStraat(): string
    {
        return $this->straat;
    }

    public function getHuisNummer(): string
    {
        return $this->huisNummer;
    }

    public function getBus(): ?string
    {
        return $this->bus;
    }

    public function getPlaats(): Place
    {
        return $this->place;
    }

    public function getActief(): bool
    {
        return $this->actief;
    }

    // Setters
    public function setId(int $adresId)
    {
        $this->adresId = $adresId;
    }

    public function setStraat(string $straat)
    {
        $this->straat = $straat;
    }

    public function setHuisNummer(string $huisNummer)
    {
        $this->huisNummer = $huisNummer;
    }

    public function setBus(string $bus)
    {
        $this->bus = $bus;
    }

    public function setPlaats(Place $place): void
    {
        $place =  $this->place;
    }

    public function setActief(bool $actief)
    {
        $this->actief = $actief;
    }
}
