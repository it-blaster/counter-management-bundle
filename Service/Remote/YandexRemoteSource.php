<?php

namespace ItBlaster\CounterManagementBundle\Service\Remote;

use Yandex\Metrica\Management\ManagementClient;
use Yandex\Metrica\Management\Models\Condition;
use Yandex\Metrica\Management\Models\Conditions;
use Yandex\Metrica\Management\Models\Counter;
use Yandex\Metrica\Management\Models\CounterParams;
use Yandex\Metrica\Management\Models\Goal;

class YandexRemoteSource  extends  RemoteSource {

    protected $manager;

    /** @var  ManagementClient */
    protected $client;

    function __construct($client)
    {
        $this->client = $client;
    }


    public function push($name, $site) {

        return $this->pushCounter($name, $site);

    }


    public function pushCounter($name, $site) {

        $counter = new Counter();

        $counter->setSite($site);
        $counter->setName($name);


        /** @var Counter $savedCounter */
        $counter = $this->client->counters()->addCounter($counter);

        return $counter;
    }

    public function addGoal($counter, $name, $url, $action = null, $id = null) {

        $goal = new Goal();
        $goal->setType('action');
        $goal->setName($name);
        $goal->setClass(0);

        $conditions = new Conditions();

        $condition = new Condition();
        $condition->setType('exact');
        $condition->setUrl($url);

        $conditions->add($condition);

        $goal->setConditions($conditions);

        /** @var Counter $counter */
        $this->client->goals()->addGoal($counter->getId(), $goal);

    }



    public function getCounter($id)
    {
        return $this->client->counters()->getCounter($id, new CounterParams());
    }


    public function getGoals($counter)
    {
        return $this->client->goals()->getGoals($counter->getId());
    }

    public function hasGoal($counter, $goal)
    {
        $goals =  $this->client->goals()->getGoals($counter->getId());

        /** @var Goal $remote */
        foreach($goals as $remote) {
            $conditions = $remote->getConditions() ? $remote->getConditions()->getAll() : array();

            /** @var Condition $condition */
            foreach($conditions as $condition) {
                if($condition->getUrl() == $goal->getAlias()) {
                    return true;
                }
            }
        }

        return false;
    }


}