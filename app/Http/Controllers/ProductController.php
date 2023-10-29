<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_to_cart(Request $request){
        $user_id = Auth::id();
        if($request->product_id){
            Cart::create([
                "user_id" => $user_id,
                "product_id" =>$request->product_id,
                "price" => $request->price ?? 0,
            ]);
            return response()->json(["success"=> true,"message" =>"Added to cart"]);
        }else{
            return response()->json(["error"=> true,"message" =>"Failed to add"]);
        }
    }
}
