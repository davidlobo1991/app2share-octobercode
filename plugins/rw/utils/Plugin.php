<?php namespace RW\Utils;

use Backend\Facades\Backend;
use Backend\Classes\Controller;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Lang;
use Event;
use Rw\Utils\Classes\BehaviorsRegistry;

class Plugin extends PluginBase
{
    public $require = ['RainLab.Translate'];

    public function registerComponents()
    {
        return [
            '\RW\Utils\Components\SeoPage' => 'seoPage',
            '\RW\Utils\Components\DynamicSeo' => 'dynamicSeo',
            '\RW\Utils\Components\Scripts' => 'scripts',
            '\RW\Utils\Components\LanguageDetector' => 'LanguageDetector',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Web details',
                'description' => 'Manage webpage details',
                'category' => 'Web',
                'icon' => 'icon-cog',
                'class' => 'RW\Utils\Models\Settings',
                'url' => Backend::url('rw/utils/settings/update'),
                'order' => 500,
                'keywords' => 'security location',
                'permissions' => ['manage_config']
            ]
        ];
    }

    public function boot()
    {
        Event::listen('pages.builder.registerControllerBehaviors', function ($behaviorLibrary) {
            new BehaviorsRegistry($behaviorLibrary);
        }, -1);

        $this->app['Illuminate\Contracts\Http\Kernel']
            ->prependMiddleware('RW\Utils\Classes\Middlewares\HttpsRedirect');

        Controller::extend(function ($controller) {
            if (!$controller->hasSeo) {
                return;
            }
            $isRelation = request('_relation_field');
            if (!is_null($isRelation) && ($isRelation != 'rwSeo')) {
                return;
            }
            if (!isset($controller->relationConfig)) {
                $controller->addDynamicProperty('relationConfig');
            }

            $rwSeoRelation = '$/rw/utils/classes/traits/seoextendable/config_relation.yaml';

            $controller->relationConfig = $controller->mergeConfig(
                $controller->relationConfig,
                $rwSeoRelation
            );
            Event::listen('backend.page.beforeDisplay', function ($controller) {
                if (!$controller->isClassExtendedWith('Backend.Behaviors.RelationController')) {
                    $controller->implement[] = 'Backend.Behaviors.RelationController';
                    $controller->extendClassWith('Backend.Behaviors.RelationController');
                }
            });
        });


        Controller::extend(function ($controller) {
            if (!$controller->hasExpirable) {
                return;
            }

            Event::listen('backend.form.extendFields', function ($widget) {
                $widget->addFields([
                    'expires_at' => [
                        'label' => 'rw.utils::lang.fields.expires_at',
                        'type' => 'datepicker'
                    ]
                ]);
            });

            $controller->listConfig = $controller->mergeConfig(
                '$/rw/utils/classes/traits/expirable/config_filter.yaml',
                $controller->listConfig
            );

            Event::listen('backend.filter.extendScopes', function ($widget) {
                $widgetController = $widget->getController();
                $modelClass = new $widgetController->listConfig->modelClass;

                $widget->addScopes([
                    'is_expired' => [
                        'label' => 'rw.utils::lang.fields.is_expired',
                        'type' => 'switch',
                        'conditions' => [
                            $modelClass->getExpirableColumn() . '>=' . "'" . Carbon::now() . "'",
                            $modelClass->getExpirableColumn() . '<=' . "'" . Carbon::now() . "'"
                        ],
                    ],
                ]);
            });
        });

        Controller::extend(function ($controller) {
            if (!$controller->hasActive) {
                return;
            }

            $controller->listConfig = $controller->mergeConfig(
                '$/rw/utils/classes/traits/activatable/config_filter.yaml',
                $controller->listConfig
            );

            Event::listen('backend.filter.extendScopes', function ($widget) {
                $widgetController = $widget->getController();
                $modelClass = new $widgetController->listConfig->modelClass;

                $widget->addScopes([
                    'is_active' => [
                        'label' => 'rw.utils::lang.fields.is_active',
                        'type' => 'switch',
                        'conditions' => [
                            $modelClass->getActiveColumn() . '<> ' . $modelClass->getActiveValue(),
                            $modelClass->getActiveColumn() . '= ' . $modelClass->getActiveValue()
                        ],
                    ],
                ]);
            });
        });
    }
}
