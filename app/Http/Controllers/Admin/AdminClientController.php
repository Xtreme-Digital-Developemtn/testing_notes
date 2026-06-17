<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientRequest;
use App\Models\User;

class AdminClientController extends Controller
{
    public function index()
    {
        $users = User::withCount('requests')->get();

        return view('admin.clients.index', compact('users'));
    }
}
