<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Import the User model
use App\Models\Restaurant; // Import the Restaurant model
use App\Models\Reservation; // Import the Reservation model
use Carbon\Carbon; // Import Carbon for date manipulation

class HomeController extends Controller
{
    public function index() {
        // Fetching data for total users
        $total_users = User::count();

        // Fetching data for total premium users
        $total_premium_users = User::whereHas('subscriptions', function ($query) {
            $query->where('stripe_status', 'active');
        })->count();

        // Calculating total free users
        $total_free_users = $total_users - $total_premium_users;

        // Fetching data for total restaurants
        $total_restaurants = Restaurant::count();

        // Fetching data for total reservations
        $total_reservations = Reservation::count();

        // Calculating monthly sales (assuming fixed monthly amount and premium users)
        $sales_for_this_month = 300 * $total_premium_users;

        return view('admin.home', compact(
            'total_users',
            'total_premium_users',
            'total_free_users',
            'total_restaurants',
            'total_reservations',
            'sales_for_this_month'
        ));
    }
}
