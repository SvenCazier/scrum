<?php
//src/Services/AddressService.php
declare(strict_types=1);

namespace Webshop\Services;

use Webshop\Services\SessionService;
use Webshop\Data\AddressDAO;
use Webshop\Entities\Address;
use Webshop\Entities\Place;

class AddressService
{
    public function getAddressById(int $id): Address
    {
        $addressDao = new AddressDAO();
        $rawAddress = $addressDao->getAddressById($id);
        $placeId = $rawAddress['plaatsId'];
        $rawPlace = $addressDao->getPlaceById($placeId);
        $place = new Place(
            (int) $rawPlace['plaatsId'],
            (string) $rawPlace['postcode'],
            (string) $rawPlace['plaats']
        );
        $address = new Address(
            (int) $rawAddress['adresId'],
            (string) $rawAddress['straat'],
            (string) $rawAddress['huisNummer'],
            $place,
            (bool) $rawAddress['actief']
        );
        return $address;
    }

    public function createAddress(string $straat, string $huisNummer, string $bus, string $zipCode, string $location): Address
    {
        $addressDao = new AddressDAO();
        $placeArray = $addressDao->getPlaceByZipCode($zipCode);
        $placeId = 0;
        $placeLocation = "";
        if (count($placeArray) > 1) {
            foreach ($placeArray as $place) {
                if ($place["plaats"] === $location) {
                    $placeId = $place["plaatsId"];
                    $placeLocation =
                        $place["plaats"];
                }
            }
        } else {
            $placeId = $placeArray[0]["plaatsId"];
            $placeLocation =
                $placeArray[0]["plaats"];
        }

        $addressId = $addressDao->createAddress($straat, $huisNummer, $bus, (int) $placeId);
        $place = new Place((int) $placeId, $zipCode, $placeLocation);
        return new Address((int) $addressId, $straat, $huisNummer, $place, true, $bus);
    }
}
