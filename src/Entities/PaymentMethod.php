<?php
//src/Entities/PaymentType.php

declare(strict_types=1);

namespace Webshop\Entities;


class PaymentMethod
{
    private int $wijzeId;
    private string $naam;

    public function __construct(int $wijzeId, string $naam)
    {
        $this->wijzeId = $wijzeId;
        $this->naam = $naam;
    }

    // Getters
    public function getId(): int
    {
        return $this->wijzeId;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }

    // Setters
    public function setWijzeId(int $wijzeId)
    {
        $this->wijzeId = $wijzeId;
    }

    public function setNaam(string $naam)
    {
        $this->naam = $naam;
    }
}
