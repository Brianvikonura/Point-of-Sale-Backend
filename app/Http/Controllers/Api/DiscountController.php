<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        // get data discounts
        $discounts = \App\Models\Discount::all();

        return response()->json([
            'status' => 'success',
            'data' => $discounts
        ], 200);
    }

    // store
    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        // create the discount
        $discount = \App\Models\Discount::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $discount
        ], 200);
    }
}
