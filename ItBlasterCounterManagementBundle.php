<?php

namespace ItBlaster\CounterManagementBundle;

use ItBlaster\CounterManagementBundle\DependencyInjection\Compiler\CounterManagementProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ItBlasterCounterManagementBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CounterManagementProviderCompilerPass());
    }
}
