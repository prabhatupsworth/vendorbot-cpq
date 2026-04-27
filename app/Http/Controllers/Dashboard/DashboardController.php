<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

       $role = auth()->user()->getRoleNames()->first();

        return view('dashboard', compact('user', 'role'));
    }
}
