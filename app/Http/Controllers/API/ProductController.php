<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $Products = Products::all();
        return response()->json(
            [
                'status' => true,
                'message' => 'Product List Fetched Successfully',
                'data'    => $Products,
            ],
        );
    }   

    public function store(Request $request)
{
    $Products = Products::create($request->only([
        'product_name',
        'description',
        'price',
        'quantity',
        'stock',
    ]));

    if (!$Products) {
        return response()->json([
            'status' => false,
            'message' => 'Product Not Created',
        ], 400);
    }

    return response()->json([
        'status'=>true,
        'message'=>'Product Created Successfully',
        'data'=>$Products
    ], 201);
}

    public function show($id)
    {
        $Products = Products::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Product Fetched Successfully',    
            'data' => $Products ], 200);
    }
}