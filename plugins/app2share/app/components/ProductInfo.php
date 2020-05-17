<?php namespace App2share\App\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use RainLab\User\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Lovata\Shopaholic\Models\Product;
use Illuminate\Support\Facades\Session;
use October\Rain\Support\Facades\Flash;
use Lovata\OrdersShopaholic\Models\Cart;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\OrdersShopaholic\Models\CartPosition;
use October\Rain\Exception\ApplicationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Symfony\Component\HttpFoundation\Request;

class ProductInfo extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ProductInfo Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
    public function onRun()
    {
        $product =  Product::transWhere('slug', $this->param('slug'))->first();

        $this->page['product'] = $product;
    }
}
