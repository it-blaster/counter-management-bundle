<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.15
 * Time: 15:27
 */

namespace ItBlaster\CounterManagementBundle\Service\Remote;


class GoogleRemoteSource extends RemoteSource
{

    protected $account_id;
    protected $serviceAccount;
    protected $client;
    protected $pathToKey;

    protected $service = null;

    function __construct($account_id, $serviceAccount, $client, $pathToKey)
    {
        $this->account_id = $account_id;
        $this->serviceAccount = $serviceAccount;
        $this->client = $client;
        $this->pathToKey = $pathToKey;
    }


    public function getService()
    {
        if ($this->service === null) {
            $this->service = new \Google_Service_Analytics($this->client);
        }

        return $this->service;
    }


    public function push($name, $site)
    {

        $this->authenticate();


        $property = new \Google_Service_Analytics_Webproperty();
        $property->setName($name);
        $property->setWebsiteUrl('http://' . $site);


        $property = $this->getService()->management_webproperties->insert(
            $this->account_id,
            $property
        );

        $profile = new \Google_Service_Analytics_Profile();
        $profile->setName('Все данные по веб-сайту');

        $profile = $this->getService()->management_profiles->insert($this->account_id, $property->getId(), $profile);

        $property->setDefaultProfileId($profile->getId());

        return $property;

    }


    public function addGoal($counter, $name, $url, $action = null, $id = null)
    {
        $categoryConditions = new \Google_Service_Analytics_GoalEventDetailsEventConditions();
        $categoryConditions->setType('CATEGORY');
        $categoryConditions->setMatchType('EXACT');
        $categoryConditions->setExpression($url);

        $actionConditions = new \Google_Service_Analytics_GoalEventDetailsEventConditions();
        $actionConditions->setType('ACTION');
        $actionConditions->setMatchType('EXACT');
        $actionConditions->setExpression($action);

        $event = new \Google_Service_Analytics_GoalEventDetails();
        $event->setUseEventValue(false);
        $event->setEventConditions(array($categoryConditions, $actionConditions));

        $goal = new \Google_Service_Analytics_Goal();
        $goal->setId($id);
        $goal->setActive(true);
        $goal->setName($name);
        $goal->setType('EVENT');


        $goal->setEventDetails($event);

        $goal = $this->getService()->management_goals->insert($this->account_id, $counter->getId(), $counter->getDefaultProfileId(), $goal);

        return $goal;

    }


    /**
     * @param $id
     * @return \Google_Service_Analytics_Webproperty
     */
    public function getCounter($id)
    {
        $counter = null;

        $this->authenticate();

        $accounts = $this->getService()->management_accounts->listManagementAccounts();
        foreach ($accounts as $account) {

            try {
                $counter = $this->getService()
                    ->management_webproperties->get($account->getId(), $id);
            } catch (\Google_Service_Exception $e) {

            }
        }


        return $counter;
    }

    /**
     * @param \Google_Service_Analytics_Webproperty $counter
     * @return \Google_Service_Analytics_Goals
     */
    public function getGoals($counter)
    {
        $this->authenticate();

        $goals = $this->getService()
            ->management_goals->listManagementGoals($counter->getAccountId(), $counter->getId(), $counter->getDefaultProfileId());

        return $goals;
    }

    protected function authenticate()
    {

        $key_file_location = $this->pathToKey;
        $key = file_get_contents($key_file_location);

        $cred = new \Google_Auth_AssertionCredentials(
            $this->serviceAccount,
            array(
                'https://www.googleapis.com/auth/analytics.edit',
                'https://www.googleapis.com/auth/analytics',
                'https://www.googleapis.com/auth/analytics.manage.users'
            ),
            $key
        );

        $this->client->setAssertionCredentials($cred);

        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion();
        }
    }

    public function hasGoal($counter, $goal)
    {
        return false;
    }


}