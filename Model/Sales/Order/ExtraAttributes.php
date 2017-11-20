<?php

namespace SR\UpsShip\Model\Sales\Order;

use Magento\Framework\DataObject;
use SR\UpsShip\Api\Data\Sales\Order\ExtraAttributesInterface;

final class ExtraAttributes extends DataObject implements ExtraAttributesInterface
{
    /**
     * Returns shipping ups Pickup Id
     *
     * @return string|null Shipping Ups Pickup Id
     */
    public function getShippingUpsPickupId()
    {
        return $this->getData(self::SHIPPING_UPS_PICKUP_ID);
    }

    /**
     * Sets shipping ups Pickup Id
     *
     * @param string $shippingPickupId
     * @return string|null Shipping Ups Pickup Id
     */
    public function setShippingUpsPickupId($shippingPickupId)
    {
        return $this->setData(self::SHIPPING_UPS_PICKUP_ID, $shippingPickupId);
    }

    /**
     * Returns shipping Additional Information
     *
     * @return string|null Shipping Additional Information
     */
    public function getShippingAdditionalInformation()
    {
        return $this->getData(self::SHIPPING_ADDITIONAL_INFORMATION);
    }

    /**
     * Sets shipping Additional Information
     *
     * @param string $shippingAdditionalInformation
     * @return string|null Shipping Additional Information
     */
    public function setShippingAdditionalInformation($shippingAdditionalInformation)
    {
        return $this->setData(self::SHIPPING_ADDITIONAL_INFORMATION, $shippingAdditionalInformation);
    }
}