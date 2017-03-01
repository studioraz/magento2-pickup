<?php
namespace SR\UpsShip\Controller\Checkout;

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
