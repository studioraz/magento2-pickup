<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Block\Adminhtml\Order\View;

use SR\UpsShip\Model\Carrier\UpsShip;

/**
 * Class Ups
 * @package SR\UpsShip\Block\Adminhtml\Order\View
 */
class Ups extends \Magento\Sales\Block\Adminhtml\Order\AbstractOrder
{
    protected $_shippingMethod;

    public function getShippingMethod()
    {
        if (!$this->_shippingMethod) {
            $this->_shippingMethod = $this->getOrder()->getShippingMethod(true);
        }

        return $this->_shippingMethod;
    }

    /**
     * Returns flag is block can be shown
     *
     * @return bool
     */
    public function canShow()
    {
        if ($this->getShippingMethod()->getCarrierCode() == UpsShip::UPS_SHIP_CARRIER_CODE
            && $this->getShippingMethod()->getMethod() == UpsShip::UPS_SHIP_PICKUP_METHOD_CODE
        )
        {
            return true;
        }

        return false;
    }

    /**
     * Returns UPS Ship Pickup Location ID
     *
     * @return mixed
     */
    public function getUpsLocationId()
    {
        return $this->getOrder()->getShippingUpsPickupId();
    }

    /**
     * Returns UPS Ship Pickup Location Details
     *
     * @param bool $asArray
     * @return array|mixed|string
     */
    public function getUpsLocationDetails($asArray = true)
    {
        try {
            $details = \Zend_Json::decode($this->getOrder()->getShippingAdditionalInformation());
            foreach(array('lat', 'lng', 'dist', 'iid') as $skip) {
                unset($details[$skip]);
            }
        } catch (\Exception $e) {
            $details = array();
        }
        if ($asArray) {
            return $details;
        }

        return implode(', ', $details);
    }
}