<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Controller\Checkout;

/**
 * Class SaveAdditional
 * @package SR\UpsShip\Controller\Checkout
 */
class SaveAdditional extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * Saves UPS shipping additional information to the Quote
     *
     * @throws \Exception
     */
    public function execute()
    {
        $quote = $this->checkoutSession->getQuote();
        $isActive = $this->getRequest()->getParam('is_active');
        $shippingUpsPickupId = $this->getRequest()->getParam('shipping_ups_pickup_id');
        $shippingAdditionalInformation = $this->getRequest()->getParam('shipping_additional_information');
        if ($isActive) {
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
