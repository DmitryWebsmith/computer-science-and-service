<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::query()->find(Auth::id());
        $leads = Lead::all();


        $operatorData = [
            'first_name' => $user->first_name,
            'email' => $user->email,
        ];

        return Inertia::render('Dashboard/Index', [
            'leadsProps' => $leads,
            'operatorData' => $operatorData,
        ]);
    }
}
