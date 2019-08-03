<?php namespace App2share\App\Components;

use Cms\Classes\ComponentBase;

class Map extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'map Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $partner = \App2share\App\Models\Partner::orderBy('name', 'asc')->get();

        $this->page['partner'] = $partner;
    }

}
