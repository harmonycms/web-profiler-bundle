<?php

namespace Harmony\Bundle\WebProfilerBundle\DataCollector;

use Exception;
use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class HarmonyCollector
 *
 * @package HarmonyCore\WebProfilerBundle\DataCollector
 */
class HarmonyCollector extends DataCollector
{

    /** @var Container */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface|Container $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request   $request   A Request instance
     * @param Response  $response  A Response instance
     * @param Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $this->data = [
            'app_name'           => $this->container->getParameter('kernel.app_name'),
            'app_version'        => $this->container->getParameter('kernel.app_version'),
            'harmony_parameters' => array_filter($this->container->getParameterBag()->all(), function ($key) {
                return strpos($key, HarmonyCoreExtension::ALIAS . '.') === 0;
            }, ARRAY_FILTER_USE_KEY)
        ];
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws Exception
     */
    public function getData(string $key)
    {
        if (!isset($this->data[$key])) {
            throw new Exception('Key doesn\'t exists');
        }

        return $this->data[$key];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName(): string
    {
        return 'harmony.collector';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data = [];
    }
}