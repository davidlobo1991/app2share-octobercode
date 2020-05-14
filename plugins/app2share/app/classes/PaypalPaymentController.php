<?php namespace App2share\App\Classes;

use Illuminate\Routing\Controller;

/**
 * Paypal Payment Controller Back-end Controller
 */
class PaypalPaymentController extends Controller
{
    public function saveSubscription() {
        $test = post();

        $test2 = $test;

        return 'caca';

    }

    public function approveSubscription() {
        $test = post();

        $test2 = $test;
    }
}
