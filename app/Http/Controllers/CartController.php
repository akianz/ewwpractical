<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user_id = Auth::id();
        $cart_list = Cart::with(['product_details'])->where('user_id',$user_id)->get();
        $cart_list_amount= Cart::where('user_id',$user_id)->sum('price');
        return view('cart',compact('cart_list','cart_list_amount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!empty($id)){
            Cart::where('id',$id)->delete();
            return response()->json(["success"=>true,"message" => "Product removed successfully."]);
        }else{
            return response()->json(["success"=>true,"messages" => "something went wrong."]);
        }
    }

    public function checkout(Request $request){

        $user_id = Auth::id();
        $cart_list = Cart::with(['product_details'])->where('user_id',$user_id)->get(); 
        $cart_list_amount= Cart::where('user_id',$user_id)->sum('price');

        if(!empty($cart_list)){

            try{
                DB::beginTransaction();
                $order = Order::create([
                    "user_id" => $user_id, 
                    "total_amount" =>  $cart_list_amount ?? 0,
                    "order_date" => date("Y-m-d"),
                ]); 
    
                foreach($cart_list as $cValue){
                    OrderDetail::create([
                        "order_id" =>$order->id,
                        "user_id" =>$user_id,
                        "product_id" =>$cValue->product_id,
                        "product_price" =>$cValue->price,
                    ]);
                    if(isset($cValue->product_id)){
                        $prodct  = DB::table('products')->where('id',$cValue->product_id)->decrement('stock', 1);
                    }
                    $cValue->delete();
                }
                DB::commit();
                return redirect()->back()->with("success","Order Created Successfully.");
            }catch(\Exception $e){
                DB::rollBack();
                return redirect()->back()->with("error","something went wrong.");
            }
        }else{
            return redirect()->back()->with("error","Cart is empty.");
        }
    }
}
