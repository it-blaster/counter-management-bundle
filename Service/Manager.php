<?php

namespace ItBlaster\CounterManagementBundle\Service;

use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;

class Manager
{

    protected $providers = array();

    public function addProvider(BaseCounter $provider)
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
        /** @var BaseCounter $provider */
        foreach ($this->getProviders() as $provider) {
            $choices[$provider->getIdentity()] = $provider->getName();
        }

        return $choices;
    }

    /**
     * @param $identity
     * @return BaseCounter
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