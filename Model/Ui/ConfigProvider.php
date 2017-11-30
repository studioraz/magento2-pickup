<?php

namespace SR\UpsShip\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use SR\UpsShip\Helper\Data as HelperData;

class ConfigProvider implements ConfigProviderInterface
{

    /**
     * @var HelperData
     */
    protected $helperData;

    public function __construct(
        HelperData $helperData
    )
    {
        $this->helperData = $helperData;
    }


    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(){
        return [
            'upship' => [
                'location_js_url' => $this->helperData->getPickupLocationJsUrl()
            ],
        ];
    }
}