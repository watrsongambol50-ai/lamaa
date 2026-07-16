<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackagesController extends Controller
{
    public function index()
    {
        $packages = Package::where('active', true)->get();
        return view('packages', compact('packages'));
    }
}
