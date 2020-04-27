<?php namespace App2share\App\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class OfferUser extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController'    ];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
    }
}
