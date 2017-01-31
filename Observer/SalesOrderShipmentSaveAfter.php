<?php
namespace SR\UpsShip\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class SalesOrderShipmentSaveAfter implements ObserverInterface
{
    public function __construct(
        \SR\UpsShip\Model\Service\InsertPickupsShipment $service,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $trackFactory,
        \SR\UpsShip\Model\Carrier\UpsShip $upsShipCarrier
    )
    {
        $this->service = $service;
        $this->trackFactory = $trackFactory;
        $this->upsShipCarrier = $upsShipCarrier;
    }

    /**
     * Assigns tracking number to the newly created shipment
     *
     * @param EventObserver $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
        if ($order->getShippingMethod(true)->getCarrierCode() == $this->upsShipCarrier->getCarrierCode()
            && $order->getTracksCollection()->count() == 0
        ) {
            $trackingData = $this->service->setEntity($order)->execute();
            foreach ($trackingData['tracking_numbers'] as $trackingNumber) {
                $shipment->addTrack(
                    $this->trackFactory->create()
                        ->setNumber($trackingNumber)
                        ->setCarrierCode($this->upsShipCarrier->getCarrierCode())
                        ->setTitle($this->upsShipCarrier->getConfigData('title'))
                );
            }
            $shipment->save();
        }
    }
}