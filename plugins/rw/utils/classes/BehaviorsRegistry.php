<?php namespace Rw\Utils\Classes;

use Lang;

/**
 * Utility class for registering controller behaviors.
 */
class BehaviorsRegistry
{
    protected $behaviorLibrary;

    public function __construct($behaviorLibrary)
    {
        $this->behaviorLibrary = $behaviorLibrary;

        $this->registerBehaviors();
    }

    protected function registerBehaviors()
    {
        $this->registerSortableRelationsBehavior();
    }

    protected function registerSortableRelationsBehavior()
    {
        $properties = [
            'parentClass' => [
                'title' => Lang::get('rw.utils::lang.controller.property_behavior_sortable_relations_parent_class'),
                'description' => Lang::get('rw.utils::lang.controller.property_behavior_sortable_relations_parent_class_description'),
                'placeholder' => Lang::get('rw.utils::lang.controller.property_behavior_sortable_relations_parent_class_placeholder'),
                'type' => 'dropdown',
                'fillFrom' => 'model-classes',
                'validation' => [
                    'required' => [
                        'message' => Lang::get('rw.utils::lang.controller.property_behavior_sortable_relations_parent_class_required')
                    ]
                ]
            ]
        ];

        $this->behaviorLibrary->registerBehavior(
            'Rw\Utils\Behaviors\SortableRelationsController',
            'rw.utils::lang.controller.behavior_sortable_relations_controller',
            'rw.utils::lang.controller.behavior_sortable_relations_controller_description',
            $properties,
            'sortableRelationsConfig',
            'Rw\Utils\Widgets\DefaultBehaviorDesignTimeProvider',
            'config_sortable_relations.yaml'
        );
    }
}
