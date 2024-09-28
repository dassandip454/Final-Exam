<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    public function create(Car $car)
    {
        return view('frontend.rentals.create', compact('car'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $car = Car::findOrFail($request->car_id);
        $totalCost = $car->daily_rent_price * (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24);
        
        Rental::create([
            'user_id' => Auth::id(),
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_cost' => $totalCost,
        ]);

        $car->availability = false;
        $car->save();

        return redirect()->route('frontend.cars.index')->with('success', 'Rental created successfully.');
    }

    public function myRentals()
    {
        $rentals = Rental::where('user_id', Auth::id())->with('car')->get();
        return view('frontend.rentals.index', compact('rentals'));
    }
}
