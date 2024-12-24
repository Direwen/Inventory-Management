<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Inventory;
use App\Traits\ApiResponseTrait;
use DB;
use Exception;

class InventoryController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        return $this->successResponse(
            data: Inventory::with("collaborators")->whereHas('collaborators', function ($query) {
                $query->where('user_id', auth()->id());
            })->get(),
            message: "Fetched All Inventories"
        );
    }

    public function store(StoreInventoryRequest $request)
    {
        $details = $request->validated();

        try {

            $inventory = null;
            
            DB::transaction(function () use ($details) {
                //create the inventory
                $inventory = Inventory::create($details);
                //store the current authenticated user as the admin in inventory_collaborators table
                $inventory->collaborators()->create([
                    'user_id' => auth()->id(),
                    'role' => 'admin'
                ]);
            });

            return $this->successResponse(
                data: $inventory,
                message: "Created an inventory"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(StoreInventoryRequest $request, Inventory $inventory)
    {
        $details = $request->validated();
        try {
            //create the inventory
            $inventory = $inventory->update($details);
            
            return $this->successResponse(
                data: $inventory,
                message: "Updated the inventory"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show(Inventory $inventory)
    {
        return $this->successResponse(
            data: $inventory->load('collaborators'),
            message: "Fetched the specific inventory"
        );
    }

    public function destroy(Inventory $inventory)
    {
        try {
            $inventory->delete();
    
            return $this->successResponse(
                message: "Deleted the inventory"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
