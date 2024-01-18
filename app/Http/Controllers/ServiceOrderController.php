<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceOrderController extends Controller
{
    public function create(Request $request)
    {
        try {
            $request->validate([
                'vehiclePlate' => 'required|string|unique:service_orders|size:7',
                'entryDateTime' => 'required|date',
                'exitDateTime' => 'required|date',
                'priceType' => 'sometimes|string',
                'price' => 'sometimes|string',
                'userId' => 'required|integer',
            ]);

            $serviceOrder = ServiceOrder::create($request->all());

            return response()->json($serviceOrder, 201);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Service order not created.'], 404);
        }
    }

    public function index(Request $request)
    {
        $serviceOrders = ServiceOrder::with('user');
        if ($request->has('vehiclePlate')) {
            $serviceOrders->where('vehiclePlate', $request->input('vehiclePlate'));
        }
    
        return response()->json($serviceOrders->paginate(5), 200);
    }

    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'vehiclePlate' => 'sometimes|required',
                'price' => 'sometimes|required|numeric',
            ]);

            $serviceOrder = ServiceOrder::findOrFail($id);
            $serviceOrder->vehiclePlate = $request->get('vehiclePlate', $serviceOrder->vehiclePlate);
            $serviceOrder->price = $request->get('price', $serviceOrder->price);
            $serviceOrder->save();

            return response()->json(['message' => 'Service order updated successfully', $serviceOrder], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => "Service order not updated."], 404);
        }            
    }

    public function delete($id)
    {
        try {
            $serviceOrder = ServiceOrder::findOrFail($id);
            $serviceOrder->delete();
    
            return response()->json(['message' => 'Service order deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Service order not found'], 404);
        }
    }
}
?>