<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function postSalesOrder (Request $request)
    {
        try {
            $data = $request->only([
                'sale_date',
                'customer_id',
            ]);

            $sale = new SalesOrder($data);
            $sale->save();

            return response($sale, 201);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
