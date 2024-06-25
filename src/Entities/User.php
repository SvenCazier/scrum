<?php
//src/Entities/Client.php

declare(strict_types=1);

namespace Webshop\Entities;

use Webshop\Entities\Address;


class User
{
    private int $gebruikersAccountId;
    private string $emailadres;
    private ?int $klantId;
    private string $voornaam;
    private string $familienaam;
    private Address $facturatieAdres;
    private Address $leveringsAdres;
    private ?string $functie;
    private ?string $bedrijfsNaam;
    private ?string $btwNummer;

    public function __construct(
        int $gebruikersAccountId,
        string $emailadres,
        ?int $klantId,
        string $voornaam,
        string $familienaam,
        Address $facturatieAdres,
        Address $leveringsAdres,
        ?string $functie = NULL,
        ?string $bedrijfsNaam = NULL,
        ?string $btwNummer = NULL
    ) {
        $this->gebruikersAccountId = $gebruikersAccountId;
        $this->emailadres = $emailadres;
        $this->klantId = $klantId;
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->facturatieAdres = $facturatieAdres;
        $this->leveringsAdres = $leveringsAdres;
        $this->functie = $functie;
        $this->bedrijfsNaam = $bedrijfsNaam;
        $this->btwNummer = $btwNummer;
    }

    // Getters
    public function getId(): int
    {
        return $this->gebruikersAccountId;
    }

    public function getEmailadres(): string
    {
        return $this->emailadres;
    }

    public function getKlantId(): int
    {
        return $this->klantId;
    }

    public function getVoornaam(): string
    {
        return $this->voornaam;
    }

    public function getFamilienaam(): string
    {
        return $this->familienaam;
    }

    public function getFacturatieAdres(): Address
    {
        return $this->facturatieAdres;
    }

    public function getLeveringsAdres(): Address
    {
        return $this->leveringsAdres;
    }

    public function getFunctie(): ?string
    {
        return $this->functie;
    }

    public function getBedrijfsNaam(): ?string
    {
        return $this->bedrijfsNaam;
    }

    public function getBtwNummer(): ?string
    {
        return $this->btwNummer;
    }

    // Setters
    public function setId(int $gebruikersAccountId): void
    {
        $this->gebruikersAccountId = $gebruikersAccountId;
    }

    public function setEmailadres(string $emailadres): void
    {
        $this->emailadres = $emailadres;
    }

    public function setKlantId(int $klantId): void
    {
        $this->klantId = $klantId;
    }

    public function setVoornaam(string $voornaam): void
    {
        $this->voornaam = $voornaam;
    }

    public function setFamilienaam(string $familienaam): void
    {
        $this->familienaam = $familienaam;
    }

    public function setFacturatieAdres(Address $facturatieAdres): void
    {
        $this->facturatieAdres = $facturatieAdres;
    }

    public function setLeveringsAdres(Address $leveringsAdres): void
    {
        $this->leveringsAdres = $leveringsAdres;
    }

    public function setFunctie(?string $functie): void
    {
        $this->functie = $functie;
    }

    public function setBedrijfsNaam(?string $bedrijfsNaam): void
    {
        $this->bedrijfsNaam = $bedrijfsNaam;
    }

    public function setBtwNummer(?string $btwNummer): void
    {
        $this->btwNummer = $btwNummer;
    }
}
