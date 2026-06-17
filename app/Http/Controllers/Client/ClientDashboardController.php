<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalRequests = ClientRequest::where('user_id', $userId)->count();
        $notStarted = ClientRequest::where('user_id', $userId)->where('status', 'not_started')->count();
        $inProgress = ClientRequest::where('user_id', $userId)->where('status', 'in_progress')->count();
        $solved = ClientRequest::where('user_id', $userId)->where('status', 'solved')->count();
        $latestRequests = ClientRequest::where('user_id', $userId)->latest()->take(5)->get();

        return view('client.dashboard', compact(
            'totalRequests', 'notStarted', 'inProgress', 'solved', 'latestRequests'
        ));
    }
}
