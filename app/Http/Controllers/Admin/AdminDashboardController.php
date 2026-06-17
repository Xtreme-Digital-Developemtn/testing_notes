<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRequests = ClientRequest::count();
        $notStarted = ClientRequest::where('status', 'not_started')->count();
        $inProgress = ClientRequest::where('status', 'in_progress')->count();
        $solved = ClientRequest::where('status', 'solved')->count();
        $totalClients = User::count();

        $priorityDistribution = [
            'low' => ClientRequest::where('priority', 'low')->count(),
            'medium' => ClientRequest::where('priority', 'medium')->count(),
            'high' => ClientRequest::where('priority', 'high')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalRequests', 'notStarted', 'inProgress', 'solved',
            'totalClients', 'priorityDistribution'
        ));
    }
}
