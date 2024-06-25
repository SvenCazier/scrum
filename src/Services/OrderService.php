<?php
//src/Services/OrderService.php
declare(strict_types=1);

namespace Webshop\Services;

use Exception;
use Webshop\Data\OrderDao;
use Webshop\Services\{AddressService, ProductService};
use Webshop\Entities\{Order, OrderLine, OrderStatus, PaymentMethod, User};

class OrderService
{
    /**
     * Creates an order for the given user.
     *
     * @param User $user The user for whom the order is created.
     * @param Order $order The order details.
     *
     * @return true If successful, false otherwise.
     * @throws Exception If an error occurs during the creation of the order.
     */
    public function createOrder(User $user, array $order): bool
    {
        try {
            $orderDAO = new OrderDAO();
            $orderDAO->beginTransaction();

            //create order
            $bestelId = $orderDAO->createOrder([
                "besteldatum" => date("Y-m-d H:i:s"),
                "klantId" => $user->getKlantId(),
                "betaald" => (int) ($order["paymentMethod"] != "2"), // 0 (false) als overschrijving (id == 2), 1 (true) als kredietkaart (id == 1)
                "betaalwijzeId" => (int) $order["paymentMethod"], // 1 als krediedkaart, 2 als overschrijving
                "bestellingsStatusId" => 3 - (int) $order["paymentMethod"], // 1 als overschrijving, 2 als kredietkaart
                "actiecodeGebruikt" => 0, // promo code not implemented yet
                "bedrijfsnaam" => $user->getBedrijfsNaam(),
                "btwNummer" => $user->getBtwNummer(),
                "voornaam" => $user->getVoornaam(),
                "familienaam" => $user->getFamilienaam(),
                "facturatieAdresId" => $user->getFacturatieAdres()->getId(),
                "leveringsAdresId" => $user->getLeveringsAdres()->getId()
            ]);

            // create orderlines
            foreach ($order["orderLines"] as $orderLine) {
                $orderDAO->createOrderLine([
                    "bestelId" => $bestelId,
                    "artikelId" => $orderLine["id"],
                    "aantalBesteld" => $orderLine["quantity"]
                ]);
            }

            // adjust promo code if single use
            // promo code not implemented yet
            $orderDAO->commitTransaction();
            return true;
        } catch (Exception $e) {
            // if db error
            $orderDAO->rollBackTransaction();
            ErrorService::setError($e->getMessage(), "bestelError");
            return false;
        }
    }



    public function getOrderById(int $id): Order
    {
        $orderDao = new OrderDao();
        $rawOrder = $orderDao->getOrderById($id);
        $paymentMethod = $this->getPaymentMethodById($rawOrder["betaalwijzeId"]);
        $orderStatus = $this->getOrderStatusById($rawOrder["bestellingsStatusId"]);
        $addressService = new AddressService();
        $billingAddressId = $rawOrder["facturatieAdresId"];
        $billingAdress = $addressService->getAddressById($billingAddressId);
        $deliveryAddressId = $rawOrder["leveringsAdresId"];
        $deliveryAdress = $addressService->getAddressById($deliveryAddressId);
        $orderlines = $orderDao->getAllOrderLinesByOrderId($id);

        $orderObject = new Order(
            (int) $rawOrder['bestelId'],
            (string)$rawOrder['besteldatum'],
            (int) $rawOrder['klantId'],
            $paymentMethod,
            (bool) $rawOrder['betalingscode'],
            (string) $rawOrder['betaalwijzeId'],
            (bool) $rawOrder['annulatie'],
            (string) $rawOrder['annulatiedatum'],
            (string) $rawOrder['terugbetalingscode'],
            $orderStatus,
            (bool) $rawOrder['actieCodeGebruikt'],
            (string) $rawOrder['bedrijfsnaam'],
            (string) $rawOrder['btwNummer'],
            (string) $rawOrder['voornaam'],
            (string) $rawOrder['familieNaam'],
            $billingAdress,
            $deliveryAdress,
            $orderlines
        );
        return $orderObject;
    }

    public function getOrderLineByOrderLineId(int $id): OrderLine
    {
        $orderDao = new OrderDao();
        $rawOrderLine = $orderDao->getOrderLinesByOrderLineId($id);
        $productService = new ProductService();
        $productId = (int) $rawOrderLine['artikelId'];
        $product = $productService->getProductById($productId);

        $orderlineObject = new OrderLine(
            (int) $rawOrderLine['bestellijnId'],
            $product,
            (int) $rawOrderLine['aantalBesteld'],
            (int) $rawOrderLine['aantalGeannuleerd'],
        );
        return $orderlineObject;
    }

    public function getAllOrderLinesByOrderId(int $id): array
    {
        $orderDao = new OrderDao();
        $rawOrderLines = $orderDao->getAllOrderLinesByOrderId($id);
        $orderLines = [];
        foreach ($rawOrderLines as $rawOrderLine) {
            $orderLines[] = $this->getOrderLineByOrderLineId((int) $rawOrderLine['bestellijnId']);
        }
        return $orderLines;
    }

    public function getOrderStatusById(int $id): OrderStatus
    {
        $orderDao = new OrderDao();
        $rawOrderStatus = $orderDao->getOrderStatusById($id);
        $orderStatusObject = new OrderStatus(
            (int) $rawOrderStatus['bestellingsStatusId'],
            (string) $rawOrderStatus['naam'],
        );
        return $orderStatusObject;
    }

    public function getPaymentMethodById(int $id): PaymentMethod
    {
        $orderDao = new OrderDao();
        $rawPaymentMethod = $orderDao->getPaymentMethodById($id);
        $paymentMethodObject = new PaymentMethod(
            (int) $rawPaymentMethod['betaalwijzeId'],
            (string) $rawPaymentMethod['naam'],
        );
        return $paymentMethodObject;
    }

    public function getPaymentMethods(): array
    {
        $orderDao = new OrderDao();
        $rawPaymentMethods = $orderDao->getPaymentMethods();
        $paymentMethods = [];
        foreach ($rawPaymentMethods as $rawPaymentMethod) {
            $paymentMethods[] = new PaymentMethod((int) $rawPaymentMethod['betaalwijzeId'], $rawPaymentMethod['naam']);
        }
        return $paymentMethods;
    }
}

//probably broken service methods after the change in the order/orderline entities