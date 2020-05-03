<?php namespace RW\Utils\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class SeoPages extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_seo' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RW.Utils', 'main-seo', 'inner-seo');
    }
}
