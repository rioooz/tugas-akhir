<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Enums\OrderStatus;

class DashboardController extends Controller
{
    /**
     * Display the authenticated user's dashboard with simple stats.
     */
    public function index()
    {
        $user = Auth::user();

        // $totalOrders = $user->orders()->where('user_id',$user->id)->count();
        $totalOrders = $user->orders()->count();
        $dalamProses = $user->orders()->where('status', OrderStatus::PENDING)->count();
        $belumBayar = $user->orders()->where('payment_status', OrderStatus::PENDING)->count();

        return view('user.dashboard', compact('totalOrders', 'dalamProses', 'belumBayar'));
    }
}
