<?php
namespace App\Http\Controllers;
use App\Models\Service;
class PricesController extends Controller {
    public function index() {
        $services = Service::orderBy('category')->orderBy('base_price')->get();
        return view('prices', compact('services'));
    }
}
