<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Translate\Adapter;

/**
 * Class InstallSchema
 * @package SR\UpsShip\Setup
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ){
        $installer = $setup;
        $installer->startSetup();
        foreach (['sales_order_address', 'quote_address'] as $tableAlias) {
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
        $installer->endSetup();
    }
}