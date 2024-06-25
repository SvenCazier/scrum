<?php
//src/Entities/OrderLine.php

declare(strict_types=1);

namespace Webshop\Entities;

class OrderLine
{
    private int $bestelLijnId;
    private Product $product;
    private int $aantalBesteld;
    private int $aantalGeannuleerd;

    /**
     * OrderLine constructor.
     *
     * @param int     $bestelLijnId       The ID of the order line.
     * @param Product $product            The product associated with the order line.
     * @param int     $aantalBesteld      The quantity ordered for this order line.
     * @param int     $aantalGeannuleerd The quantity canceled for this order line.
     */
    public function __construct(int $bestelLijnId, Product $product, int $aantalBesteld, int $aantalGeannuleerd)
    {
        $this->bestelLijnId = $bestelLijnId;
        $this->product = $product;
        $this->aantalBesteld = $aantalBesteld;
        $this->aantalGeannuleerd = $aantalGeannuleerd;
    }

    /**
     * Get the order line ID.
     *
     * @return int The order line ID.
     */
    public function getId(): int
    {
        return $this->bestelLijnId;
    }

    /**
     * Get the product associated with the order line.
     *
     * @return Product The product associated with the order line.
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Get the quantity ordered for this order line.
     *
     * @return int The quantity ordered for this order line.
     */
    public function getAantalBesteld(): int
    {
        return $this->aantalBesteld;
    }

    /**
     * Get the quantity canceled for this order line.
     *
     * @return int The quantity canceled for this order line.
     */
    public function getAantalGeannuleerd(): int
    {
        return $this->aantalGeannuleerd;
    }

    /**
     * Set the order line ID.
     *
     * @param int $bestelLijnId The order line ID to set.
     */
    public function setId(int $bestelLijnId): void
    {
        $this->bestelLijnId = $bestelLijnId;
    }

    /**
     * Set the product associated with the order line.
     *
     * @param Product $product The product to set.
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * Set the quantity ordered for this order line.
     *
     * @param int $aantalBesteld The quantity to set.
     */
    public function setAantalBesteld(int $aantalBesteld): void
    {
        $this->aantalBesteld = $aantalBesteld;
    }

    /**
     * Set the quantity canceled for this order line.
     *
     * @param int $aantalGeannuleerd The quantity to set.
     */
    public function setAantalGeannuleerd(int $aantalGeannuleerd): void
    {
        $this->aantalGeannuleerd = $aantalGeannuleerd;
    }
}
