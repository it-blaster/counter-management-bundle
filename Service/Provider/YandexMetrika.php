<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;


use ItBlaster\CounterManagementBundle\Model\WebCounterQuery;
use Yandex\Metrica\Management\ManagementClient;
use Yandex\Metrica\Management\Models\Counter;
use Yandex\OAuth\OAuthClient;

class YandexMetrika implements CounterProviderInterface {

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

    public function create($parameters = array())
    {
        // make auth
//        $this->client->requestAccessToken($parameters['code']);

        // prepare counter
        $counter = new Counter();
        $counter->setSite('profitbase.ru');
        $counter->setName('Generated Counter');

//        $accessToken = $this->client->getAccessToken();
//        var_dump($accessToken);
        // add counter
        $managementClient = new ManagementClient('7326119c49634e62b98749c57cac485d');
        return $managementClient->counters()->addCounter($counter);
    }

    public function generateCode($number)
    {
        return sprintf('var yaCounter%s = new Ya.Metrika({id: %s, enableAll: true});', $number, $number);
    }

    public function getWebCounterList()
    {
        return WebCounterQuery::create()->filterByTypeKey(self::IDENTITY)->find();
    }


}