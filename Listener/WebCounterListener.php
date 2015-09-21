<?php


namespace ItBlaster\CounterManagementBundle\Listener;


use ItBlaster\CounterManagementBundle\Model\WebCounter;
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
        /** @var WebCounter $web_counter */
        $web_counter = $event->getSubject();
        if ($web_counter->isColumnModified(WebCounterPeer::NUMBER) || $web_counter->getCode() === null) {
            $this->generateCode($web_counter);
        }
    }

    /**
     * @param WebCounter $web_counter
     */
    protected function generateCode(WebCounter $web_counter)
    {
        $provider = $this->counter_management_manager->getProvider($web_counter->getTypeKey());
        $web_counter->setCode(
            $provider->generateCode($web_counter->getNumber())
        );
    }


}