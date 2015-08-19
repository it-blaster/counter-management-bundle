<?php

namespace ItBlaster\CounterManagementBundle\Service;

class Manager
{

    protected $providers = array();

    function __construct($providers = array())
    {
    }


    public function addProvider(CounterProviderInterface $provider)
    {
        $this->providers[$provider->getIdentity()] = $provider;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    public function getProvider($identity)
    {
        if($this->hasProvider($identity) === false) {
            throw new \InvalidArgumentException(sprintf('Undefined provider %s', $identity));
        }

        return $this->providers[$identity];
    }


    public function hasProvider($identity)
    {
        return isset($this->providers[$identity]);
    }




}