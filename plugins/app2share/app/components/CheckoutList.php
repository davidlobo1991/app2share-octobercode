<?php namespace App2share\App\Components;

use Auth;
use RainLab\Location\Models\State;
use Throwable;
use Stripe\Stripe;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use RainLab\Location\Models\Country;
use Lovata\Shopaholic\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Redirect;
use Lovata\OrdersShopaholic\Models\Cart;
use Lovata\OrdersShopaholic\Models\Order;
use Stripe\Checkout\Session as SessionStripe;
use Lovata\OrdersShopaholic\Models\CartPosition;
use Lovata\OrdersShopaholic\Models\ShippingType;
use October\Rain\Exception\ApplicationException;
use Lovata\OrdersShopaholic\Models\OrderPosition;
use Lovata\OrdersShopaholic\Models\PaymentMethod;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckoutList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'CheckoutList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
    public function onRun()
    {
        $cart_id = Session::get('web_cart_id');
        $cart = Cart::whereKey($cart_id)->first();

        $this->page['shippingPrice'] = 0;
        $this->page['paymentMethods'] = PaymentMethod::whereActive(true)->get();
        $this->page['totalPrices'] = 123;
    }


    public function onGenerateOrder()
    {
        $data = post();

        $user = Auth::getUser();

        try {
            $order = new Order;
            $order->status_id = 2;
            if ($user) {
                $order->user_id = $user->id;
            }
            $order->currency_id = 1;


            $order->save();
            $this->setOrderItem($order, 1);

        } catch (Throwable $e) {
            throw $e;
        }

        Session::put('web_order_id', $order->id);

        return $this->getCheckoutStripe($order);
    }

    private function setOrderItem(Order $order, $cartItems)
    {
            $orderItem = new OrderPosition;
            $orderItem->order_id = $order->id;
            $orderItem->item_id = 1;
            $orderItem->item_type = 'Lovata\Shopaholic\Models\Offer';
            $orderItem->price = 123;
            $orderItem->quantity = 1;
            $orderItem->save();

    }

    private function getCheckoutStripe(Order $order)
    {
        Stripe::setApiKey(env('STRIPE_KEY'));

        $serverName = $_SERVER['HTTP_HOST'];

        $data = $this->generateData($order);

        try {
            $session =  SessionStripe::create([
                'payment_method_types' => ['card'],
                'line_items' => $data['items'],
                'subscription_data' => [
                    'items' => $data['subcription'],
                ],
                'success_url' => 'http://'.$serverName.'/ok',
                'cancel_url' => 'http://'.$serverName.'/ko',
            ]);
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            throw new ApplicationException('Ha ocurrido un error al crear la session de stripe');
        }

        return [
            'sessionId' => $session->id
        ];
    }

    private function generateData(Order $order)
    {
        $countField = 0;
        $countSubcription = 0;
        $items = null;
        $subcription = null;


        foreach ($order->order_offer as $key => $position) {
            if ((int) $position->price > 0) {
                if ($position->item->is_subscription) {
                    $key = $countSubcription;
                    $subcription[$key] = [
                        'plan' => $position->item->product->stripe_plan_id,
                        'quantity' => $position->quantity,
                    ];
                    $countSubcription ++;
                } else {
                    $key = $countField;
                    $price = $position->price * 100;
                    $items[$key] = [
                        'name' => $position->item->product->name,
                        'images' => [$position->item->product->preview_image->path],
                        'amount' => $price,
                        'currency' => 'EUR',
                        'quantity' => $position->quantity,
                    ];
                    $countField ++;
                }
            }
        }

        if ($order->shipping_type_id) {
            $price = $order->shipping_price * 100;

            if ($price > 0) {
                if (isset($items)) {
                    $itemsKey = count($items);
                    $items[$itemsKey] = [
                        'name' => 'Gastos de envío',
                        'amount' => $price,
                        'currency' => 'EUR',
                        'quantity' => 1,
                    ];
                } else {
                    $items[0] = [
                        'name' => 'Gastos de envío',
                        'amount' => $price,
                        'currency' => 'EUR',
                        'quantity' => 1,
                    ];
                }
            }
        }


        $data = [
            'subcription' => $subcription,
            'items' => $items,
        ];

        return $data;
    }
}
