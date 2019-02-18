<?php

namespace Harmony\Bundle\WebProfilerBundle\DataCollector;

use Exception;
use Harmony\Bundle\CoreBundle\Component\HttpKernel\AbstractKernel;
use Harmony\Bundle\CoreBundle\HarmonyCoreBundle;
use Harmony\Bundle\CoreBundle\Manager\SettingsManager;
use Liip\ThemeBundle\ActiveTheme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class HarmonyCollector
 *
 * @package HarmonyCore\WebProfilerBundle\DataCollector
 */
class HarmonyCollector extends DataCollector
{

    /** @var ActiveTheme $activeTheme */
    protected $activeTheme;

    /** @var array $toolbars */
    protected $toolbars = [];

    /** @var KernelInterface $kernel */
    protected $kernel;

    /** @var settingsManager $settingsManager */
    protected $settingsManager;

    /**
     * Constructor.
     *
     * @param KernelInterface|AbstractKernel $kernel
     * @param settingsManager                $settingsManager
     * @param ActiveTheme                    $activeTheme
     */
    public function __construct(KernelInterface $kernel, settingsManager $settingsManager, ActiveTheme $activeTheme)
    {
        $this->kernel          = $kernel;
        $this->settingsManager = $settingsManager;
        $this->activeTheme     = $activeTheme;
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
            'toolbars'         => $this->toolbars,
            'app_name'         => HarmonyCoreBundle::NAME,
            'app_version'      => HarmonyCoreBundle::VERSION,
            'settings'         => $this->settingsManager->getSettingsByDomain(array_keys($this->settingsManager->getDomains())),
            'active_theme'     => $this->activeTheme->getName(),
            'available_themes' => $this->kernel->getThemes(),
            'extensions'       => $this->kernel->getExtensions(),
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
        if (!array_key_exists($key, $this->data)) {
            throw new Exception('Key "' . $key . '"" doesn\'t exists');
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