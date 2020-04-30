<?php namespace App2share\App\Components;

use Cms\Classes\ComponentBase;
use Lovata\Shopaholic\Models\Product;

class ProductList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ProductList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $products = Product::all();
        $subscription = Product::all();

        if ($subscription) {
            $this->page['subscription'] = $subscription;
        };

        $this->page['products'] = $products;
    }
}
