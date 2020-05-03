<?php namespace Rw\Utils\Behaviors;

use Str;
use Backend\Classes\ControllerBehavior;
use October\Rain\Exception\ApplicationException;

class SortableRelationsController extends ControllerBehavior
{
    /**
     * @var array Properties that must exist in the controller using this behavior.
     */
    protected $requiredProperties = ['sortableRelationsConfig'];

    /**
     * @var array Configuration values that must exist when applying the primary config file.
     * - parentClass: Class name for the parent model
     */
    protected $requiredConfig = ['parentClass'];

    /**
     * Behavior constructor
     * @param Backend\Classes\Controller $controller
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        if (!$controller->isClassExtendedWith('Backend.Behaviors.RelationController')) {
            throw new ApplicationException('The parent controller of the SortableRelations behavior must extend first the Backend\Behaviors\RelationController behavior');
        }

        $this->setConfig($controller->sortableRelationsConfig, $this->requiredConfig);

        $this->config->parentClass = Str::normalizeClassName($this->config->parentClass);

        $this->addAssets();
    }

    /**
     * Add the page assets needed for sorting.
     * @return void
     */
    protected function addAssets()
    {
        $this->addJs('js/Sortable.min.js');
        $this->addJs('js/sortable_relations.js');
        $this->addCss('css/sortable_relations.css');
    }

    /**
     * Gets the relation by the relation name from the configuration
     * @param string $relationName
     * @return array
     */
    protected function getRelation($relationName)
    {
        if (!$relationName) {
            throw new ApplicationException('You need to specify the "relationName" in the sort order handler');
        }

        if (!$relation = $this->getConfig($relationName)) {
            throw new ApplicationException('The relationName "' . $relationName . '" specified in the sort order handler does not exist');
        }

        return $relation;
    }

    /**
     * Gets the related model class by the relation from the configuration
     * @param array $relation
     * @return string
     */
    protected function getRelatedModelClass($relation)
    {
        if (!$relatedModelClass = $relation['modelClass']) {
            throw new ApplicationException('You need to specify the "modelClass" inside the relationName of the configuration file');
        }

        return $relatedModelClass;
    }

    /**
     * Find the parent model by the primery key from the configuration parent class
     * @return \October\Rain\Database\Model
     * @throws \October\Rain\Exception\ApplicationException
     */
    protected function findParentModel($parentId)
    {
        if (!$parentModel = $this->config->parentClass::find($parentId)) {
            throw new ApplicationException('Could not find the parent model by the id for unknown reasons');
        }

        return $parentModel;
    }

    protected function pivotRelation($relation)
    {
        return !! ($relation['pivot'] ?? false);
    }

    protected function getSortOrderColumn($relation, $relationName, $model)
    {
        if ($this->pivotRelation($relation)) {
            $sortOrderColumn = 'pivot['.$model->pivotSortableRelations[$relationName].']';
        } else {
            $relatedClass = $model->{$relationName}()->getRelated();

            if (!isset(class_uses($relatedClass)['October\Rain\Database\Traits\Sortable']))
                throw new ApplicationException('The model "' . get_class($relatedClass) . '" must use the "\October\Rain\Database\Traits\Sortable" trait');

            $sortOrderColumn = (new $relatedClass)->getSortOrderColumn();
        }

        return $sortOrderColumn;
    }

    /**
     * Ajax event handler to update the relation sort order with a new position.
     * @return void
     * @throws \October\Rain\Exception\ApplicationException
     */
    public function update_onReorderRelation($recordId = null)
    {
        $data = post();

        $relation = $this->getRelation($data['relationName']);

        $relatedModelClass = $this->getRelatedModelClass($relation);

        if ($this->pivotRelation($relation)) {
            $parentModel = $this->findParentModel($recordId);
            $parentModel->setSortablePivotOrder($data['relationName'], $data['sortableIds'], $data['sortableOrders']);
        } else {
            $relatedModel = new $relatedModelClass;
            $relatedModel->setSortableOrder($data['sortableIds'], $data['sortableOrders']);
        }
    }

    /**
     * Disable sorting.
     * @param object $config
     * @param string $field
     * @param \October\Rain\Database\Model $model
     * @return void
     */
    public function relationExtendConfig($config, $field, $model)
    {
        if (!$relation = $this->getConfig($field)) {
            return;
        }

        $sortOrderColumn = $this->getSortOrderColumn($relation, $field, $model);

        $config->view['showSorting'] = false;

        $config->view['defaultSort'] = [
            'column'    => $sortOrderColumn,
            'direction' => 'asc'
        ];
    }

    public function relationExtendViewWidget($widget, $field, $model)
    {
        if (!$relation = $this->getConfig($field))
            return;
        $sortOrderColumn = $this->getSortOrderColumn($relation, $field, $model);

        $handlerPath = $this->pivotRelation($relation)
                        ? '_sort_order_pivot_handler_column.htm'
                        : '_sort_order_handler_column.htm';

        $widget->addColumns([
            $sortOrderColumn => [
                'invisible' => true
            ],
            'sort_order_handler' => [
                'label' => 'rw.utils::lang.common.reorder',
                'type' => 'partial',
                'path' => $this->viewPath . '/' . $handlerPath,
                'width' => '60px',
                'searchable' => false,
                'clickable' => false
            ]
        ]);

        $widget->cssClasses[] = 'sortable-relations-manager';
    }
}
