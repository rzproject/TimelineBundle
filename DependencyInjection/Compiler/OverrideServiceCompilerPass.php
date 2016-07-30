<?php

namespace Rz\TimelineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        #####################################
        ## Override Block Class
        #####################################
        $definition = $container->getDefinition('sonata.timeline.block.timeline');
        $definition->setClass($container->getParameter('rz.timeline.block.timeline.class'));
        $definition->addMethodCall('setAdminPool', array(new Reference('sonata.admin.pool')));
    }
}
