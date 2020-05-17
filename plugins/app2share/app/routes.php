<?php

\Illuminate\Support\Facades\Route::post('payment/paypal', 'App2share\App\Classes\PaypalPaymentController@saveSubscription')->middleware('web');
\Illuminate\Support\Facades\Route::post('payment/approve', 'App2share\App\Classes\PaypalPaymentController@approveSubscription')->middleware('web');
