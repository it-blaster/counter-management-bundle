<?php


namespace ItBlaster\CounterManagementBundle\Listener;


use ItBlaster\CounterManagementBundle\Model\WebCounter;
use ItBlaster\CounterManagementBundle\Model\WebCounterGoalQuery;
use ItBlaster\CounterManagementBundle\Model\WebCounterPeer;
use ItBlaster\CounterManagementBundle\Service\Manager;
use Symfony\Component\EventDispatcher\GenericEvent;

class WebCounterListener
{


    /**
     * @var Manager
     */
    protected $counter_management_manager;

    /**
     * WebCounterListener constructor.
     * @param Manager $counter_management_manager
     */
    public function __construct(Manager $counter_management_manager)
    {
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
            $remoteCounter = $provider->getRemoteRepository()->push($counter);

            $counter->setNumber($remoteCounter->getId());

            $goals = WebCounterGoalQuery::create()
                ->find();

            /** @var WebCounterGoal $goal */
            foreach($goals as $goal)
            {
                $provider->getRemoteRepository()
                    ->addGoal($counter, $goal->getName(), $goal->getAlias(), $goal->getAction());
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
            $provider->generateCode($counter->getNumber())
        );
    }



}