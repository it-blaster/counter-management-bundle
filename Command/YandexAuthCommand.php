<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.09.15
 * Time: 22:48
 */

namespace ItBlaster\CounterManagementBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yandex\Metrica\Management\ManagementClient;

class YandexAuthCommand extends ContainerAwareCommand {



    protected function configure()
    {
        $this
            ->setName('yandex:auth')
            ->addArgument('code')
            ->setDescription('Return auth token')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $client = $this->getContainer()->get('counter_management.yandex_oauth_client');
        $token = $client->requestAccessToken($input->getArgument('code'));

        var_dump($token);

    }


    }