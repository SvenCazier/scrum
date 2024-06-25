<?php
//src/Services/ProductService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\SessionService;
use Webshop\Data\{CategoryDAO, ProductDAO};
use Webshop\Entities\Product;

class ProductService
{

    public function getProductByIdAsArray(int $id): array
    {
        $product = $this->getProductById($id);
        return array(
            'artikelId' => $product->getId(),
            'ean' => $product->getEan(),
            'naam' => $product->getNaam(),
            'beschrijving' => $product->getBeschrijving(),
            'prijs' => $product->getPrijs(),
            'gewichtInGram' => $product->getGewichtInGram(),
            'bestelpeil' => $product->getBestelpeil(),
            'voorraad' => $product->getVoorraad(),
            'minimumVoorraad' => $product->getMinimumVoorraad(),
            'maximumVoorraad' => $product->getMaximumVoorraad(),
            'levertijd' => $product->getLevertijd(),
            'aantalBesteldLeverancier' => $product->getAantalBesteldLeverancier(),
            'maxAantalInMagazijnPLaats' => $product->getMaxAantalInMagazijnPLaats(),
            'leveranciersId' => $product->getLeveranciersId(),
            'categorieId' => $product->getCategorieId()
        );
    }

    public function getProductById(int $id): Product
    {
        $productDao = new ProductDAO();
        $rawProduct = $productDao->getProductById($id);
        $categoryDao = new CategoryDAO();
        $rawCategories = $categoryDao->getCategoriesbyProductId($id);
        $categories = [];
        foreach ($rawCategories as $rawCategory) {
            $categories[] = $rawCategory["categorieId"];
        }
        $productObject = new Product(
            (int) $rawProduct["artikelId"],
            (string) $rawProduct["ean"],
            (string) $rawProduct["naam"],
            (string) $rawProduct["beschrijving"],
            (float) $rawProduct["prijs"],
            (int) $rawProduct["gewichtInGram"],
            (int) $rawProduct["bestelpeil"],
            (int) $rawProduct["voorraad"],
            (int) $rawProduct["minimumVoorraad"],
            (int) $rawProduct["maximumVoorraad"],
            (int) $rawProduct["levertijd"],
            (int) $rawProduct["aantalBesteldLeverancier"],
            (int) $rawProduct["maxAantalInMagazijnPlaats"],
            (int) $rawProduct["leveranciersId"],
            $categories
        );
        return $productObject;
    }

    public function getAllProducts(): array
    {
        $productDao = new ProductDAO();
        $rawProducts = $productDao->getAllProducts();
        $categoryDao = new CategoryDAO();
        $products = [];
        foreach ($rawProducts as $rawProduct) {
            $rawCategories = $categoryDao->getCategoriesbyProductId((int) $rawProduct["artikelId"]);
            $categories = [];
            foreach ($rawCategories as $rawCategory) {
                $categories[] = $rawCategory["categorieId"];
            }
            $productObject = new Product(
                (int) $rawProduct["artikelId"],
                (string) $rawProduct["ean"],
                (string) $rawProduct["naam"],
                (string) $rawProduct["beschrijving"],
                (float) $rawProduct["prijs"],
                (int) $rawProduct["gewichtInGram"],
                (int) $rawProduct["bestelpeil"],
                (int) $rawProduct["voorraad"],
                (int) $rawProduct["minimumVoorraad"],
                (int) $rawProduct["maximumVoorraad"],
                (int) $rawProduct["levertijd"],
                (int) $rawProduct["aantalBesteldLeverancier"],
                (int) $rawProduct["maxAantalInMagazijnPlaats"],
                (int) $rawProduct["leveranciersId"],
                $categories
            );
            $products[] = $productObject;
        }
        return $products;
    }

    public function getAllProductsByCategoryId(int $id): array
    {
        $productDao = new ProductDAO();
        $rawProducts = $productDao->getAllProductsByCategoryId($id);
        $products = [];
        foreach ($rawProducts as $rawProduct) {
            $products[] = $this->getProductById((int) $rawProduct["artikelId"]);
        }
        return $products;
    }

    public function getCategorieIdByProductId(int $id): array{
        $categoryDAO = new CategoryDAO();
        return $categoryDAO->getCategoriesbyProductId($id);
    }

    public function productsBySearch(string $search): array{
        $productDao = new ProductDAO();
        $rawProducts = $productDao->productsBySearch($search);
        $products = [];
        foreach ($rawProducts as $rawProduct) {
            $products[] = $this->getProductById((int) $rawProduct["artikelId"]);
        }
        return $products;
    }
}
