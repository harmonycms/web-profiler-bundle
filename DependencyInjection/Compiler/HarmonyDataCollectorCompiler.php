<?php

namespace Harmony\Bundle\WebProfilerBundle\DependencyInjection\Compiler;

use Harmony\Bundle\WebProfilerBundle\DataCollector\HarmonyCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HarmonyDataCollectorCompiler
 *
 * @package Harmony\Bundle\WebProfilerBundle\DependencyInjection\Compiler
 */
class HarmonyDataCollectorCompiler implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(HarmonyCollector::class)) {
            return;
        }

        $definition = $container->findDefinition(HarmonyCollector::class);

        // find all service IDs with the `harmony.data_collector` tag
        $taggedServices = $container->findTaggedServiceIds('harmony.data_collector');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('addCollector', [new Reference($id)]);
        }
    }
}