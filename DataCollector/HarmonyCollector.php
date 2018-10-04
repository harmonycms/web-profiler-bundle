<?php

namespace Harmony\Bundle\WebProfilerBundle\DataCollector;

use Exception;
use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Harmony\Bundle\CoreBundle\HarmonyCoreBundle;
use Harmony\Bundle\ThemeBundle\ActiveTheme;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

    /** @var ParameterBagInterface $parameterBag */
    protected $parameterBag;

    /** @var ActiveTheme $activeTheme */
    protected $activeTheme;

    /** @var array $toolbars */
    protected $toolbars = [];

    /**
     * Constructor.
     *
     * @param ParameterBagInterface $parameterBag
     * @param ActiveTheme           $activeTheme
     */
    public function __construct(ParameterBagInterface $parameterBag, ActiveTheme $activeTheme)
    {
        $this->parameterBag = $parameterBag;
        $this->activeTheme  = $activeTheme;
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
            'toolbars'           => $this->toolbars,
            'app_name'           => HarmonyCoreBundle::NAME,
            'app_version'        => HarmonyCoreBundle::VERSION,
            'harmony_parameters' => array_filter($this->parameterBag->all(), function ($key) {
                return strpos($key, HarmonyCoreExtension::ALIAS . '.') === 0;
            }, ARRAY_FILTER_USE_KEY),
            'active_theme'       => $this->activeTheme->getName(),
            'available_themes'   => $this->activeTheme->getThemeData()
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
        return 'harmony';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data['harmony_parameters'] = [];
        $this->data['toolbars']           = [];
    }

    /**
     * @param AbstractHarmonyCollector $collector
     */
    public function addCollector(AbstractHarmonyCollector $collector)
    {
        $this->toolbars[] = $collector->getToolbar()->getContent();
    }
}