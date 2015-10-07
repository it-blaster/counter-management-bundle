<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.15
 * Time: 21:38
 */

namespace ItBlaster\CounterManagementBundle\Service\Remote;

abstract class RemoteSource {

    abstract public function push($name, $site);


    abstract public function addGoal($counter, $name, $url, $action = null, $id = null);
}