<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SalesOrderController extends Controller
{
    public function postSalesOrder (Request $request)
    {
        try {
            $request->validate([
                'sale_date' => 'required|string',
                'customer_id' => 'required|exists:customers,id',
            ]);

            $data = $request->only([
                'sale_date',
                'customer_id',
            ]);

            $sale = new SalesOrder($data);
            $sale->save();

            return response($sale, 201);
        } catch (ValidationException $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 422);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
