<?php namespace App2share\App;

use Backend;
use System\Classes\PluginBase;

/**
 * app Plugin Information File
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
            'name'        => 'app',
            'description' => 'No description provided yet...',
            'author'      => 'app2share',
            'icon'        => 'icon-leaf'
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
        \Event::listen('backend.menu.extendItems', function($manager) {
            if (Backend\Facades\BackendAuth::getFacadeRoot()->getUser()->login === "app2share") {
                $manager->removeMainMenuItem('October.Cms', 'cms');
                $manager->removeMainMenuItem('October.Backend', 'media');
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'App2share\App\Components\Map' => 'Map',
            'App2share\App\Components\ContactFinal' => 'ContactFinal',
            'App2share\App\Components\ContactPartner' => 'ContactPartner',
            'App2share\App\Components\OfferValoration' => 'OfferValoration',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'app2share.app.changes' => [
                'tab' => 'app',
                'label' => 'Changes Backend'
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
            'partner' => [
                'label'       => 'Asociados',
                'url'         => Backend::url('app2share/app/partner'),
                'icon'        => 'icon-building',
                'permissions' => ['app2share.app.changes'],
                'order'       => 500,
                'sideMenu'    => [
                    'partnerSide' => [
                        'label'       => 'Asociados',
                        'url'         => Backend::url('app2share/app/partner'),
                        'icon'        => 'icon-building',
                        'permissions' => ['app2share.app.changes'],
                    ],

                    'partnerType' => [
                        'label'       => 'Tipos de asociados',
                        'url'         => Backend::url('app2share/app/partnertype'),
                        'icon'        => 'icon-briefcase',
                        'permissions' => ['app2share.app.changes'],
                        'order'       => 500,
                    ],
                ],
            ],
            'contact' => [
                'label'       => 'Contacto',
                'url'         => Backend::url('app2share/app/contactpartner'),
                'icon'        => 'icon-envelope',
                'permissions' => ['app2share.app.changes'],
                'order'       => 500,
                'sideMenu'    => [
                    'contactPartner' => [
                        'label'       => 'Contacto Partner',
                        'url'         => Backend::url('app2share/app/contactpartner'),
                        'icon'        => 'icon-envelope',
                        'permissions' => ['app2share.app.changes'],
                    ],

                    'contactFinal' => [
                        'label'       => 'Contacto cliente final',
                        'url'         => Backend::url('app2share/app/contactfinal'),
                        'icon'        => 'icon-envelope',
                        'permissions' => ['app2share.app.changes'],
                        'order'       => 500,
                    ],
                ],
            ],
        ];
    }
}
