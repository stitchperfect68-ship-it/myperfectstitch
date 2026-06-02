<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Client;

class ContextController extends Controller
{
    public function index()
    {
        $team = TeamMember::active()->get();
        $clients = Client::active()->get();

        return view('context', compact('team', 'clients'));
    }
}
