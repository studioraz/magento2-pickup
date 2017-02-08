<?php
/**
 * Copyright ©2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDORAZ.txt for license details.
 */
namespace SR\UpsShip\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

/**
 * Class EmailOrderSetTemplateVarsBefore
 * @package SR\UpsShip\Observer
 */
class EmailOrderSetTemplateVarsBefore implements ObserverInterface
{
    /**
     * Sets additional email variable
     *
     * @param EventObserver $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $variablesObject = $observer->getEvent()->getTransport();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $variablesObject->getOrder();
        if ($order->getIsVirtual()) {
            return;
        }
        $shippingAddress = $order->getShippingAddress();
        try {
            $locationData = \Zend_Json::decode($shippingAddress->getShippingAdditionalInformation());
        } catch (\Exception $e) {
            return;
        }
        $locationAddress = $locationData['title'] . ': ' . $locationData['city'] . ', ' . $locationData['street'] . ', ' . $locationData['zip'];
        $oldShippingMsg = $variablesObject->getShippingMsg() ? $variablesObject->getShippingMsg() . '; ' : '';
        $variablesObject->setShippingMsg($oldShippingMsg . ' ' . $locationAddress);
    }
}