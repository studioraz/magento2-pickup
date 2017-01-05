<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */

namespace SR\UpsShip\Plugin;

use SR\UpsShip\Model\Carrier\UpsShip;
use Magento\Framework\Exception\InputException;

/**
 * Class ShippingInformationManagement
 * @package SR\UpsShip\Plugin
 */
class ShippingInformationManagement
{
    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     * @throws InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        \Closure $proceed,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $returnValue = $proceed($cartId, $addressInformation);
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $shippingUpsPickupId = $extAttributes->getUpsIid();
        $shippingAdditionalInformation = $extAttributes->getUpsLocation();
        $quote = $this->quoteRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();
        $pickupMethod = UpsShip::UPS_SHIP_CARRIER_CODE . '_' . UpsShip::UPS_SHIP_PICKUP_METHOD_CODE;
        if ($shippingAddress->getShippingMethod() == $pickupMethod) {
            if (!$shippingUpsPickupId || !$shippingAdditionalInformation) {
                throw new InputException(__('Please select pickup location for this carrier'));
            }
            $shippingAddress
                ->setShippingUpsPickupId($shippingUpsPickupId)
                ->setShippingAdditionalInformation($shippingAdditionalInformation)
                ->save();
        }

        return $returnValue;
    }
}
