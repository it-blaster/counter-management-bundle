<?php

namespace ItBlaster\CounterManagementBundle\Service;

use ItBlaster\CounterManagementBundle\Service\Provider\CounterProviderInterface;

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

    public function getProvidersChoices()
    {
        $choices = array();
        /** @var CounterProviderInterface $provider */
        foreach ($this->getProviders() as $provider) {
            $choices[$provider->getIdentity()] = $provider->getName();
        }

        return $choices;
    }

    /**
     * @param $identity
     * @return CounterProviderInterface
     */
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