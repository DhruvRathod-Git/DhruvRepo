<?php

namespace App\Http\Controllers\API;

use App\Models\Products;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'product_id' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $total = $request->quantity * $request->price;

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $total,
        ]);

        if(!$order){
            return response()->json([
                'status' => false,
                'message' => 'Order Not Created',
            ], 400);
        }else{
        return response()->json([
            'status' => true,
            'message' => 'Order created successfully',
            'data' =>  $order], 201);
        }
    }

    public function getAllOrders()
    {
        $orders = Order::all();
        if (!$orders) {
            return response()->json([
                'status' => false,
                'message' => 'Orders Not Found',
            ], 404);
        }else{
            return response()->json([
            'status' => true,
            'message' => 'All orders fetched successfully',
            'data'=> $orders], 200);
        }
    }

    

    
}
