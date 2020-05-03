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

    public function onCreateCart()
    {
        $data = post();
        $user = Auth::getUser();
        $now = Carbon::now();


        try {
            $product = Product::find($data['product_id']);
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            throw new ApplicationException('Producto  no encontrado');
        }


        if ($product->is_digital && !isset($data['digital'])) {
            throw new ApplicationException('Selecciona como mínimo un artículo digital');
        }


        $stockFinal = $product->getStock();

        if ($data['quantity'] == 0) {
            throw new ApplicationException('Articulo no añadido. La cantidad tiene que ser mayor de 0');
        }

        if ($data['quantity'] > $stockFinal) {
            throw new ApplicationException('Articulo no añadido. La cantidad no puede ser mayor de '. $stockFinal);
        }


        if (!Session::has('web_cart_id')) {
            try {
                $cart = new Cart;
                if ($user) {
                    $cart->user_id = $user->id;
                }
                $cart->created_at = $now;
                $cart->updated_at = $now;
                $cart->save();
            } catch (MassAssignmentException $e) {
                Log::error($e->getMessage());
                throw new ApplicationException('Error al crear el carito');
            }

            $this->onCreateCartPosition($cart, $data);

            Session::put('web_cart_id', $cart->id);

        }else {
            $cart_id = Session::get('web_cart_id');
            $cart = Cart::whereKey($cart_id)->first();
            $tets = $cart->position;
            $cartPositionIds = $cart->position->where('digital_parent_id', null)->pluck('product_id');

            if (in_array($data['product_id'], $cartPositionIds->toArray())) {
                $this->onUpdateCartPosition($cart, $data);
            }else {
                $this->onCreateCartPosition($cart, $data);
            }
        }

        $cart->load('position');
        $this->page['cart'] = $cart;

        if ($product->isDigital() && isset($data['digital']) && count($data['digital']) > 0) {
            foreach ($data['digital'] as $idDigitalProduct) {
                $digitalProduct = ['product_id' => $idDigitalProduct, 'quantity' => $data['quantity']];
                $this->onCreateCartPosition($cart, $digitalProduct, true, $product->id);
            }
        }

        $this->page['totalPrice'] = CartPosition::getTotalPrice($cart->id);
        $this->page['totalItems'] = $cart->position->sum->quantity;
    }

    private function onCreateCartPosition(Cart $cart, $data, $isDigital = false, $digitalParentId = null)
    {
        try {
            $cartItem = new CartPosition;
            $cartItem->cart_id = $cart->id;
            $cartItem->item_id = $data['product_id'];
            $cartItem->item_type = Product::class;
            $cartItem->quantity = $data['quantity'];
            $cartItem->save();
        } catch (MassAssignmentException $e) {
            Log::error($e->getMessage());
            throw new ApplicationException('Error al crear el carrito');
        }
    }

    private function onUpdateCartPosition(Cart $cart, $data)
    {
        $cartItem = $cart->position->where('product_id', $data['product_id'])->first();

        try {
            $cartItem = $cart->position->where('product_id', $data['product_id'])->first();
            $cartItem->quantity =  ($cartItem->quantity + $data['quantity']);
            $cartItem->save();
        } catch (MassAssignmentException $e) {
            Log::error($e->getMessage());
            throw new ApplicationException('Error al crear el carrito');
        }
    }
}
