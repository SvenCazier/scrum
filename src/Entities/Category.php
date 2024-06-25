<?php
//src/Entities/Category.php

declare(strict_types=1);

namespace Webshop\Entities;

use Webshop\Entities\Product;

class Category
{
    private int $categorieId;
    private string $naam;
    private ?int $hoofdCategorieId;
    private array $subCategories;

    public function __construct(int $categorieId, string $naam, ?int $hoofdCategorieId, array $subCategories = [])
    {
        $this->categorieId = $categorieId;
        $this->naam = $naam;
        $this->hoofdCategorieId = $hoofdCategorieId;
        $this->subCategories = $subCategories;
    }

    // Getters
    public function getId(): int
    {
        return $this->categorieId;
    }

    public function getNaam(): string
    {
        return $this->naam;
    }
    public function getHoofdCategorieId(): ?int
    {
        return $this->hoofdCategorieId;
    }

    public function getSubCategories(): array
    {
        return $this->subCategories;
    }

    public function setId(int $categorieId): void
    {
        $this->categorieId = $categorieId;
    }

    public function setNaam(string $naam): void
    {
        $this->naam = $naam;
    }
    public function setHoofdCategorieId(int $hoofdCategorieId): void
    {
        $this->hoofdCategorieId = $hoofdCategorieId;
    }

    public function setSubCategories(array $subCategories): void
    {
        $this->subCategories = $subCategories;
    }

    public function addSubCategory(Category $subCategory): void
    {
        $this->subCategories[] = $subCategory;
    }
}
