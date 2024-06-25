<?php
//src/Entities/OrderStatus.php

declare(strict_types=1);

namespace Webshop\Entities;

class OrderStatus
{
    private int $bestellingsStatusId;
    private string $naam;

    /**
     * OrderStatus constructor.
     * @param int $bestellingsStatusId The ID of the order status.
     * @param string $naam The name of the order status.
     */
    public function __construct(int $bestellingsStatusId, string $naam)
    {
        $this->bestellingsStatusId = $bestellingsStatusId;
        $this->naam = $naam;
    }

    /**
     * Get the ID of the order status.
     * @return int The ID of the order status.
     */
    public function getId(): int
    {
        return $this->bestellingsStatusId;
    }

    /**
     * Get the name of the order status.
     * @return string The name of the order status.
     */
    public function getNaam(): string
    {
        return $this->naam;
    }

    /**
     * Set the ID of the order status.
     * @param int $bestellingsStatusId The ID of the order status.
     */
    public function setId(int $bestellingsStatusId): void
    {
        $this->bestellingsStatusId = $bestellingsStatusId;
    }

    /**
     * Set the name of the order status.
     * @param string $naam The name of the order status.
     */
    public function setNaam(string $naam): void
    {
        $this->naam = $naam;
    }
}
