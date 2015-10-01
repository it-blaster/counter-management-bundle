<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;


use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Tests\Console\Descriptor\ExtendedCallableClass;
use Yandex\Metrica\Management\ManagementClient;
use Yandex\Metrica\Management\Models\Counter;
use Yandex\Metrica\Management\Models\ExtendCounter;
use Yandex\Metrica\Management\Models\Goal;
use Yandex\OAuth\OAuthClient;

class YandexMetrika extends BaseCounter {

    const IDENTITY = 'yandex_metrika';

    protected  $client = null;

    function __construct(OAuthClient $client)
    {
        $this->client = $client;
    }

    public function getIdentity()
    {
        return self::IDENTITY;
    }

    public function getName()
    {
        return 'Яндекс.Метрика';
    }


    /**
     * @param $name
     * @param $site
     * @param $token
     * @return  Counter
     */
    public function create($name, $site, $token)
    {
        // $token = '7326119c49634e62b98749c57cac485d';

        // prepare counter
        $counter = new Counter();

        $counter->setSite($site);
        $counter->setName($name);


        $managementClient = new ManagementClient($token);
        /** @var Counter $savedCounter */
        $savedCounter = $managementClient->counters()->addCounter($counter);

        $goals = array();

        foreach($goals as $goal)
        {
            $managementClient->goals()->addGoal($savedCounter->getId(), $goal);
        }

        return $savedCounter;
    }

    public function generateCode($number)
    {
        return sprintf('w.yaCounter%s = new Ya.Metrika({id: %s, enableAll: true});', $number, $number);
    }

}