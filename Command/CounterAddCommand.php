<?php

namespace ItBlaster\CounterManagementBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CounterAddCommand extends ContainerAwareCommand {


    protected function configure()
    {
        $this
            ->setName('counters:add')
            ->setDescription('Create counter')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'What kind of counter do u want to create?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $counter = $this->getContainer()->get('counter_management.manager')
            ->getProvider('yandex_metrika')->create(array('code' => '5742498'));

        var_dump($counter);
    }
}
