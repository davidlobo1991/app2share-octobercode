<?php namespace App2share\App\Components;

use App2share\App\Models\Offer;
use App2share\App\Models\Partner;
use Cms\Classes\ComponentBase;
use function foo\func;

class Map extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'map Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {

        $offers = Offer::with('partner.partner_type')
            ->where('active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $this->page['offers'] = $offers;
    }

}
