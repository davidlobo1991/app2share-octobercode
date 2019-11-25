<?php namespace App2share\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ContactPartner extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\ReorderController'    ];

    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('App2share.App', 'contact', 'contactPartner');
    }
}
