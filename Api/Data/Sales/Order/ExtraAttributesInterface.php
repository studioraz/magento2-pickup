<?php

namespace SR\UpsShip\Api\Data\Sales\Order;

interface ExtraAttributesInterface
{
    /**
     * Shipping Ups Pickup Id
     */
    const SHIPPING_UPS_PICKUP_ID = 'shipping_ups_pickup_id';

    /**
     * Shipping Additional Information
     */
    const SHIPPING_ADDITIONAL_INFORMATION = 'shipping_additional_information';

    /**
     * Returns shipping ups Pickup Id
     *
     * @return string|null Shipping Ups Pickup Id
     */
    public function getShippingUpsPickupId();

    /**
     * Sets shipping ups Pickup Id
     *
     * @param string $shippingPickupId
     * @return string|null Shipping Ups Pickup Id
     */
    public function setShippingUpsPickupId($shippingPickupId);

    /**
     * Returns shipping Additional Information
     *
     * @return string|null Shipping Additional Information
     */
    public function getShippingAdditionalInformation();

    /**
     * Sets shipping Additional Information
     *
     * @param string $shippingAdditionalInformation
     * @return string|null Shipping Additional Information
     */
    public function setShippingAdditionalInformation($shippingAdditionalInformation);
}