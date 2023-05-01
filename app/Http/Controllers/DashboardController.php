<?php

namespace App\Http\Controllers;

use App\Models\PizzaDebt;
use Illuminate\Http\Request;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $debts = PizzaDebt::with('user')->get();
        $users = User::all();

        return view('dashboard', ['debts' => $debts, 'users' => $users]);
    }
}
