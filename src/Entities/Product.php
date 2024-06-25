<?php
//src/Entities/Product.php

declare(strict_types=1);

namespace Webshop\Entities;

class Product
{
    private int $artikelId;
    private string $ean;
    private string $naam;
    private string $beschrijving;
    private float $prijs;
    private int $gewichtInGram;
    private int $bestelpeil;
    private int $voorraad;
    private int $minimumVoorraad;
    private int $maximumVoorraad;
    private int $levertijd;
    private int $aantalBesteldLeverancier;
    private int $maxAantalInMagazijnPLaats;
    private int $leveranciersId;
    private array $categorieId;

    public function __construct(
        int $artikelId,
        string $ean,
        string $naam,
        string $beschrijving,
        float $prijs,
        int $gewichtInGram,
        int $bestelpeil,
        int $voorraad,
        int $minimumVoorraad,
        int $maximumVoorraad,
        int $levertijd,
        int $aantalBesteldLeverancier,
        int $maxAantalInMagazijnPLaats,
        int $leveranciersId,
        array $categorieId = []
    ) {
        $this->artikelId = $artikelId;
        $this->ean = $ean;
        $this->naam = $naam;
        $this->beschrijving = $beschrijving;
        $this->prijs = $prijs;
        $this->gewichtInGram = $gewichtInGram;
        $this->bestelpeil = $bestelpeil;
        $this->voorraad = $voorraad;
        $this->minimumVoorraad = $minimumVoorraad;
        $this->maximumVoorraad = $maximumVoorraad;
        $this->levertijd = $levertijd;
        $this->aantalBesteldLeverancier = $aantalBesteldLeverancier;
        $this->maxAantalInMagazijnPLaats = $maxAantalInMagazijnPLaats;
        $this->leveranciersId = $leveranciersId;
        $this->categorieId = $categorieId;
    }

    // Getters
    public function getId(): int
    {
        return $this->artikelId;
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    public function getBeschrijving(): string
    {
        return $this->beschrijving;
    }

    public function getPrijs(): float
    {
        return $this->prijs;
    }

    public function getGewichtInGram(): int
    {
        return $this->gewichtInGram;
    }

    public function getBestelpeil(): int
    {
        return $this->bestelpeil;
    }

    public function getVoorraad(): int
    {
        return $this->voorraad;
    }

    public function getMinimumVoorraad(): int
    {
        return $this->minimumVoorraad;
    }

    public function getMaximumVoorraad(): int
    {
        return $this->maximumVoorraad;
    }

    public function getLevertijd(): int
    {
        return $this->levertijd;
    }

    public function getAantalBesteldLeverancier(): int
    {
        return $this->aantalBesteldLeverancier;
    }

    public function getMaxAantalInMagazijnPLaats(): int
    {
        return $this->maxAantalInMagazijnPLaats;
    }

    public function getLeveranciersId(): int
    {
        return $this->leveranciersId;
    }

    public function getCategorieId(): array
    {
        return $this->categorieId;
    }

    // Setters
    public function setArtikelId(int $artikelId)
    {
        $this->artikelId = $artikelId;
    }

    public function setEan(string $ean)
    {
        $this->ean = $ean;
    }

    public function setNaam(string $naam)
    {
        $this->naam = $naam;
    }

    public function setBeschrijving(string $beschrijving)
    {
        $this->beschrijving = $beschrijving;
    }

    public function setPrijs(float $prijs)
    {
        $this->prijs = $prijs;
    }

    public function setGewichtInGram(int $gewichtInGram)
    {
        $this->gewichtInGram = $gewichtInGram;
    }

    public function setBestelpeil(int $bestelpeil)
    {
        $this->bestelpeil = $bestelpeil;
    }

    public function setVoorraad(int $voorraad)
    {
        $this->voorraad = $voorraad;
    }

    public function setMinimumVoorraad(int $minimumVoorraad)
    {
        $this->minimumVoorraad = $minimumVoorraad;
    }

    public function setMaximumVoorraad(int $maximumVoorraad)
    {
        $this->maximumVoorraad = $maximumVoorraad;
    }

    public function setLevertijd(int $levertijd)
    {
        $this->levertijd = $levertijd;
    }

    public function setAantalBesteldLeverancier(int $aantalBesteldLeverancier)
    {
        $this->aantalBesteldLeverancier = $aantalBesteldLeverancier;
    }

    public function setMaxAantalInMagazijnPLaats(int $maxAantalInMagazijnPLaats)
    {
        $this->maxAantalInMagazijnPLaats = $maxAantalInMagazijnPLaats;
    }

    public function setLeveranciersId(int $leveranciersId)
    {
        $this->leveranciersId = $leveranciersId;
    }

    public function setCategorieId(array $categorieId)
    {
        $this->categorieId = $categorieId;
    }
}
