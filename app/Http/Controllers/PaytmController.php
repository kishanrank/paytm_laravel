<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Paytm;

class PaytmController extends Controller
{

    public function initiate()
    {
        return view('paytm');
    }

    public function pay(Request $request)
    {
        $amount = 1500;

        $userData = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'fee' => $amount,
            'order_id' => $request->mobile."_".rand(1,1000)
        ];

        $paytmuser = Paytm::create($userData);

        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $userData['order_id'], 
            'user' => $paytmuser->id,
            'mobile_number' => $userData['mobile'],
            'email' => $userData['email'], // your user email address
            'amount' => $amount, // amount will be paid in INR.
            'callback_url' => route('status') // callback URL
        ]);
        return $payment->receive();
    }

    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        
        $order_id = $transaction->getOrderId();
    
        if ($transaction->isSuccessful()) {

            Paytm::where('order_id', $order_id)->update(['status' => 1, 'transaction_id' => $transaction->getTransactionId()]);

            return redirect(route('initiate.payment'))->with('message', "Your payment is successfull.");

        } else if ($transaction->isFailed()) {

            Paytm::where('order_id', $order_id)->update(['status' => 0, 'transaction_id' => $transaction->getTransactionId()]);

            return redirect(route('initiate.payment'))->with('message', "Your payment is failed.");
            
        } else if ($transaction->isOpen()) {

            Paytm::where('order_id', $order_id)->update(['status' => 2, 'transaction_id' => $transaction->getTransactionId()]);

            return redirect(route('initiate.payment'))->with('message', "Your payment is processing.");
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        // $transaction->getOrderId(); // Get order id
    }
}
