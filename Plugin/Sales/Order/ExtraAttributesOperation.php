<?php

namespace SR\UpsShip\Plugin\Sales\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use SR\UpsShip\Model\ResourceModel\Sales\Order\ExtraAttributes as ExtraAttributesResourceModel;
use SR\UpsShip\Model\Sales\Order\ExtraAttributesFactory;

class ExtraAttributesOperation
{
    /**
     * @var ExtraAttributesResourceModel
     */
    protected $resourceModel;

    /**
     * @var OrderInterface
     */
    protected $orderExtensionFactory;

    /**
     * @var ExtraAttributesResourceModel
     */
    protected $extraAttributesFactory;

    /**
     * 
     * @param OrderInterface $orderExtensionFactory
     * @param ExtraAttributesFactory $extraAttributesFactory
     * @param ExtraAttributesResourceModel $resourceModel
     */
    public function __construct
    (
        OrderInterface $orderExtensionFactory,
        ExtraAttributesFactory $extraAttributesFactory,
        ExtraAttributesResourceModel $resourceModel
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->extraAttributesFactory = $extraAttributesFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * Adds Order Extra Attributes on Order get-action
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $entity
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $entity) {
        $this->addExtraAttributesToOrder($entity);
        return $entity;
    }

    /**
     * Adds Order Extra Attributes on Order getLis-action
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $searchResult
     * @return \Magento\Framework\Api\SearchResults
     */
    public function afterGetList
    (
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Framework\Api\SearchResultsInterface $searchResult
    ) {
        /** @var OrderInterface $order */
        foreach ($searchResult->getItems() as $order) {
            $this->addExtraAttributesToOrder($order);
        }
        return $searchResult;
    }

    /**
     * @param OrderInterface $order
     * @return self
     */
    protected function addExtraAttributesToOrder(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        //@todo: implemente such logic via Repository and Registry patterns
        $extraAttr = $this->extraAttributesFactory->create();
        $extraAttr->setData($this->resourceModel->getExtraAttributesByOrderId($order->getId()));

        $extensionAttributes->setShippingUpsPickupId($extraAttr->getShippingUpsPickupId());
        $extensionAttributes->setShippingAdditionalInformation($extraAttr->getShippingAdditionalInformation());

        $order->setExtensionAttributes($extensionAttributes);
        return $this;
    }
}