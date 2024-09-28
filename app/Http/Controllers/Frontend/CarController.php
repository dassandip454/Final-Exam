<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('availability', true);

        if ($request->has('car_type')) {
            $query->where('car_type', $request->car_type);
        }

        if ($request->has('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->has('max_price')) {
            $query->where('daily_rent_price', '<=', $request->max_price);
        }

        $cars = $query->get();
        return view('frontend.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        return view('frontend.cars.show', compact('car'));
    }
}

