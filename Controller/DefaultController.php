<?php

namespace ItBlaster\CounterManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {

        return $this->render('ItBlasterCounterManagementBundle:Default:index.html.twig', array('name' => $name));
    }
}
