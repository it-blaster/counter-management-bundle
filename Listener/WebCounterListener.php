<?php


namespace ItBlaster\CounterManagementBundle\Listener;


use ItBlaster\CounterManagementBundle\Model\WebCounter;
use ItBlaster\CounterManagementBundle\Model\WebCounterGoalQuery;

use ItBlaster\CounterManagementBundle\Service\Manager;
use Symfony\Component\EventDispatcher\GenericEvent;

class WebCounterListener
{


    protected $twig = null;

    /**
     * @var Manager
     */
    protected $counter_management_manager;

    /**
     * Constructor
     * @param Manager $counter_management_manager
     * @param \Twig_Environment $twig
     */
    public function __construct(Manager $counter_management_manager, \Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->counter_management_manager = $counter_management_manager;
    }

    public function onPreSave(GenericEvent $event)
    {

        /** @var WebCounter $counter */
        $counter = $event->getSubject();

        $provider = $this->counter_management_manager
            ->getProvider($counter->getTypeKey());

        /** Генерируем код счетчика */
        if ($counter->isRequiredCodeGeneration()) {
            $counter->setCode($provider->generateCode(
                $counter->getNumber()
            ));
        }

        /** Если указан флаг создания счетчика на сервере отправим необходимые запросы */

        if($counter->isNew() && $counter->getPushToRemote() && $provider->getRemoteRepository()) {
            $remoteCounter = $provider->getRemoteRepository()->push($counter->getName(), $counter->getSite());

            $counter->setNumber($remoteCounter->getId());

            $goals = WebCounterGoalQuery::create()
                ->find();

            /** @var WebCounterGoal $goal */
            foreach($goals as $key => $goal)
            {
                $provider->getRemoteRepository()
                    ->addGoal($remoteCounter, $goal->getName(), $goal->getAlias(), $goal->getAction(), $key + 1);
            }
        }
    }

    /**
     * @param WebCounter $counter
     */
    protected function generateCode(WebCounter $counter)
    {
        $provider = $this->counter_management_manager->getProvider($counter->getTypeKey());
        $counter->setCode(
            $provider->generateCode($this->twig->getExtension('counter_management_twig')->counter($this->twig, $counter->getId()))
        );
    }



}