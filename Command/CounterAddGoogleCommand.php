<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.10.15
 * Time: 12:09
 */

namespace ItBlaster\CounterManagementBundle\Command;


use ItBlaster\CounterManagementBundle\Model\WebCounterGoal;
use ItBlaster\CounterManagementBundle\Model\WebCounterGoalQuery;
use ItBlaster\CounterManagementBundle\Service\Provider\GoogleAnalytics;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CounterAddGoogleCommand  extends ContainerAwareCommand {




    protected function configure()
    {
        $this
            ->setName('counters:google:add')
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $site = $input->getArgument('site');
        $accountId = '66401003';


        $client = $this->getContainer()->get('counter_management.google_api_client');

        $key_file_location = $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/api/google.p12';
        $key = file_get_contents($key_file_location);

        $cred = new \Google_Auth_AssertionCredentials(
            '866193973145-b6qhtut1c19gb38cffeqlltb6e7s8o0s@developer.gserviceaccount.com',
            array(
                'https://www.googleapis.com/auth/analytics.edit',
                'https://www.googleapis.com/auth/analytics',
                'https://www.googleapis.com/auth/analytics.manage.users'
                ),
            $key
        );

        $client->setAssertionCredentials($cred);

        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

//        notasecret
        $service = new \Google_Service_Analytics($client);

        $newProperty =  new \Google_Service_Analytics_Webproperty();
        $newProperty->setName($name);
        $newProperty->setWebsiteUrl($site);


        $property = $service->management_webproperties->insert(
            $accountId,
            $newProperty
        );

        $goals = WebCounterGoalQuery::create()->find();

        /** @var WebCounterGoal $goal */
        foreach($goals as $goal) {
            $gaGoal = new \Google_Service_Analytics_Goal();

            $event = new \Google_Service_Analytics_GoalEventDetails();

            $conditions = new \Google_Service_Analytics_GoalEventDetailsEventConditions();
            $conditions->setType('CATEGORY');
            $conditions->setMatchType('EXACT');
            $conditions->setExpression($goal->getAlias());

            $conditions->setType('ACTION');
            $conditions->setMatchType('EXACT');
            $conditions->setExpression($goal->getAction());

            $event->setUseEventValue(false);
            $event->setEventConditions($conditions);
            $gaGoal->setEventDetails($event);
            $gaGoal->setName($goal->getName());
        }

        $webCounter = new WebCounter();
        $webCounter->setName($name);
        $webCounter->setNumber($property->getId());
        $webCounter->setTypeKey(GoogleAnalytics::IDENTITY);
        $webCounter->save();



    }

}