<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
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

            $itemsInput = $request->only('items');
            $hasItems = !empty($itemsInput) && is_array($itemsInput);

            if ($hasItems) {
                $request->validate(([
                    'items' => 'array',
                    'items.*' => 'array:sale_id,product_id,unit_price',
                    'items.*.sale_id' => 'exists:sales_orders,id',
                    'items.*.product_id' => 'required|exists:products,id',
                    'items.*.unit_price' => 'required|numeric',
                ]));
            }

            $salesOrder = $request->only([
                'sale_date',
                'customer_id',
            ]);

            $sale = new SalesOrder($salesOrder);
            $sale->save();

            if ($hasItems) {
                foreach ($itemsInput['items'] as $value) {
                    $item = new SaleItem([
                        'sale_id' => $sale->id,
                        'product_id' => $value['product_id'],
                        'unit_price' => $value['unit_price'],
                    ]);
                    $item->save();
                }
            }

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
