<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

use Exception;

use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    //
    public function process(Request $request)
    {
        // TODO: Save users data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        // Proses checkout
        $code = 'STORE-' . mt_rand(0000, 9999);
        $carts = Cart::with(['product', 'user'])
            ->where('users_id', Auth::user()->id)
            ->get();

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'insurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

        foreach ($carts as $cart) {
            $trx = 'TRX-' . mt_rand(0000, 9999);

            TransactionDetail::create([
                'transactions_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'price' => $cart->product->price,
                'shipping_status' => 'PENDING',
                'resi' => '',
                'code' => $trx
            ]);
        }

        // Delete cart data
        Cart::with(['product', 'user'])->where('users_id', Auth::user()->id)->delete();

        // Konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Buat array untuk dikirim ke midtrans
        $midtrans = array(
            'transaction_details' => array(
                'order_id' =>  $code,
                'gross_amount' => (int) $request->total_price,
            ),
            'customer_details' => array(
                'first_name'    => 'Galih Pratama',
                'email'         => 'hanamura.iost@gmail.com'
            ),
            'enabled_payments' => array('gopay', 'bank_transfer'),
            'vtweb' => array()
        );

        try {
            // Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // Redirect ke halaman midtrans
            return redirect($paymentUrl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function success(Request $request)
    {
    }
}