<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;


use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;
use ItBlaster\CounterManagementBundle\Service\Remote\RemoteSource;

use Yandex\OAuth\OAuthClient;

class YandexMetrika extends BaseCounter {

    const IDENTITY = 'yandex_metrika';

    protected  $client = null;

    function __construct(RemoteSource $remoteSource)
    {
        $this->remoteSource = $remoteSource;
    }

    public function getIdentity()
    {
        return self::IDENTITY;
    }

    public function getName()
    {
        return 'Яндекс.Метрика';
    }

    public function generateCode($number)
    {
        return sprintf('w.yaCounter%s = new Ya.Metrika({id: %s, enableAll: true});', $number, $number);
    }

}