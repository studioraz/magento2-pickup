<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */

namespace SR\UpsShip\Block\Paypal\Review;

/**
 * Class UpsShip
 * @package SR\UpsShip\Block\Paypal\Review
 */
class UpsShip extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data)
    {
        $this->quote = $checkoutSession->getQuote();
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    /**
     * Return shipping address as array
     *
     * @return array
     */
    public function getShippingAddress()
    {
        return \Magento\Framework\Convert\ConvertArray::toFlatArray($this->quote->getShippingAddress()->getData());
    }
}