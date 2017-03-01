<?php
/**
 * Copyright © 2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Model\Service;

/**
 * Class InsertPickupsShipment
 * @package SR\UpsShip\Model\Service
 */
class InsertPickupsShipment extends AbstractService
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    public $entity;

    /**
     * Returns service location
     *
     * @return string
     */
    protected function _getServiceLocation()
    {
        return 'ShipWebServices/SHipWbService.svc?wsdl';
    }

    /**
     * Prepares and returns request
     */
    protected function _prepareRequest()
    {
        $data = [
            'info' => [
                'NumberOfPackages' => 1,
                'ConsigneeAddress' => [
                    'ContactPerson' => $this->scopeConfig->getValue('general/store_information/name'),
                    'Phone1' => $this->scopeConfig->getValue('general/store_information/phone')
                ],
                'PickupPointID' => $this->getEntity()->getShippingUpsPickupId(),
                'Reference1' => $this->getEntity()->getIncrementId(),
                'UseDefaultShipperAddress' => 'true',
                'Weight' => $this->getEntity()->getWeight()
            ]
        ];

        return $data;
    }

    /**
     * Prepares response to be returned to processor
     *
     * @param $response
     * @return array
     */
    public function readResponse($response)
    {
        if (empty($response)) {
            return [];
        }
        $errorCode = false;
        $errorMessage = false;
        $response = isset($response->{$this->_getRequestMethodName() . 'Result'}) ?
            $response->{$this->_getRequestMethodName() . 'Result'} : new \stdClass();

        if (isset($response->IsSucceeded) && $response->IsSucceeded != 'true' && isset($response->LastError)) {
            $errorMessage = $response->LastError->ErrorMessage . '. ' . $response->LastError->OriginalMessage;
            $errorCode = $response->LastError->ErrorCode;
        } else if ($response->IsSucceeded != 'true') {
            $errorMessage = __('Some error has occurred during the %1 request.', $this->_getRequestMethodName());
        }

        $data = [
            'tracking_numbers' => isset($response->TrackingNumber)
                ? [$response->TrackingNumber]
                : [],
            'error_message' =>  $errorMessage,
            'error_code' => $errorCode
        ];

        return $data;
    }

    /**
     * Returns requested method name
     *
     * @return string
     */
    protected function _getRequestMethodName()
    {
        return 'InsertPickupsShipment';
    }
}