<?php
/**
 * SR Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * https://wiki.studioraz.co.il/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@studioraz.com so we can send you a copy immediately.
 *
 * @package     SR_SocialLogin
 * @copyright   Copyright (c) 2015 SR Inc. (https://studioraz.co.il)
 * @license     https://wiki.studioraz.co.il/wiki/EULA  End-user License Agreement
 */

namespace SR\UpsShip\Helper;

use  \Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const PICKUP_API_JS_URL_FORMANT = 'https://pick-ups.co.il/api/ups-pickups.sdk.%s.js?r=2.0';
    /**
     * Get pickup location type (stores & lockers, stores, lockers)
     * @return string
     */
    public function getPickupLocationType()
    {
        return $this->scopeConfig->getValue(
            'carriers/upsship/pickup_location_type',
            ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getPickupLocationJsUrl()
    {
        return sprintf(self::PICKUP_API_JS_URL_FORMANT, $this->getPickupLocationType());
    }

}