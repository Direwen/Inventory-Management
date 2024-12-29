<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Stock;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    use ApiResponseTrait;

    private $PREFIX = "PROD";

    public function index(Inventory $inventory, Request $request)
    {
        $request->validate([
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date', 'after:start'], 
        ]);
    
        $search = $request->query('search');
        $startDate = $request->query('start');
        $endDate = $request->query('end');
    
        $query = $inventory->products()->with('stock');
    
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%"); 
        }
    
        if ($startDate) {
            $query->whereHas('stock', function ($q) use ($startDate) {
                $q->whereDate('updated_at', '>=', $startDate);
            });
        }
    
        if ($endDate) {
            $query->whereHas('stock', function ($q) use ($endDate) {
                $q->whereDate('updated_at', '<=', $endDate);
            });
        }
    
        $products = $query->latest()->paginate(10);
    
        return $this->successResponse(
            data: $products,
            message: "Paginated products of the inventory '{$inventory->name}'"
        );
    }
    public function show(Inventory $inventory, Product $product)
    {
        return $this->successResponse(
            data: $product->load("stock"),
            message: "Fetched the product of the inventory, '{$inventory->name}'"
        );
    }

    public function store(StoreProductRequest $request, Inventory $inventory)
    {

        $details = $request->validated();

        $sku = uniqid($details["prefix"] ?? $this->PREFIX);

        try {
            DB::transaction(function () use ($details, $sku, $inventory) {
                $product = Product::create([
                    'name' => $details["name"],
                    'sku' => $sku,
                    'inventory_id' => $inventory->id
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'current_stock' => $details["initial_qty"] ?? 0
                ]);
            });

            return $this->successResponse(
                message: "Created a product for the '{$inventory->name}' inventory"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, Inventory $inventory, Product $product)
    {
        $details = $request->validated();

        try {
            $product->update($details);

            return $this->successResponse("Updated the product, " . $product->name);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(Inventory $inventory, Product $product)
    {
        try {
            $product->delete();

            return $this->successResponse(
                message: "Deleted the product"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
