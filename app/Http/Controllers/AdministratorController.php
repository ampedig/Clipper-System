<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function index()
    {
        $administrators = User::where('role', 'admin')->paginate(10);
        return view('dashboard.administrators.index', compact('administrators'));
    }
}
