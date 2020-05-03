<?php namespace App2share\App\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Lovata\OrdersShopaholic\Models\Cart;
use Lovata\OrdersShopaholic\Models\CartPosition;
use October\Rain\Exception\ApplicationException;


class CartList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CartList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['totalItems'] = 0;
        if( Session::has('web_cart_id')) {
            $cart_id = Session::get('web_cart_id');
            $cart = Cart::whereKey($cart_id)->first();

            $this->page['cart'] = $cart;
            $this->page['totalItems'] = $cart->position->sum->quantity;
            $this->page['totalPrice'] = CartPosition::getTotalPrice($cart_id);
        }
    }

    public function onDeleteItem()
    {
        $data = post();

        try {
            CartPosition::where('cart_id', $data['cart_id'])
                ->where('digital_parent_id', $data['product_id'])->delete();

            CartPosition::where('cart_id', $data['cart_id'])
                ->where('product_id', $data['product_id'])->first()->delete();
        } catch (QueryException  $e) {
            Log::error($e->getMessage());
            throw new ApplicationException('Error al eliminar un item del carro');
        }

        $cart = Cart::whereKey($data['cart_id'])->first();

        $cart->load('position');
        $this->page['cart'] = $cart;
        $this->page['totalItems'] = $cart->position->sum->quantity;
        $this->page['totalPrice'] = CartPosition::getTotalPrice($data['cart_id']);
    }
}
