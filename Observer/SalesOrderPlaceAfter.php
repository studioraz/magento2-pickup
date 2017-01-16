<?php
/**
 * Copyright ©2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDORAZ.txt for license details.
 */
namespace SR\UpsShip\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

/**
 * Class SalesOrderPlaceAfter
 * @package SR\UpsShip\Observer
 * @todo add storing tracking number in the order/shipment/etc entity
 */
class SalesOrderPlaceAfter implements ObserverInterface
{
    public function __construct(\SR\UpsShip\Model\Service\InsertPickupsShipment $service)
    {
        $this->service = $service;
    }

    /**
     * Request tracking numbers and creates a shipment
     *
     * @param EventObserver $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $data = $this->service->setEntity($order)->execute();
        if (!empty($data['tracking_number'])) {
            $order->setData('tracking_numbers', [$data['tracking_number']]);
        }
    }
}