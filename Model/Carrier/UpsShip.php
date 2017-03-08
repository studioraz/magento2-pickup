<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

/**
 * Class UpsShip
 * @package SR\UpsShip\Model\Carrier
 */
class UpsShip extends \Magento\Shipping\Model\Carrier\AbstractCarrierOnline implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    protected $_code = 'upsship';
    protected $_rateMethodFactory;
    protected $_rateResultFactory;
    protected $_trackingResultFactory;
    const UPS_SHIP_CARRIER_CODE = 'upsship';
    const UPS_SHIP_PICKUP_METHOD_CODE = 'pickup';

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Xml\Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_trackingResultFactory = $trackFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [self::UPS_SHIP_PICKUP_METHOD_CODE => 'Store pickup'];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod(self::UPS_SHIP_PICKUP_METHOD_CODE);
        $method->setMethodTitle($this->getConfigData('title'));

        $freeShippingLimit = (int)$this->getConfigData('free_shipping_limit');
        $price = ($freeShippingLimit > 0 && $request->getPackageValueWithDiscount() >= $freeShippingLimit)
            ? 0
            : $this->getConfigData('price');

        $method->setPrice($price);
        $method->setCost($price);

        $result->append($method);

        return $result;
    }

    /**
     *  Retrieve sort order of current carrier
     *  Shipping line must be last one as the picker button is located at the additional block
     * @return string|null
     */
    public function getSortOrder()
    {
        return 999;
    }

    public function isTrackingAvailable()
    {
        return true;
    }

    protected function _doShipmentRequest(\Magento\Framework\DataObject $request)
    {
        return false;
    }

    public function getTracking($tracking)
    {
        $result = $this->_trackFactory->create();

        $error = $this->_trackErrorFactory->create();
        $error->setCarrier($this->_code);
        $error->setCarrierTitle($this->getConfigData('title'));
        $error->setTracking($tracking);

        // not displayed anywhere, though needs to be set
        $error->setErrorMessage(true);
        $result->append($error);

        return $result;
    }

    public function proccessAdditionalValidation(\Magento\Framework\DataObject $request)
    {
        return $this;
    }
}