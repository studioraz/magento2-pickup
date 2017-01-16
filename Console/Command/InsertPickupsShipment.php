<?php
/**
 * Copyright © 2017 Studio Raz. All rights reserved.
 * For more information contact us at dev@studioraz.co.il
 * See COPYING_STUIDRAZ.txt for license details.
 */
namespace SR\UpsShip\Console\Command;

use SR\UpsShip\Model\Service\InsertPickupsShipment as InsertPickupsShipmentService;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InsertPickupsShipment
 * @package SR\UpsShip\Console\Command
 */
class InsertPickupsShipment extends Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    protected $model;

    /**
     * OrderProcessCommand constructor.
     * @param State $appState
     * @param InsertPickupsShipmentService $model
     * @param \Magento\Sales\Model\Order $order
     */
    public function __construct(
        State $appState,
        InsertPickupsShipmentService $model,
        \Magento\Sales\Model\Order $order
    ) {
        $this->appState = $appState;
        $this->model = $model;
        $this->order = $order;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('studioraz:upsship:insert_pickups_shipment')
            ->setDescription('Tries to get tracking numbers.')
            ->setDefinition([]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->order->load(29);
        $this->appState->setAreaCode('frontend');
        var_dump($this->model->setEntity($this->order)->execute());
    }
}
