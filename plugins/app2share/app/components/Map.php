<?php namespace App2share\App\Components;

use Cms\Classes\ComponentBase;
use function foo\func;

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
        $partner = \App2share\App\Models\Partner::with('offer')->whereHas('offer', function ($q) {
            $q->where('active', '=', 1);
        })->orderBy('name', 'asc')->get();


        $this->page['partner'] = $partner;
    }

}
