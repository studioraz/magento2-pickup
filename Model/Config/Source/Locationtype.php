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

namespace SR\UpsShip\Model\Config\Source;

class Locationtype implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'all',
                'label' => __('Stores & Lockers')
            ],
            [
                'value' => 'stores',
                'label' => __('Stores Only')
            ],
            [
                'value' => 'lockers',
                'label' => __('Lockers Only')
            ]
        ];
    }


}
