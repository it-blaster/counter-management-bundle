<?php

namespace ItBlaster\CounterManagementBundle\Command;

use ItBlaster\CounterManagementBundle\Model\WebCounter;
use ItBlaster\CounterManagementBundle\Model\WebCounterGoal;
use ItBlaster\CounterManagementBundle\Model\WebCounterGoalQuery;
use ItBlaster\CounterManagementBundle\Service\Provider\YandexMetrika;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yandex\Metrica\Management\ManagementClient;
use Yandex\Metrica\Management\Models\Condition;
use Yandex\Metrica\Management\Models\Conditions;
use Yandex\Metrica\Management\Models\Counter;
use Yandex\Metrica\Management\Models\Goal;


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

        $token = $this->getContainer()->getParameter('yandex_metrika_token');


        /** @var Counter $counter */
        $counter = $this->getContainer()->get('counter_management.manager')
            ->getProvider('yandex_metrika')->create($name, $site, $token);


        $goals = WebCounterGoalQuery::create()->find();

        /** @var WebCounterGoal $goal */
        foreach($goals as $goal) {

            $remoteGoal = new Goal();
            $remoteGoal->setType('action');
            $remoteGoal->setName($goal->getName());
            $remoteGoal->setClass(0);

            $conditions = new Conditions();

            $condition = new Condition();
            $condition->setType('exact');
            $condition->setUrl($goal->getAlias());


            $conditions->add($condition);

            $remoteGoal->setConditions($conditions);

            $managementClient = new ManagementClient($token);

            /** @var Counter $savedCounter */
            $managementClient->goals()->addGoal($counter->getId(), $remoteGoal);

        }

        $webCounter = new WebCounter();
        $webCounter->setName($name);
        $webCounter->setNumber($counter->getId());
        $webCounter->setTypeKey(YandexMetrika::IDENTITY);
        $webCounter->save();



        var_dump($counter);
    }
}
