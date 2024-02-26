<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // index API
    public function index()
    {
        // get all products
        $products = Product::all();

        $products->load('category');
        
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }
}
