<?php
//src/Entities/Order.php

declare(strict_types=1);

namespace Webshop\Entities;

use DateTime;

class Order
{
    private int $bestelId;
    private DateTime $besteldatum;
    private int $klantId;
    private bool $betaald;
    private string $betalingscode;
    private PaymentMethod $betaalwijze;
    private bool $annulatie;
    private DateTime $annulatiedatum;
    private string $terugbetalingscode;
    private OrderStatus $bestellingsStatus;
    private bool $actiecodeGebruikt;
    private string $bedrijfsnaam;
    private string $btwNummer;
    private string $voornaam;
    private string $familienaam;
    private Address $facturatieAdres;
    private Address $leveringsAdres;
    private array $bestelLijnen;

    public function __construct(
        int $bestelId,
        string $besteldatum,
        int $klantId,
        PaymentMethod $betaalwijze,
        bool $betaald,
        string $betalingscode,
        bool $annulatie,
        string $annulatiedatum,
        string $terugbetalingscode,
        OrderStatus $bestellingsStatus,
        bool $actiecodeGebruikt,
        string $bedrijfsnaam,
        string $btwNummer,
        string $voornaam,
        string $familienaam,
        Address $facturatieAdres,
        Address $leveringsAdres,
        array $bestelLijnen = []
    ) {
        $this->bestelId = $bestelId;
        $this->besteldatum = new DateTime($besteldatum);
        $this->klantId = $klantId;
        $this->betaald = $betaald;
        $this->betalingscode = $betalingscode;
        $this->betaalwijze = $betaalwijze;
        $this->annulatie = $annulatie;
        $this->annulatiedatum = new DateTime($annulatiedatum);
        $this->terugbetalingscode = $terugbetalingscode;
        $this->bestellingsStatus = $bestellingsStatus;
        $this->actiecodeGebruikt = $actiecodeGebruikt;
        $this->bedrijfsnaam = $bedrijfsnaam;
        $this->btwNummer = $btwNummer;
        $this->voornaam = $voornaam;
        $this->familienaam = $familienaam;
        $this->facturatieAdres = $facturatieAdres;
        $this->leveringsAdres = $leveringsAdres;
        $this->bestelLijnen = $bestelLijnen;
    }

    public function getId(): int
    {
        return $this->bestelId;
    }

    public function getBesteldatum(): DateTime
    {
        return $this->besteldatum;
    }

    public function getKlantId(): int
    {
        return $this->klantId;
    }

    public function getBetaald(): bool
    {
        return $this->betaald;
    }

    public function getBetalingscode(): string
    {
        return $this->betalingscode;
    }

    public function getBetaalwijze(): PaymentMethod
    {
        return $this->betaalwijze;
    }

    public function getAnnulatie(): bool
    {
        return $this->annulatie;
    }

    public function getAnnulatiedatum(): DateTime
    {
        return $this->annulatiedatum;
    }

    public function getTerugbetalingscode(): string
    {
        return $this->terugbetalingscode;
    }

    public function getBestellingsStatus(): OrderStatus
    {
        return $this->bestellingsStatus;
    }

    public function getActiecodeGebruikt(): bool
    {
        return $this->actiecodeGebruikt;
    }

    public function getBedrijfsnaam(): string
    {
        return $this->bedrijfsnaam;
    }

    public function getBtwNummer(): string
    {
        return $this->btwNummer;
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

    public function getBestelLijnen(): array
    {
        return $this->bestelLijnen;
    }

    public function setBestelId(int $bestelId)
    {
        $this->bestelId = $bestelId;
    }

    public function setBesteldatum(DateTime $besteldatum)
    {
        $this->besteldatum = $besteldatum;
    }

    public function setKlantId(int $klantId)
    {
        $this->klantId = $klantId;
    }

    public function setBetaald(bool $betaald)
    {
        $this->betaald = $betaald;
    }

    public function setBetalingscode(string $betalingscode)
    {
        $this->betalingscode = $betalingscode;
    }

    public function setBetaalwijze(PaymentMethod $betaalwijze)
    {
        $this->betaalwijze = $betaalwijze;
    }

    public function setAnnulatie(bool $annulatie)
    {
        $this->annulatie = $annulatie;
    }

    public function setAnnulatiedatum(DateTime $annulatiedatum)
    {
        $this->annulatiedatum = $annulatiedatum;
    }

    public function setTerugbetalingscode(string $terugbetalingscode)
    {
        $this->terugbetalingscode = $terugbetalingscode;
    }

    public function setBestellingsStatus(int $bestellingsStatus)
    {
        $this->bestellingsStatus = $bestellingsStatus;
    }

    public function setActiecodeGebruikt(bool $actiecodeGebruikt)
    {
        $this->actiecodeGebruikt = $actiecodeGebruikt;
    }

    public function setBedrijfsnaam(string $bedrijfsnaam)
    {
        $this->bedrijfsnaam = $bedrijfsnaam;
    }

    public function setBtwNummer(string $btwNummer)
    {
        $this->btwNummer = $btwNummer;
    }

    public function setVoornaam(string $voornaam)
    {
        $this->voornaam = $voornaam;
    }

    public function setFamilienaam(string $familienaam)
    {
        $this->familienaam = $familienaam;
    }

    public function setFacturatieAdres(Address $facturatieAdres)
    {
        $this->facturatieAdres = $facturatieAdres;
    }

    public function setLeveringsAdres(Address $leveringsAdres)
    {
        $this->leveringsAdres = $leveringsAdres;
    }

    public function setBestelLijnen(array $bestelLijnen): void
    {
        $this->bestelLijnen = $bestelLijnen;
    }

    public function addBestelLijn(OrderLine $bestelLijn): void
    {
        $this->bestelLijnen[] = $bestelLijn;
    }
}
