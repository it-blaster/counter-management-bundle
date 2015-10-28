<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider\Base;

use ItBlaster\CounterManagementBundle\Model\WebCounterQuery;

abstract class BaseCounter
{

    protected $remoteSource = null;

    abstract public function getIdentity();

    abstract public function getName();

    abstract public function generateCode($number);

    public function getRemoteRepository()
    {
        return $this->remoteSource;
    }

    /**
     * @return mixed|\PropelObjectCollection
     */
    public function getWebCounterList()
    {
        return WebCounterQuery::create()
                 ->filterByTypeKey($this->getIdentity())->find();
    }

}