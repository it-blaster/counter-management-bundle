<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.10.15
 * Time: 20:43
 */

namespace ItBlaster\CounterManagementBundle\Service\Provider;


use ItBlaster\CounterManagementBundle\Service\Provider\Base\BaseCounter;

class Persisted extends BaseCounter {

    const IDENTITY = 'persisted';

    public function getIdentity()
    {
        return self::IDENTITY;
    }

    public function getName()
    {
       return 'Другое';
    }

    public function generateCode($number)
    {
        return false;
    }


}