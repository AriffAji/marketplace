<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Cart;

class DashboardController extends Controller
{
    public function index()
    {
        $transaction = TransactionDetail::with(['transaction.user', 'product.galleries'])->whereHas('product', function ($product) {
            $product->where('users_id', Auth::user()->id);
        });
        $revenue = $transaction->get()->reduce(function ($carry, $item) {
            return $carry + $item->price;
        });
        $customer = User::count();
        $carts = Cart::where('users_id', Auth::user()->id)->count();
        return view('pages.dashboard', [
            'transaction_count' => $transaction->count(),
            'transaction_data' => $transaction->get(),
            'revenue' => $revenue,
            'customer' => $customer,
            'carts' => $carts,
        ]);
    }
}