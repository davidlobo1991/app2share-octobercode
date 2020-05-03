<?php namespace Rw\Utils\Widgets;

use RainLab\Builder\Classes\BehaviorDesignTimeProviderBase;

class DefaultBehaviorDesignTimeProvider extends BehaviorDesignTimeProviderBase
{
    protected $defaultBehaviorClasses = [
        'Rw\Utils\Behaviors\SortableRelationsController' => 'sortable-relations-controller'
    ];

    /**
     * Renders behavior body.
     * @param string $class Specifies the behavior class to render.
     * @param array $properties Behavior property values.
     * @param  \RainLab\Builder\FormWidgets\ControllerBuilder $controllerBuilder ControllerBuilder widget instance.
     * @return string Returns HTML markup string.
     */
    public function renderBehaviorBody($class, $properties, $controllerBuilder)
    {
        if (!array_key_exists($class, $this->defaultBehaviorClasses)) {
            throw new SystemException('Unknown behavior class: ' . $class);
        }

        $partial = $this->defaultBehaviorClasses[$class];

        return $this->makePartial('behavior-' . $partial, [
            'properties' => $properties,
            'controllerBuilder' => $controllerBuilder
        ]);
    }

    /**
     * Returns default behavior configuration as an array.
     * @param string $class Specifies the behavior class name.
     * @param string $controllerModel Controller model.
     * @param mixed $controllerGenerator Controller generator object.
     * @return array Returns the behavior configuration array.
     */
    public function getDefaultConfiguration($class, $controllerModel, $controllerGenerator)
    {
        if (!array_key_exists($class, $this->defaultBehaviorClasses)) {
            throw new SystemException('Unknown behavior class: ' . $class);
        }

        switch ($class) {
            case 'Rw\Utils\Behaviors\SortableRelationsController':
                return $this->getActivatableControllerDefaultConfiguration($controllerModel, $controllerGenerator);
        }
    }

    protected function getActivatableControllerDefaultConfiguration($controllerModel, $controllerGenerator)
    {
        return [
            'parentClass' => $this->getFullModelClass($controllerModel->getPluginCodeObj(), $controllerModel->baseModelClassName)
        ];
    }

    protected function getFullModelClass($pluginCodeObj, $modelClassName)
    {
        return $pluginCodeObj->toPluginNamespace().'\\Models\\'.$modelClassName;
    }
}
