<?php
namespace SR\UpsShip\Controller\Checkout;

use SR\UpsShip\Model\Carrier\UpsShip;

class SaveAdditional extends \Magento\Framework\App\Action\Action
{
    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $quote = $this->checkoutSession->getQuote();
        $shippingMethod = $this->getRequest()->getParam('shipping_method');
        $shippingUpsPickupId = $this->getRequest()->getParam('shipping_ups_pickup_id');
        $shippingAdditionalInformation = $this->getRequest()->getParam('shipping_additional information');
        $pickupMethod = UpsShip::UPS_SHIP_CARRIER_CODE . '_' . UpsShip::UPS_SHIP_PICKUP_METHOD_CODE;
        if ($shippingMethod == $pickupMethod) {
            $quote
                ->setShippingUpsPickupId(htmlspecialchars($shippingUpsPickupId))
                ->setShippingAdditionalInformation($shippingAdditionalInformation);
        } else {
            $quote
                ->setShippingUpsPickupId(null)
                ->setShippingAdditionalInformation(null);
        }

        if ($quote->hasDataChanges()) {
            $quote->getResource()->save($quote);
        }
    }
}
