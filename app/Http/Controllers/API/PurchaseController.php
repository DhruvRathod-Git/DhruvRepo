<?php

namespace App\Http\Controllers\API;

use App\Models\Products;
use App\Models\Purchase;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchase = Purchase::with(['customer', 'product'])->get();
        return response()->json([
            'status'  => true,
            'message' => 'All Purchase List Fetched Successfully',
            'data'    => $purchase,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'status'      => 'required|string',
        ]);

        return DB::transaction(function () use ($validated) {

            $product = Products::findOrFail($validated['product_id']);

            if ($product->stock < $validated['quantity']) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Not enough stock available'
                ], 400);
            }

            $product->decrement('stock', $validated['quantity']);

            $purchase = Purchase::create([
                'customer_id' => $validated['customer_id'],
                'product_id'  => $product->id,
                'quantity'    => $validated['quantity'],
                'status'      => $validated['status'],
                'total_price' => $product->price * $validated['quantity'],
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Purchase successful',
                'data'    => $purchase
            ], 201);
        });
    }

    public function cancel($id)
    {
        $purchase = Purchase::find($id);

        $purchase->status = 'cancelled';
            if (!$purchase->save()) {
            return response()->json([
            'status' => false,
            'message' => 'Failed to cancel the order',
            ], 500);
        }

        $customer = Customer::find($purchase->customer_id);
        $product = Products::find($purchase->product_id);
        $product->increment('stock', $purchase->quantity);

        $purchase->status = 'cancelled';
        $purchase->save();

        return response()->json([
            'status'  => true,
            'message' => 'Order cancelled successfully',
            'data' => $purchase
        ], 200);
    }

    public function invoice($id)
{
    $purchase = Purchase::with(['customer', 'product'])->findOrFail($id);

    return response()->json([
        'invoice' => [
            'invoice_id'    => $purchase->id,
            'customer_id' => $purchase->customer,
            'product_id'  => $purchase->product, 
            'quantity'      => $purchase->quantity,
            'total_price'   => $purchase->total_price,  
            'status'        => $purchase->status,
            ]
        ]);
    }
}