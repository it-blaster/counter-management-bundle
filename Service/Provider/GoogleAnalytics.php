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

    public function create($name, $site, $token)
    {
        $service = new \Google_Service_Analytics($this->client);
        $property = new \Google_Service_Analytics_Webproperty();

        if(isset($parameters['web_site_url'])) {
            $property->setWebsiteUrl($parameters['web_site_url']);
        }

        if(isset($parameters['industry_vertical'])) {
            $property->setIndustryVertical($parameters['industry_vertical']);
        }

        try {
            $response = $service->management_webproperties->insert($this->accountId, $property);
        } catch (\Google_Exception $exception) {

        }

        return $response;
    }

    public function generateCode($number)
    {
        return sprintf('ga("create", "%s", "auto"); ga("send", "pageview");', $number);
    }




}