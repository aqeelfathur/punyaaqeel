<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $programCount = Program::count();
        $userCount = User::count();

        return view('home', compact('programCount', 'userCount'));
    }
}
