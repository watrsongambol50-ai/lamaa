<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller {
    public function index() { return view('profile',['user'=>Auth::user()]); }
    public function update(Request $request) {
        $user = Auth::user();
        $request->validate(['name'=>'required|string|max:255','email'=>'nullable|email|unique:users,email,'.$user->id,'phone'=>'required|string|unique:users,phone,'.$user->id,'address'=>'nullable|string|max:500','region'=>'nullable|string|max:255']);
        $data = $request->only('name','email','phone','address','region');
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password,$user->password)) return back()->withErrors(['current_password'=>'كلمة المرور الحالية غير صحيحة'])->withInput();
            $request->validate(['new_password'=>'required|string|min:6|confirmed']);
            $data['password'] = Hash::make($request->new_password);
        }
        $user->update($data);
        return back()->with('success',__('messages.profile_updated'));
    }
}
