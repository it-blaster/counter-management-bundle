<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;

use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;
use ItBlaster\CounterManagementBundle\Service\Remote\RemoteSource;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class GoogleAnalytics extends BaseCounter {

    const IDENTITY = 'ga';

    protected $client = null;

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
        return 'Google Analytics';
    }


    public function generateCode($number)
    {
        return;
    }




}