<?php


namespace ItBlaster\CounterManagementBundle\Twig;


use ItBlaster\CounterManagementBundle\Model\WebCounterQuery;
use ItBlaster\CounterManagementBundle\Service\Manager;

class CounterManagementTwigExtension extends \Twig_Extension
{

    /**
     * @var Manager
     */
    protected $counter_management_manager;


    /**
     * CounterManagementTwigExtension constructor.
     * @param Manager $counter_management_manager
     */
    public function __construct(Manager $counter_management_manager)
    {
        $this->counter_management_manager = $counter_management_manager;
    }

    public function getFunctions()
    {
        return array(
            'render_counters' => new \Twig_Function_Method($this, 'counters', array(
                'needs_environment' => true,
                'is_safe' => array('html')
            )),
            'render_persisted_counters' => new \Twig_Function_Method($this, 'persisted', array(
                'needs_environment' => true,
                'is_safe' => array('html')
            )),
            'render_counter' => new \Twig_Function_Method($this, 'counter', array(
                'needs_environment' => true,
                'is_safe' => array('html')
            )),
        );
    }

    public function getName()
    {
        return 'counter_management_twig';
    }

    public function counters(\Twig_Environment $environment)
    {
        $response = '';
        foreach ($this->counter_management_manager->getProviders() as $provider) {
            if($provider->getIdentity() !== 'persisted') {
                $response .= $environment->render('ItBlasterCounterManagementBundle:WebCounter/render:' . $provider->getIdentity() . '.html.twig', array(
                    'web_counter_list' => $provider->getWebCounterList()
                ));
            }
        }
        return $response;
    }


    public function counter(\Twig_Environment $environment, $id)
    {
        $response = '';

        if($counter = WebCounterQuery::create()->findOneById($id)) {
            $provider =  $this->counter_management_manager->getProvider($counter->getTypeKey());

            $response .= $environment->render('ItBlasterCounterManagementBundle:WebCounter/render:' . $provider->getIdentity() . '.html.twig', array(
                'web_counter_list' => array($counter)
            ));
        }

        return $response;
    }

    public function persisted(\Twig_Environment $environment)
    {
        $response = '';

        $provider = $this->counter_management_manager->getProvider('persisted');

        $response .= $environment->render('ItBlasterCounterManagementBundle:WebCounter/render:' . $provider->getIdentity() . '.html.twig', array(
            'web_counter_list' => $provider->getWebCounterList()
        ));
        return $response;
    }

}