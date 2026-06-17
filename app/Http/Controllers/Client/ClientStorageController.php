<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientStorageController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaFile::where('user_id', Auth::id());

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($requestId = $request->get('request_id')) {
            $query->where('request_id', $requestId);
        }

        if ($year = $request->get('year')) {
            $query->whereYear('created_at', $year);
        }

        if ($month = $request->get('month')) {
            $query->whereMonth('created_at', $month);
        }

        $files = $query->latest()->paginate(20);

        $requests = Auth::user()->requests()->select('id', 'title')->get();

        return view('client.storage.index', compact('files', 'requests'));
    }
}
