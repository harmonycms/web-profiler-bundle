<?php

namespace Harmony\Bundle\WebProfilerBundle;

use Harmony\Bundle\WebProfilerBundle\DependencyInjection\Compiler\HarmonyDataCollectorCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HarmonyWebProfilerBundle
 *
 * @package HarmonyCore\WebProfilerBundle
 */
class HarmonyWebProfilerBundle extends Bundle
{

    /**
     * Builds the bundle.
     * It is only ever called once when the cache is empty.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new HarmonyDataCollectorCompiler());
    }

}