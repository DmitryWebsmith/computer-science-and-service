<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response|\Symfony\Component\HttpFoundation\Response
    {
        if (!Auth::check()) {
            return Inertia::render('Landing/Index');
        }

        return Inertia::location(route('dashboard'));
    }
}
