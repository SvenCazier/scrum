<?php
//tests/UserDAOTest.php

use PHPUnit\Framework\TestCase;
use Webshop\Data\UserDAO;

class UserDAOTest extends TestCase
{

    public function testGetUserLoginInfoWithInValidEmail()
    {
        $userDAO = new UserDAO();
        $email = "invalid@example.com";
        $actualResult = $userDAO->getUserLoginInfo($email);
        $this->assertFalse($actualResult);
    }

    public function testGetUserLoginInfoWithDisabledEmail()
    {
        $userDAO = new UserDAO();
        $email = "anoniemeKlant@prularia.com";
        $actualResult = $userDAO->getUserLoginInfo($email);
        $this->assertFalse($actualResult);
    }


    public function testGetUserLoginInfoWithValidEmail()
    {
        $userDAO = new UserDAO();
        $email = "ad.ministrateur@vdab.be";
        $expectedResult = ["gebruikersAccountId" => 2, "paswoord" => "KlantVanPrularia"];
        $actualResult = $userDAO->getUserLoginInfo($email);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetUserInfoById()
    {
        $userDAO = new UserDAO();
        $expectedResult =
            [
                "gebruikersAccountId" => 2,
                "emailadres" => "ad.ministrateur@vdab.be",
                "voornaam" => "Ad",
                "familienaam" => "Ministrateur",
                "functie" => "CEO",
                "bedrijfsNaam" => "VDAB",
                "btwNummer" => "0887010362",
                "klantId" => 1,
                "facturatieAdresId" => 1,
                "leveringsAdresId" => 7
            ];
        $actualResult = $userDAO->getUserInfoById(2);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testCreateUserAccount() {
        $userDAO = new UserDAO();
        $expectedResult = "22"; // check your DB the next ID to be inserted, this is different on every DB
        $actualResult = $userDAO->createUserAccount("Foo", "bar", 0);
        $this->assertEquals($expectedResult, $actualResult);
    }
}
