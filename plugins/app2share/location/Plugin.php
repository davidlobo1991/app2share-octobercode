<?php namespace App2share\Location;

use Backend;
use System\Classes\PluginBase;

/**
 * location Plugin Information File
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
            'name' => 'location',
            'description' => 'No description provided yet...',
            'author' => 'app2share',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'App2share\Location\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'app2share.location.some_permission' => [
                'tab' => 'location',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [

            'city' => [
                'label' => 'Ciudades',
                'url' => Backend::url('app2share/location/city'),
                'icon' => 'icon-home',
                'permissions' => ['app2share.location.*'],
                'order' => 500,
                'sideMenu' => [
                    'citySide' => [
                        'label' => 'Ciudades',
                        'url' => Backend::url('app2share/location/city'),
                        'icon' => 'icon-home',
                        'permissions' => ['app2share.location.*'],
                        'permissions' => ['cptmeatball.pricetables.access_prices']
                    ],
                    'province' => [
                        'label' => 'Provincias',
                        'icon' => 'icon-road',
                        'url' => Backend::url('app2share/location/province'),
                        'permissions' => ['app2share.location.*'],
                    ],
                ]
            ]
        ];
    }
}
