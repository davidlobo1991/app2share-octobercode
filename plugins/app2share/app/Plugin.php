<?php namespace App2share\App;

use App2share\App\Models\SubscriptionsFreePack;
use Backend;
use Auth;
use Carbon\Carbon;
use Stripe\Order;
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

            if (strpos($post['code'], 'plan') === 0) {
                $user = Auth::getUser();
                $user->newSubscription('main', $post['code'])->create($token);
            } else {
                $startDate = Carbon::parse($post['start_date']);
                $endDate = Carbon::parse($post['end_date']);
                $diffInDays = $startDate->diffInDays($endDate);

                $user = Auth::getUser();
                \Stripe\Stripe::setApiKey('sk_test_rcRHedkKfgooEhWenJDcuzIB00IU1TfR80');

                try {
                    $order = Order::create([
                        'currency' => 'eur',
                        'email' => $user->email,
                        'items' => [
                            [
                                'type' => 'sku',
                                'parent' => $post['code'],
                                'quantity' => $diffInDays
                            ],
                        ],
                        'shipping' => [
                            'name' => $user->name,
                            'address' => [
                                'line1' => '1234 Main Street',
                                'city' => 'San Francisco',
                                'state' => 'CA',
                                'country' => 'US',
                                'postal_code' => '94111',
                            ],
                        ],
                    ]);

                    $order->pay(['source' => $token]);

                    $subscription = new SubscriptionsFreePack();
                    $subscription->date_start = $startDate;
                    $subscription->date_end = $endDate;
                    $subscription->user_id = $user->id;
                    $subscription->price = $order->amount / 100;

                    if ($order->status === 'paid') {
                        $subscription->is_paid = true;
                    }

                    $subscription->save();

                } catch (\Exception $e) {
                    logger()->error($e->getMessage());
                }
            }

            return [
                'redirect' => \Url::to('thank-you')
            ];
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
            'subscriptions' => [
                'label' => 'Suscripciones',
                'url' => Backend::url('app2share/app/subscriptionsFreePack'),
                'icon' => 'icon-euro',
                'permissions' => ['app2share.app.changes'],
                'order' => 500,
                'sideMenu' => [
                    'contactPartner' => [
                        'label' => 'Suscripciones',
                        'url' => Backend::url('app2share/app/subscriptionsFreePack'),
                        'icon' => 'icon-euro',
                        'permissions' => ['app2share.app.changes'],
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
