<?php

namespace SR\UpsShip\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $installer = $setup;
            $installer->startSetup();
            foreach (['sales_order', 'quote'] as $tableAlias) {
                $installer->getConnection()
                    ->addColumn($installer->getConnection()->getTableName($tableAlias),
                        'shipping_ups_pickup_id',
                        [
                            'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable'  => true,
                            'length'    => 40,
                            'default'   => null,
                            'comment'   => 'Shipping UPS Pickup ID'
                        ]
                    );
                $installer->getConnection()
                    ->addColumn($installer->getConnection()->getTableName($tableAlias),
                        'shipping_additional_information',
                        [
                            'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable'  => true,
                            'default'   => null,
                            'comment'   => 'Shipping Additional Information'
                        ]
                    );
            }

            foreach (['sales_order_address', 'quote_address'] as $tableAlias) {
                $installer->getConnection()
                    ->dropColumn($installer->getConnection()->getTableName($tableAlias), 'shipping_ups_pickup_id');
                $installer->getConnection()
                    ->dropColumn($installer->getConnection()->getTableName($tableAlias), 'shipping_additional_information');
            }

            $installer->endSetup();
        }
    }
}