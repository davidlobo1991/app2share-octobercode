<?php namespace Rebel59\Countdown;

use Backend;
use System\Classes\PluginBase;

/**
 * countdown Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rebel59.countdown::lang.plugin.name',
            'description' => 'rebel59.countdown::lang.plugin.description',
            'author'      => 'rebel59',
            'homepage'    => 'https://github.com/Rebel59/oc-countdown-plugin',
            'icon'        => 'icon-clock-o'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Rebel59\Countdown\Components\Countdown' => 'countdown',
        ];
    }
}
