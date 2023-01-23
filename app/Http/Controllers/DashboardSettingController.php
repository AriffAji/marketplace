<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardSettingController extends Controller
{
    public function store()
    {
        $user =  Auth::user();
        $categories = Category::all();
        $carts = Cart::where('users_id', Auth::user()->id)->count();
        return view('pages.dashboard-settings', [
            'user' => $user,
            'categories' => $categories,
            'carts' => $carts,
        ]);
    }
    public function account()
    {
        $user =  Auth::user();
        $carts = Cart::where('users_id', Auth::user()->id)->count();
        return view('pages.dashboard-account', [
            'user' => $user,
            'carts' => $carts,
        ]);
    }
    public function update(Request $request, $redirect)
    {
        $data = $request->all();
        $item = Auth::user();
        $item->update($data);
        return redirect()->route($redirect);
    }
}