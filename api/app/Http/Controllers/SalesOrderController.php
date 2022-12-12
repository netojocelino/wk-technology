<?php

namespace App\Http\Controllers;

use App\Models\SaleItem;
use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function getSalesOrder (Request $request, string $id)
    {
        try {
            $model = SalesOrder::findOrFail($id);
            $sale = $model->toArray();

            $sale['total'] = $model->TotalPrice;

            return response($sale, 200);

        } catch (ModelNotFoundException $exception) {
            return response([
                'message' => 'Product cannot exists.',
            ], 404);
        } catch (\Exception $exception) {
            return response([
                'message' => 'Sale Order cannot be retrived',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getSalesOrders (Request $request)
    {
        try {
            $orders = SalesOrder::with([
                'customer:id,name',
                'items:id,sale_id,product_id,total_price,total_items',
                'items.product:id,name,unit_price',
            ])->get();
            $responseArray = $orders->toArray();

            foreach($orders as $index => $order) {
                $responseArray[$index]['total_price'] = SaleItem::where('sale_id', $order->id)->sum('total_price');
            }

            return response($responseArray, 200);
        } catch (\Exception $exception) {
            return response([
                'message' => 'Sale Order cannot be retrived',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
