<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;

use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class GoogleAnalytics extends BaseCounter {

    const IDENTITY = 'ga';

    protected $client = null;

    function __construct(\Google_Client $client, $accountId)
    {
        $this->accountId = $accountId;
        $this->client = $client;
    }


    public function getIdentity()
    {
        return self::IDENTITY;
    }

    public function getName()
    {
        return 'Google Analytics';
    }


    public function generateCode($number)
    {
        return sprintf('ga("create", "%s", "auto"); ga("send", "pageview");', $number);
    }




}