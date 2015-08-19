<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider;

class GoogleAnalytics implements CounterProviderInterface {

    const INDENTITY = 'ga';

    protected $client = null;

    function __construct(\Google_Client $client, $accountId)
    {
        $this->accountId = $accountId;
        $this->client = $client;
    }


    public function getIdentity()
    {
        return self::INDENTITY;
    }

    public function getName()
    {
        return 'Google Analytics';
    }

    public function create($parameters = array())
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
        return sprintf('Google Analytics code %s', $number);
    }


}