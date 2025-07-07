<?php
/**
 * Copyright © 2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Model;

/**
 * Class Client
 * @package SR\UpsShip\Model
 */
class Client
{
    protected $_client;

    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * Creates and returns SoapClient
     *
     * @param $wsdl
     * @return \SoapClient
     */
    public function getSoapClient($wsdl)
    {
        $client = new \SoapClient($wsdl, [
            'trace' => 1,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'connection_timeout' => 10
        ]);

        return $client;
    }
}
