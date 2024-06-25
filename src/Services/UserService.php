<?php
//src/Services/UserService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\SessionService;
use Webshop\Data\UserDAO;
use Webshop\Services\AddressService;
use Webshop\Entities\User;

class UserService{
    public function getUserInfoById(int $id): User{
        $userDAO = new UserDAO();
        $rawUser = $userDAO->getUserInfoById($id);
        $addressService = new AddressService();
        $billingAddressId = $rawUser["facturatieAdresId"];
        $billingAdress = $addressService->getAddressById($billingAddressId);
        $deliveryAddressId = $rawUser["leveringsAdresId"];
        $deliveryAdress = $addressService->getAddressById($deliveryAddressId);
        $user = new User(
            (int) $rawUser["gebruikersAccountId"],
            (string) $rawUser["emailadres"],
            (int) $rawUser["klantId"],
            (string) $rawUser["voornaam"],
            (string) $rawUser["familienaam"],
            $billingAdress,
            $deliveryAdress,
            (string) $rawUser["functie"],
            (string) $rawUser["bedrijfsNaam"],
            (string) $rawUser["btwNummer"],
        );
        return $user;
    }
}