<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;


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
        $this->client->requestAccessToken($parameters['code']);

        // prepare counter
        $counter = new Counter();
        $counter->setName('Generated Counter');

        // add counter
        $managementClient = new ManagementClient($this->client->getAccessToken());
        return $managementClient->counters()->addCounter($counter);
    }

    public function generateCode($number)
    {
        return sprintf('Yandex Metrika code %s', $number);
    }


}