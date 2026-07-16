<?php
namespace App\Http\Controllers;
use App\Models\Service;
class HomeController extends Controller {
    public function index() {
        $services = Service::orderBy('category')->orderBy('base_price')->get();
        return view('home', compact('services'));
    }
}
