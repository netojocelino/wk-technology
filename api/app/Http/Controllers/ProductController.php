<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function postProduct (Request $request) {
        try {
            $data = $request->only([
                'name',
                'unit_price',
            ]);

            $product = new Product($data);
            $product->save();

            return response($product, 201);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
