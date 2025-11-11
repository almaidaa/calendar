<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HMI;

class HMIController extends Controller
{
    public function index()
    {
        $hmis = HMI::all();
        return view('hmi.index', compact('hmis'));
    }
}
