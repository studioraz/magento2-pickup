<?php
/**
 * Copyright © 2016 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Controller\Adminhtml\Order;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use SR\UpsShip\Model\Carrier\UpsShip;

/**
 * Class WbLabels
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WbLabels extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $collection;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        FileFactory $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
    }

    /**
     * Print WB labels for selected orders
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    protected function massAction(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection)
    {
        $this->_prepareCollection($collection);

        if (!$this->collection->getSize()) {
            $this->messageManager->addError(__('There are no printable labels related to selected orders.'));
            return $this->resultRedirectFactory->create()->setPath($this->getComponentRefererUrl());
        }

        $json = $this->_prepareDataResponse();

        return $this->fileFactory->create(
            'orders.ship',
            $json,
            DirectoryList::VAR_DIR,
            'application/json'
        );
    }

    /**
     * Prepares order collection
     *
     * @param $collection
     */
    protected function _prepareCollection($collection)
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create()
                ->addFieldToFilter('entity_id', ['in' => $collection->getAllIds()])
                ->addFieldToFilter('shipping_method',
                    UpsShip::UPS_SHIP_CARRIER_CODE . '_' . UpsShip::UPS_SHIP_PICKUP_METHOD_CODE);
            ;

            $billingTableAliasName = 'billing_o_a';
            $this->collection->getSelect()
                ->joinLeft(
                    [$billingTableAliasName => $collection->getTable('sales_order_address')],
                    "(main_table.entity_id = {$billingTableAliasName}.parent_id" .
                    " AND {$billingTableAliasName}.address_type = 'billing')"
                );
            $itemsTableAliasName = 'o_i';
            $this->collection->getSelect()
                ->joinLeft(
                    [$itemsTableAliasName => $collection->getTable('sales_order_item')],
                    "(main_table.entity_id = {$itemsTableAliasName}.order_id" .
                    " AND {$itemsTableAliasName}.parent_item_id IS NULL)",
                    [
                        'sum_weight' => 'SUM(o_i.weight * (qty_ordered - qty_canceled))',
                        'sum_qty' => 'SUM(qty_ordered - qty_canceled)'
                    ]
                );

            $this->collection->getSelect()->group('main_table.entity_id');
        }
    }

    /**
     * Prepares data
     *
     * @param bool $asJson
     * @return array|string
     */
    protected function _prepareDataResponse($asJson = true)
    {
        $data = [];
        $i = 1;
        foreach ($this->collection as $order) {
            $data['Orders'][$i]['ConsigneeAddress']['City'] = $order->getCity();
            $data['Orders'][$i]['ConsigneeAddress']['ContactName'] = $order->getFirstname() . ' ' . $order->getLastname();
            $data['Orders'][$i]['ConsigneeAddress']['HouseNumber'] = $order->getHomeNumber();
            $data['Orders'][$i]['ConsigneeAddress']['PhoneNumber'] = $order->getTelephone();
            $data['Orders'][$i]['ConsigneeAddress']['Street'] = $order->getStreet();
            $data['Orders'][$i]['ConsigneeAddress']['LocationDescription'] = ''; //@todo what is this?
            $data['Orders'][$i]['ConsigneeAddress']['Email'] = $order->getEmail();
            $data['Orders'][$i]['PKP']= $order->getShippingAdditionalInformation();
            $data['Orders'][$i]['OrderID']= $order->getIncrementId();
            $data['Orders'][$i]['Weight']= (float)$order->getSumWeight();
            $data['Orders'][$i]['NumberOfPackages'] = (int)$order->getSumQty();
            $i++;
        }

        if ($asJson) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $data;
    }
}
