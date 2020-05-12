<?php namespace App2share\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class SubscriptionsFreePack extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\RelationController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $sortableRelationsConfig = 'config_sortable_relations.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
    }
}
