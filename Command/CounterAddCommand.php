<?php

namespace ItBlaster\CounterManagementBundle\Command;

use ItBlaster\CounterManagementBundle\Model\WebCounter;
use ItBlaster\CounterManagementBundle\Service\Provider\YandexMetrika;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CounterAddCommand extends ContainerAwareCommand {


    protected function configure()
    {
        $this
            ->setName('counters:yandex:add')
            ->setDescription('Create counter')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Choose name for your counter?'
            )
            ->addArgument(
                'site',
                InputArgument::OPTIONAL,
                'Please enter site domain'
            )
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'What kind of counter do u want to create?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $name = $input->getArgument('name');
        $site = $input->getArgument('site');

        $webCounter = new WebCounter();
        $webCounter->setName($name);
        $webCounter->setSite($site);
        $webCounter->setPushToRemote(true);
        $webCounter->setTypeKey(YandexMetrika::IDENTITY);
        $webCounter->save();

    }
}
