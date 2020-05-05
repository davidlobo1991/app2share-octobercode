<?php namespace App2share\App;

use Backend;
use Auth;
use System\Classes\PluginBase;

/**
 * app Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['Flynsarmy.SocialLogin'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'App2Share',
            'description' => 'Plugin app2share',
            'author' => 'app2share',
            'icon' => 'icon-sun'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        \Event::listen('backend.form.extendBeforeCreate', function ($query) {
            $test = $query;
        });


    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        \Event::listen('backend.menu.extendItems', function ($manager) {
            if (Backend\Facades\BackendAuth::getFacadeRoot()->getUser()->login === "app2share") {
                $manager->removeMainMenuItem('October.Cms', 'cms');
                $manager->removeMainMenuItem('October.Backend', 'media');
            }
        });

        \Event::listen('offline.cashier::stripeElementForm.submit', function ($post) {
           $token = $post['token']['id'] ?? null;
            if (!$token) {
                throw new \RuntimeException('Stripe token is missing!');
            }

            $user = Auth::getUser();
            $user->newSubscription('main', $post['code'])->create($token);

            return [
                'redirect' => \Url::to('thank-you')
            ];
        });


        \Event::listen('offline.cashier::subscription.check', function ($user, $component) {

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
            'App2share\App\Components\OfferRating' => 'OfferRating',
            'App2share\App\Components\UserOffer' => 'UserOffer',
            'App2Share\App\Components\ProductList' => 'ProductList',
            'App2Share\App\Components\CheckoutList' => 'CheckoutList',
            'App2Share\App\Components\CartList' => 'CartList',
            'App2Share\App\Components\ProductInfo' => 'ProductInfo',
            'App2Share\App\Components\StripeElementsForm' => 'stripeElementsForm',
            'App2Share\App\Components\NeedsSubscription' => 'needsSubscription',

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
            'offeruser' => [
                'label' => 'Usos App2Share',
                'url' => Backend::url('app2share/app/offeruser'),
                'icon' => 'icon-magic',
                'permissions' => ['app2share.app.changes'],
                'order' => 500,
            ],
            'partner' => [
                'label' => 'Asociados',
                'url' => Backend::url('app2share/app/partner'),
                'icon' => 'icon-building',
                'permissions' => ['app2share.app.changes'],
                'order' => 500,
                'sideMenu' => [
                    'partnerSide' => [
                        'label' => 'Asociados',
                        'url' => Backend::url('app2share/app/partner'),
                        'icon' => 'icon-building',
                        'permissions' => ['app2share.app.changes'],
                    ],

                    'partnerType' => [
                        'label' => 'Tipos de asociados',
                        'url' => Backend::url('app2share/app/partnertype'),
                        'icon' => 'icon-briefcase',
                        'permissions' => ['app2share.app.changes'],
                        'order' => 500,
                    ],

                    'city' => [
                        'label' => 'Ciudades',
                        'url' => Backend::url('app2share/location/city'),
                        'icon' => 'icon-home',
                        'permissions' => ['app2share.location.changes'],
                        'order' => 501,
                     ],
                    'province' => [
                        'label' => 'Provincias',
                        'icon' => 'icon-road',
                        'url' => Backend::url('app2share/location/province'),
                        'permissions' => ['app2share.location.changes'],
                        'order' => 502,
                    ],
                ],
            ],
            'contact' => [
                'label' => 'Contacto',
                'url' => Backend::url('app2share/app/contactpartner'),
                'icon' => 'icon-envelope',
                'permissions' => ['app2share.app.changes'],
                'order' => 500,
                'sideMenu' => [
                    'contactPartner' => [
                        'label' => 'Contacto Partner',
                        'url' => Backend::url('app2share/app/contactpartner'),
                        'icon' => 'icon-envelope',
                        'permissions' => ['app2share.app.changes'],
                    ],

                    'contactFinal' => [
                        'label' => 'Contacto cliente final',
                        'url' => Backend::url('app2share/app/contactfinal'),
                        'icon' => 'icon-envelope',
                        'permissions' => ['app2share.app.changes'],
                        'order' => 500,
                    ],
                ],
            ],
        ];
    }

    function register_flynsarmy_sociallogin_providers()
    {
        return [
            'App2Share\\App\\SocialLoginProviders\\Instagram' => [
                'label' => 'Instagram',
                'alias' => 'Instagram',
                'description' => 'Log in with Instagram'
            ],
        ];
    }
}
