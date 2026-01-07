<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CarService;
use App\Models\Car;
use App\Models\Models;

class CarController extends Controller
{
    protected $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    // Main Page
    public function index()
    {
        $models = Models::all();
        // Initial load of cars for server-side rendering preference or just empty container to be filled by ajax
        // Let's pass the initial cars so the page isn't empty on load
        $cars = $this->carService->getCars(null, null, 4);
        return view('cars.index', compact('models', 'cars'));
    }

    // Fetch Content (AJAX)
    public function fetch(Request $request)
    {
        if($request->ajax()) {
            $search = $request->get('search');
            $modelId = $request->get('model_id');
            // Check if model_id is "all" or empty and set to null
            if($modelId == 'all') $modelId = null;

            $cars = $this->carService->getCars($search, $modelId, 4);

            return view('cars.partials.list', compact('cars'))->render();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'model_id' => 'required|exists:models,id',
            'price_per_day' => 'required|numeric',
            'status' => 'required|string',
            'year' => 'required|integer',
            'image' => 'required|image|max:2048'
        ]);

        // Default user_id to 1 for now as we don't have auth implemented in this flow seemingly
        $data = $request->all();
        $data['user_id'] = 1; // Hardcoded or Auth::id()

        $this->carService->createCar($data);

        return response()->json(['success' => 'Car created successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'model_id' => 'required|exists:models,id',
            'price_per_day' => 'required|numeric',
            'status' => 'required|string',
            'year' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        $car = Car::findOrFail($id);
        $this->carService->updateCar($car, $request->all());

        return response()->json(['success' => 'Car updated successfully']);
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $this->carService->deleteCar($car);

        return response()->json(['success' => 'Car deleted successfully']);
    }
    
    // Helper to get a single car details for Edit Modal
    public function show($id)
    {
        $car = Car::findOrFail($id);
        return response()->json($car);
    }
}
