<?php

\Illuminate\Support\Facades\Route::post('payment/paypal', 'App2share\App\Classes\PaypalPaymentController@saveSubscription');
\Illuminate\Support\Facades\Route::post('payment/approve', 'App2share\App\Classes\PaypalPaymentController@approveSubscription');
