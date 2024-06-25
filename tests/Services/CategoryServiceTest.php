<?php

use PHPUnit\Framework\TestCase;
use Webshop\Entities\{Category};
use Webshop\Services\{CategoryService};

class CategoryServiceTest extends TestCase
{
    public function testCategoryMethods()
    {
        $categoryService = new CategoryService();
        $result = $categoryService->getHeadCategories();
        var_dump($result);
        $this->assertNotEmpty($result);
    }
}
