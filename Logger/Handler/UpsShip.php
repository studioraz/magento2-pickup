<?php
/**
 * Copyright © 2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Logger\Handler;

/**
 * Class UpsShip
 * @package SR\UpsShip\Logger\Handler
 */
class UpsShip extends \Magento\Framework\Logger\Handler\Base
{
    protected $fileName = '/var/log/upsship.log';
    protected $loggerType = \Monolog\Logger::INFO;
}