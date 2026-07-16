<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'staff')->latest()->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $staff = User::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),
        ]);
        // Privileged/system fields set explicitly outside mass assignment — see User::$fillable.
        $staff->forceFill([
            'role'       => 'staff',
            'created_by' => Auth::id(),
            'total_orders' => 0,
        ])->save();

        return back()->with('success', 'تم إنشاء حساب الموظف بنجاح');
    }

    public function destroy($id)
    {
        User::where('role', 'staff')->findOrFail($id)->delete();
        return back()->with('success', 'تم حذف حساب الموظف');
    }
}
