<?php

namespace ItBlaster\CounterManagementBundle\Service\Provider\Base;

use ItBlaster\CounterManagementBundle\Model\WebCounterQuery;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

abstract class BaseCounter
{

    abstract public function getIdentity();

    abstract public function getName();

    abstract public function create($parameters = array());

    abstract public function generateCode($number);


    /**
     * @return mixed|\PropelObjectCollection
     */
    public function getWebCounterList()
    {
        return WebCounterQuery::create()->filterByTypeKey($this->getIdentity())->find();
    }

}