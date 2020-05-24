<?php namespace App2share\App\Classes;

use App2share\App\Models\SubscriptionsFreePack;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use RainLab\User\Facades\Auth;

/**
 * Paypal Payment Controller Back-end Controller
 */
class PaypalPaymentController extends Controller
{
    public function saveSubscription()
    {
        $post = post();
        $user = Auth::getUser();

        if (!$user) {
            return null;
        }

        $dateStart = \Illuminate\Support\Carbon::parse($post['formStartDate']);
        $dateEnd = \Illuminate\Support\Carbon::parse($post['formEndDate']);
        $diffInDays = $dateStart->diffInDays($dateEnd);

        if ($diffInDays >= 3 && $diffInDays <= 7) {
            $totalPrice = $diffInDays * 3;
        } elseif ($diffInDays > 7 && $diffInDays <= 14) {
            $totalPrice = $diffInDays * 2.5;
        } elseif ($diffInDays > 14 && $diffInDays <= 21) {
            $totalPrice = $diffInDays * 2;
        } else {
            $totalPrice = $diffInDays * 1.5;
        }

        $subscription = new SubscriptionsFreePack();

        $subscription->date_start = $post['formStartDate'];
        $subscription->date_end = $post['formEndDate'];
        $subscription->user = $user->id;
        $subscription->price = $totalPrice;
        $subscription->id_paypal_order = str_random(20);

        $subscription->save();

        return [
          'amount' => $totalPrice,
          'idOrder' => $subscription->id_paypal_order
        ];
    }

    public function approveSubscription()
    {
        $post = post();

        $customId = $post['data'][0]['custom_id'];
        $amount = $post['data'][0]['amount']['value'];

        $subscription = SubscriptionsFreePack::where('id_paypal_order', $customId)->first();

        if ($amount == $subscription->price) {
            $subscription->is_paid = true;
        }

        $subscription->amount_paid = $amount;
        $subscription->save();
    }
}
