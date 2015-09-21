<?php


namespace ItBlaster\CounterManagementBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class CounterManagementProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('counter_management.manager')) {
            return;
        }

        $definition = $container->findDefinition(
            'counter_management.manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'counter_management.provider'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addProvider',
                array(new Reference($id))
            );
        }
    }


}