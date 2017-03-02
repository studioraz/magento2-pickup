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
 * Class SalesQuoteSubmitBefore
 * @package SR\UpsShip\Observer
 */
class SalesQuoteSubmitBefore implements ObserverInterface
{
    /**
     * Sets UPS data to an order
     *
     * @param EventObserver $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $order->addData([
            'shipping_ups_pickup_id' => $quote->getData('shipping_ups_pickup_id'),
            'shipping_additional_information' => $quote->getData('shipping_additional_information')
        ]);
    }
}