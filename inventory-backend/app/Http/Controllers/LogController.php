<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Log;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LogController extends Controller
{

    use ApiResponseTrait;

    public function index(Inventory $inventory)
    {
        $logs = $inventory->logs()->paginate(10);

        return $this->successResponse(
            data: $logs,
            message: "Paginated logs of the inventory '{$inventory->name}'"
        );
    }
}
