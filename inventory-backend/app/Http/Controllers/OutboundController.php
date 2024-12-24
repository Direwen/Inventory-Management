<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboundRequest;
use App\Models\Inventory;
use App\Models\Outbound;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;

class OutboundController extends Controller
{
    use ApiResponseTrait;

    public function store(StoreInboundRequest $request, Inventory $inventory, Product $product)
    {
        $details = $request->validated();

        $stock = $product->stock;

        $is_sufficient = ($stock->current_stock - $details["quantity"]) >= 0;

        if (!$is_sufficient) return $this->errorResponse("Not Enough Stocks");

        try {

            DB::transaction(function () use ($details, $product) {
                $outbound = Outbound::create(array_merge($details, [
                    "product_id" => $product->id,
                    "user_id" => auth()->id()
                ]));
    
                $stock = $product->stock;
    
                $stock->update([
                    "current_stock" => ($stock->current_stock) - ($outbound->quantity)
                ]);
            });

            return $this->successResponse(
                data: $product->stock,
                message: "Outbound Process Complete"
            );

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
