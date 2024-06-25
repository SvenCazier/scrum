<?php

use PHPUnit\Framework\TestCase;
use Webshop\Entities\{Address, Place, User};
use Webshop\Services\{AuthenticationService, ErrorService, LoginService};

class LoginServiceTest extends TestCase
{
    public function testLoginWithInValidEmail()
    {
        $loginService = new LoginService();
        $loginService->login("invalid@example.com", "");
        $expectedResult = [
            "AuthenticationError" => "De ingevoerde inloggegevens zijn onjuist. Probeer het opnieuw."
        ];
        $actualResult = ErrorService::getErrors();
        $this->assertEquals($expectedResult, $actualResult);
    }
    public function testLoginWithDisabledEmail()
    {
        $loginService = new LoginService();
        $loginService->login("anoniemeKlant@prularia.com", "");
        $expectedResult = [
            "AuthenticationError" => "De ingevoerde inloggegevens zijn onjuist. Probeer het opnieuw."
        ];
        $actualResult = ErrorService::getErrors();
        $this->assertEquals($expectedResult, $actualResult);
    }
    public function testLoginWithEmptyPassword()
    {
        $loginService = new LoginService();
        $loginService->login("ad.ministrateur@vdab.be", "");
        $expectedResult = [
            "AuthenticationError" => "De ingevoerde inloggegevens zijn onjuist. Probeer het opnieuw."
        ];
        $actualResult = ErrorService::getErrors();
        $this->assertEquals($expectedResult, $actualResult);
    }
    public function testLoginWithInvalidPassword()
    {
        $loginService = new LoginService();
        $loginService->login("ad.ministrateur@vdab.be", "invalidPassword");
        $expectedResult = [
            "AuthenticationError" => "De ingevoerde inloggegevens zijn onjuist. Probeer het opnieuw."
        ];
        $actualResult = ErrorService::getErrors();
        $this->assertEquals($expectedResult, $actualResult);
    }
    public function testLoginWithValidCredentialsLegalPerson()
    {
        $loginService = new LoginService();
        $loginService->login("ad.ministrateur@vdab.be", "KlantVanPrularia");
        $actualResult = AuthenticationService::isAuthenticated();
        $this->assertInstanceOf(User::class, $actualResult);
        $this->assertInstanceOf(Address::class, $actualResult->getFacturatieAdres());
        $this->assertInstanceOf(Place::class, $actualResult->getFacturatieAdres()->getPlaats());
        $this->assertInstanceOf(Address::class, $actualResult->getLeveringsAdres());
        $this->assertInstanceOf(Place::class, $actualResult->getLeveringsAdres()->getPlaats());

        $this->assertEquals(2, $actualResult->getId());
        $this->assertEquals("ad.ministrateur@vdab.be", $actualResult->getEmailadres());
        $this->assertEquals(1, $actualResult->getKlantId());
        $this->assertEquals("Ad", $actualResult->getVoornaam());
        $this->assertEquals("Ministrateur", $actualResult->getFamilienaam());

        $this->assertEquals(1, $actualResult->getFacturatieAdres()->getId());
        $this->assertEquals("Keizerslaan", $actualResult->getFacturatieAdres()->getStraat());
        $this->assertEquals("11", $actualResult->getFacturatieAdres()->getHuisNummer());
        $this->assertNull($actualResult->getFacturatieAdres()->getBus());
        $this->assertEquals(381, $actualResult->getFacturatieAdres()->getPlaats()->getId());
        $this->assertEquals("1000", $actualResult->getFacturatieAdres()->getPlaats()->getPostcode());
        $this->assertEquals("Brussel", $actualResult->getFacturatieAdres()->getPlaats()->getPlaats());
        $this->assertTrue($actualResult->getFacturatieAdres()->getActief());

        $this->assertEquals(7, $actualResult->getLeveringsAdres()->getId());
        $this->assertEquals("Sterrekundelaan", $actualResult->getLeveringsAdres()->getStraat());
        $this->assertEquals("14", $actualResult->getLeveringsAdres()->getHuisNummer());
        $this->assertNull($actualResult->getLeveringsAdres()->getBus());
        $this->assertEquals(2203, $actualResult->getLeveringsAdres()->getPlaats()->getId());
        $this->assertEquals("1210", $actualResult->getLeveringsAdres()->getPlaats()->getPostcode());
        $this->assertEquals("Sint-Joost-Ten-Noode", $actualResult->getLeveringsAdres()->getPlaats()->getPlaats());
        $this->assertTrue($actualResult->getLeveringsAdres()->getActief());

        $this->assertEquals("CEO", $actualResult->getFunctie());
        $this->assertEquals("VDAB", $actualResult->getBedrijfsNaam());
        $this->assertEquals("0887010362", $actualResult->getBtwNummer());
    }
    public function testRemainsLoggedIn()
    {
        $actualResult = AuthenticationService::isAuthenticated();
        $this->assertInstanceOf(User::class, $actualResult);
    }
    public function testLogout()
    {
        AuthenticationService::logout();
        $actualResult = AuthenticationService::isAuthenticated();
        $this->assertNull($actualResult);
    }
    public function testLoginWithValidCredentialsNaturalPerson()
    {
        $loginService = new LoginService();
        $loginService->login("alpha.klant@bestaatniet.be", "KlantVanPrularia");
        $actualResult = AuthenticationService::isAuthenticated();
        $this->assertInstanceOf(User::class, $actualResult);
        $this->assertInstanceOf(Address::class, $actualResult->getFacturatieAdres());
        $this->assertInstanceOf(Place::class, $actualResult->getFacturatieAdres()->getPlaats());
        $this->assertInstanceOf(Address::class, $actualResult->getLeveringsAdres());
        $this->assertInstanceOf(Place::class, $actualResult->getLeveringsAdres()->getPlaats());

        $this->assertEquals(3, $actualResult->getId());
        $this->assertEquals("alpha.klant@bestaatniet.be", $actualResult->getEmailadres());
        $this->assertEquals(2, $actualResult->getKlantId());
        $this->assertEquals("Alpha", $actualResult->getVoornaam());
        $this->assertEquals("Klant", $actualResult->getFamilienaam());

        $this->assertEquals(2, $actualResult->getFacturatieAdres()->getId());
        $this->assertEquals("Interleuvenlaan", $actualResult->getFacturatieAdres()->getStraat());
        $this->assertEquals("2", $actualResult->getFacturatieAdres()->getHuisNummer());
        $this->assertNull($actualResult->getFacturatieAdres()->getBus());
        $this->assertEquals(1037, $actualResult->getFacturatieAdres()->getPlaats()->getId());
        $this->assertEquals("3001", $actualResult->getFacturatieAdres()->getPlaats()->getPostcode());
        $this->assertEquals("Heverlee", $actualResult->getFacturatieAdres()->getPlaats()->getPlaats());
        $this->assertTrue($actualResult->getFacturatieAdres()->getActief());

        $this->assertEquals(2, $actualResult->getLeveringsAdres()->getId());
        $this->assertEquals("Interleuvenlaan", $actualResult->getLeveringsAdres()->getStraat());
        $this->assertEquals("2", $actualResult->getLeveringsAdres()->getHuisNummer());
        $this->assertNull($actualResult->getLeveringsAdres()->getBus());
        $this->assertEquals(1037, $actualResult->getLeveringsAdres()->getPlaats()->getId());
        $this->assertEquals("3001", $actualResult->getLeveringsAdres()->getPlaats()->getPostcode());
        $this->assertEquals("Heverlee", $actualResult->getLeveringsAdres()->getPlaats()->getPlaats());
        $this->assertTrue($actualResult->getLeveringsAdres()->getActief());

        $this->assertNull($actualResult->getFunctie());
        $this->assertNull($actualResult->getBedrijfsNaam());
        $this->assertNull($actualResult->getBtwNummer());
        AuthenticationService::logout();
    }
}
