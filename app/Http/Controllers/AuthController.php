<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function login(Request $request) {
        $request->validate(['phone'=>'required','password'=>'required']);

        // Brute-force protection: lock out after 5 failed attempts per phone+IP for 60s.
        $throttleKey = strtolower($request->phone).'|'.$request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['phone'=>__('auth.throttle', ['seconds'=>$seconds]) ?: "عدد محاولات كبير، حاول مرة أخرى بعد {$seconds} ثانية"])->withInput();
        }

        $user = User::where('phone',$request->phone)->first();
        if (!$user || !Hash::check($request->password,$user->password)) {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['phone'=>__('auth.failed')])->withInput();
        }
        \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

        Auth::login($user,$request->boolean('remember'));
        $request->session()->regenerate();

        if (in_array($user->role, ['admin', 'staff'], true)) return redirect()->route('admin.index');
        return redirect()->intended(route('dashboard'));
    }
    public function register(Request $request) {
        $request->validate(['name'=>'required|string|max:255','phone'=>'required|string|unique:users,phone','country_code'=>'nullable|string|max:5','password'=>'required|string|min:8|confirmed']);
        $user = User::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'country_code'=>$request->country_code ?: '+966',
            'email'=>$request->email,
            'address'=>$request->address,
            'password'=>Hash::make($request->password),
        ]);
        // Privileged/system fields set explicitly outside mass assignment — see User::$fillable.
        $user->forceFill(['role'=>'customer','total_orders'=>0])->save();
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
