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
}
