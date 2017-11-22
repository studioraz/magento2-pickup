<?php

namespace SR\UpsShip\Model\ResourceModel\Sales\Order;

use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Api\Data\OrderInterface;
use SR\UpsShip\Api\Data\Sales\Order\ExtraAttributesInterface;

class ExtraAttributes
{
     /** @var  ResourceConnection\ */
    private $resourceConnection;

    /**
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct
    (
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     *
     * @param integer $orderId
     * @return array
     */
    public function getExtraAttributesByOrderId($orderId)
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select();

        $select->from($this->resourceConnection->getTableName('sales_order'), [
            ExtraAttributesInterface::SHIPPING_UPS_PICKUP_ID,
            ExtraAttributesInterface::SHIPPING_ADDITIONAL_INFORMATION,
        ])
        ->where(OrderInterface::ENTITY_ID . ' = ?', $orderId);

        $row = $connection->fetchRow($select);
        return $row ?: [];
    }
}