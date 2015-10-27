<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.10.15
 * Time: 20:53
 */

namespace ItBlaster\CounterManagementBundle\Controller;

use ItBlaster\CounterManagementBundle\Model\WebCounterGoalQuery;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class WebCounterAdminController extends CRUDController {

    public function installGoalsAction($id, Request $request) {

        $object = $this->admin->getObject($id);

        $provider = $this->get('counter_management.manager')
            ->getProvider($object->getTypeKey());

        if(!$object->isNew() && $object->getNumber() && $provider->getRemoteRepository()) {


            $goals = WebCounterGoalQuery::create()
                ->find();

            $counter = $provider->getRemoteRepository()->getCounter($object->getNumber());

            /** @var WebCounterGoal $goal */
            foreach($goals as $key => $goal)
            {
                if($provider->getRemoteRepository()->hasGoal($counter, $goal) === false)  {
                    $provider->getRemoteRepository()
                        ->addGoal($counter, $goal->getName(), $goal->getAlias(), $goal->getAction(), $key + 1);
                }
            }

        }

        if(true) {
            $request->getSession()->getFlashBag()->add("success", "Все цели установлены");
        } else {
            $request->getSession()->getFlashBag()->add("error", "Не удалось установить цели");
        }

        return $this->redirectTo($object);
    }


}