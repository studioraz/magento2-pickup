<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Model\Order;

use Magento\Sales\Model\Order\Address as OrderAddress;

/**
 * Class Address
 * @package SR\UpsShip\Model\Order
 */
class Address extends OrderAddress
{
    const SHIPPING_UPS_PICKUP_ID = 'shipping_ups_pickup_id';
    const SHIPPING_ADDITIONAL_INFORMATION = 'shipping_additional_information';

    public function setShippingUpsPickupId($shippingUpsPickupId)
    {
        return $this->setData(self::SHIPPING_UPS_PICKUP_ID, $shippingUpsPickupId);
    }

    public function setShippingAdditionalInformation($shippingUpsPickupId)
    {
        return $this->setData(self::SHIPPING_ADDITIONAL_INFORMATION, $shippingUpsPickupId);
    }

    public function getShippingUpsPickupId()
    {
        return $this->getData(self::SHIPPING_UPS_PICKUP_ID);
    }

    public function getShippingAdditionalInformation()
    {
        return $this->getData(self::SHIPPING_ADDITIONAL_INFORMATION);
    }
}
