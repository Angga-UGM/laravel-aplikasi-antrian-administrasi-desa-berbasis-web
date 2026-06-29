<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect ke admin dashboard yang sudah ada
        return redirect()->route('admin.dashboard');
    }
}