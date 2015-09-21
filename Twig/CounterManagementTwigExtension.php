<?php


namespace ItBlaster\CounterManagementBundle\Twig;


use ItBlaster\CounterManagementBundle\Service\Manager;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

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
            'render_counter' => new \Twig_Function_Method($this, 'renderCounter', array(
                'needs_environment' => true,
                'is_safe' => array('html')
            ))
        );
    }

    public function getName()
    {
        return 'counter_management_twig';
    }

    public function renderCounter(\Twig_Environment $environment)
    {
        $response = '';
        foreach ($this->counter_management_manager->getProviders() as $provider) {
            $response .= $environment->render('ItBlasterCounterManagementBundle:WebCounter:' . $provider->getIdentity() . '.html.twig', array(
                'web_counter_list' => $this->counter_management_manager->getProvider($provider->getIdentity())->getWebCounterList()
            ));
        }
        return $response;
    }


}