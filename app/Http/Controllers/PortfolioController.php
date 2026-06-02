<?php

namespace App\Http\Controllers;

use App\Models\PortfolioProject;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = PortfolioProject::active()->with('images')->get();
        $categories = $projects->pluck('category')->unique()->values();
        $clients = $projects->pluck('client_name')->unique()->values();

        return view('portfolio', compact('projects', 'categories', 'clients'));
    }
}
