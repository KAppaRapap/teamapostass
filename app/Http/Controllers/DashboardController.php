<?php

namespace App\Http\Controllers;

use App\Models\Draw;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar próximos sorteios
        $upcomingDraws = Draw::where('draw_date', '>', now())
            ->where('is_completed', false)
            ->orderBy('draw_date', 'asc')
            ->take(5)
            ->get();

        // Buscar atividades recentes
        $recentActivities = Activity::where('user_id', $user->id)
            ->orWhere(function($query) use ($user) {
                $query->whereIn('group_id', $user->groups->pluck('id'));
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('upcomingDraws', 'recentActivities'));
    }
} 