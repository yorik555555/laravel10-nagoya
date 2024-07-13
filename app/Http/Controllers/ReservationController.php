<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->orderBy('reserved_datetime', 'desc')
            ->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Restaurant $restaurant)
    {
        return view('reservations.create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date_format:Y-m-d',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_people' => 'required|integer|min:1|max:50',
        ]);

        $reserved_datetime = $request->input('reservation_date') . ' ' . $request->input('reservation_time');

        Reservation::create([
            'reserved_datetime' => $reserved_datetime,
            'number_of_people' => $request->input('number_of_people'),
            'restaurant_id' => $request->input('restaurant_id'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('reservations.index')->with('flash_message', '予約が完了しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== Auth::id()) {
            return redirect()->route('reservations.index')->with('error_message', '不正なアクセスです。');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('flash_message', '予約をキャンセルしました。');
    }
}
