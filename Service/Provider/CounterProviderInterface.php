<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.08.15
 * Time: 21:01
 */

namespace ItBlaster\CounterManagementBundle\Service\Provider;


interface CounterProviderInterface {


    public function getIdentity();

    public function getName();

    public function create($parameters = array());

    public function generateCode($number);

    /**
     * @return mixed|\PropelObjectCollection
     */
    public function getWebCounterList();

}