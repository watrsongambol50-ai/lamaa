<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:30|unique:coupons,code',
            'percentage'  => 'required|numeric|min:1|max:100',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        Coupon::create([
            'code'        => strtoupper($request->code),
            'percentage'  => $request->percentage,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
            'active'      => true,
        ]);

        return back()->with('success', 'تم إنشاء كود الخصم بنجاح');
    }

    public function toggle($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['active' => !$coupon->active]);
        return back()->with('success', 'تم تحديث حالة الكود');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف كود الخصم');
    }
}
