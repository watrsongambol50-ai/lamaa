<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name_ar')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'icon'        => 'shirt',
            'base_price'  => $request->base_price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'name_ar'     => $request->name_ar,
            'name_en'     => $request->name_en,
            'base_price'  => $request->base_price,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Prevent deletion if orders reference this service
        if ($service->orders()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الخدمة لوجود طلبات مرتبطة بها');
        }

        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'تم حذف الخدمة');
    }
}
