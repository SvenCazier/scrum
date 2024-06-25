<?php
//src/Entities/Place.php

declare(strict_types=1);

namespace Webshop\Entities;

class Place
{
    private int $plaatsId;
    private string $postcode;
    private string $plaats;

    public function __construct(int $plaatsId, string $postcode, string $plaats)
    {
        $this->plaatsId = $plaatsId;
        $this->postcode = $postcode;
        $this->plaats = $plaats;
    }

    // Getters
    public function getId(): int
    {
        return $this->plaatsId;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function getPlaats(): string
    {
        return $this->plaats;
    }

    // Setters
    public function setId(int $plaatsId)
    {
        $this->plaatsId = $plaatsId;
    }

    public function setPostcode(string $postcode)
    {
        $this->postcode = $postcode;
    }

    public function setPlaats(string $plaats)
    {
        $this->plaats = $plaats;
    }
}
