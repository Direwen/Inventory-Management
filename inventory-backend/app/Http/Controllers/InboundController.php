<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboundRequest;
use App\Models\Inbound;
use App\Models\Inventory;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;

class InboundController extends Controller
{
    use ApiResponseTrait;

    public function store(StoreInboundRequest $request, Inventory $inventory, Product $product)
    {
        $details = $request->validated();

        try {
            DB::transaction(function () use ($details, $product) {
                $inbound = Inbound::create(array_merge($details, [
                    "product_id" => $product->id,
                    "user_id" => auth()->id()
                ]));
    
                $stock = $product->stock;

                $stock->update([
                    "current_stock" => ($stock->current_stock) + ($inbound->quantity)
                ]);
            });

            return $this->successResponse(
                data: $product->stock,
                message: "Inbound Process Complete"
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
