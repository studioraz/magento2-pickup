<?php
/**
 * Copyright © 2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Model\Service;

/**
 * Class AbstractService
 * @package SR\UpsShip\Model\Service
 */
abstract class AbstractService
{
    const AUTH_COOKIE_NAME = '.ASPXFORMSAUTH';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \SR\UpsShip\Model\Client $client,
        \SR\UpsShip\Logger\UpsShip $logger,
        \Magento\Framework\Message\Manager $manager
    )
    {
        $this->scopeConfig = $context->getScopeConfig();
        $this->client = $client;
        $this->message = $manager;
        $this->logger = $logger;
        $this->urlPrefix = $this->getConfigValue('general/is_live_mode')
            ? $this->getConfigValue('general/service_domain_prefix_live')
            : $this->getConfigValue('general/service_domain_prefix_dev');
    }

    /**
     * Performs login and returns auth cookie value if any
     *
     * @todo add as a separate service
     * @return bool|string
     */
    protected function _login()
    {
        $authCookie = false;
        $loginServiceName = 'Login';
        $loginData = [
            'username' => $this->getConfigValue('general/customer_number') . '.' . $this->getConfigValue('general/login'),
            'password' => $this->getConfigValue('general/password')
        ];
        $loginServiceUrl = $this->urlPrefix . $this->_getLoginServiceLocation();
        try {
            $client = $this->client->getSoapClient($loginServiceUrl);
            $response = $client->__soapCall($loginServiceName, [$loginData]);
            $this->logger->info($client->__getLastRequest());
            $this->logger->info($client->__getLastResponse());
            if (isset($response->{$loginServiceName . 'Result'}) && $response->{$loginServiceName . 'Result'} == 'true') {
                $authCookie = $client->_cookies[self::AUTH_COOKIE_NAME][0];
            }

        } catch(\Exception $e) {
            $this->logger->info($e->getMessage());
            $this->message->addError($e->getMessage());
        }

        return $authCookie;
    }

    /**
     * executes the request, main entry point of the process
     */
    public function execute()
    {
        $response = [];
        $client = null;

        if ($authCookie = $this->_login()) {
            try {
                $client = $this->client->getSoapClient($this->_getServiceUrl());
                $client->__setCookie(self::AUTH_COOKIE_NAME, $authCookie);
                $request = $this->_prepareRequest();
                $response = $client->__soapCall($this->_getRequestMethodName(), [$request]);
            } catch(\Exception $e) {
                $this->logger->info($e->getMessage());
                $this->message->addError($e->getMessage());
            }
            if ($client) {
                $this->logger->info($client->__getLastRequest());
                $this->logger->info($client->__getLastResponse());
            }
        }

        return $this->readResponse($response);
    }

    /**
     * Prepares and returns request
     *
     * @return array
     */
    abstract protected function _prepareRequest();

    /**
     * process the services response (save return values, log successful connection, etc)
     * should be implemented in child class
     *
     * @param $response
     * @return mixed
     */
    abstract public function readResponse($response);

    /**
     * Returns service location
     *
     * @return string
     */
    abstract protected function _getServiceLocation();

    /**
     * Returns requested method name
     *
     * @return string
     */
    abstract protected function _getRequestMethodName();

    /**
     * Config value of the corresponding carrier
     *
     * @param $configName
     * @return mixed
     */
    public function getConfigValue($configName)
    {
        return $this->scopeConfig->getValue('upsship/' . $configName);
    }

    /**
     * Returns login service location
     *
     * @return string
     */
    protected function _getLoginServiceLocation()
    {
        return 'ShipWebServices/AuthenticationService.svc?wsdl';
    }

    /**
     * Sets service Url
     *
     * @return string
     */
    protected function _getServiceUrl()
    {
        return $this->urlPrefix . $this->_getServiceLocation();
    }

    /**
     * Sets an entity to be processed
     *
     * @param $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
