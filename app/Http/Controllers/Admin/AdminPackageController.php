<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class AdminPackageController extends Controller
{
    public function index()
    {
        $packages = Package::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar'         => 'required|string|max:255',
            'name_en'         => 'required|string|max:255',
            'price'           => 'required|numeric|min:0',
            'pieces_included' => 'required|integer|min:0',
        ]);

        Package::create([
            'name_ar'          => $request->name_ar,
            'name_en'          => $request->name_en,
            'price'            => $request->price,
            'pieces_included'  => $request->pieces_included,
            'free_delivery'    => $request->boolean('free_delivery'),
            'description_ar'   => $request->description_ar,
            'description_en'   => $request->description_en,
            'active'           => true,
        ]);

        return back()->with('success', 'تم إضافة الباقة بنجاح');
    }

    public function toggle($id)
    {
        $package = Package::findOrFail($id);
        $package->update(['active' => !$package->active]);
        return back()->with('success', 'تم تحديث حالة الباقة');
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف الباقة');
    }
}
