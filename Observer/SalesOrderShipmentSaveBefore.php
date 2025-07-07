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
 * Class SalesOrderShipmentSaveBefore
 * @package SR\UpsShip\Observer
 */
class SalesOrderShipmentSaveBefore implements ObserverInterface
{

    protected $service;
    protected $trackFactory;
    protected $upsShipCarrier;
    protected $messageManager;

    public function __construct(
        \SR\UpsShip\Model\Service\InsertPickupsShipment $service,
        \Magento\Sales\Model\Order\Shipment\TrackFactory $trackFactory,
        \SR\UpsShip\Model\Carrier\UpsShip $upsShipCarrier,
        \Magento\Framework\Message\Manager $manager
    ) {
        $this->service = $service;
        $this->trackFactory = $trackFactory;
        $this->upsShipCarrier = $upsShipCarrier;
        $this->messageManager = $manager;
    }

    /**
     * Assigns tracking number to the newly created shipment
     *
     * @param EventObserver $observer
     * @throws \Exception
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
            if ($trackingData['error_code'] && $trackingData['error_message']) {
                $error = __('The service returned an error code "%1" with message "%2"',
                    $trackingData['error_code'],
                    $trackingData['error_message']
                );
                $this->messageManager->addError($error);
                throw new \Exception($error);
            }
            foreach ($trackingData['tracking_numbers'] as $trackingNumber) {
                $shipment->addTrack(
                    $this->trackFactory->create()
                        ->setNumber($trackingNumber)
                        ->setCarrierCode($this->upsShipCarrier->getCarrierCode())
                        ->setTitle($this->upsShipCarrier->getConfigData('title'))
                );
            }
        }
    }
}
