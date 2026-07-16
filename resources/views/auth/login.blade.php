@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up">
    <div class="flex flex-col items-center mb-6 mt-4">
        <div class="bg-white rounded-2xl shadow-lg mb-3 flex items-center justify-center" style="width:96px;height:96px;">
            <img src="/images/lamaa-logo.png" alt="لماء LAMAA" style="width:78px;height:78px;object-fit:contain;display:block;">
        </div>
        <h1 class="text-2xl font-black text-primary text-center">{{ app()->getLocale()==='ar' ? 'مرحباً بعودتك' : 'Welcome Back' }}</h1>
        <p class="text-gray-500 text-sm mt-1 text-center">{{ app()->getLocale()==='ar' ? 'سجّل دخولك للمتابعة' : 'Sign in to continue' }}</p>
    </div>
    <div class="card p-6">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-4 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $errors->first() }}
        </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'رقم الجوال' : 'Phone Number' }}</label>
                <div class="flex gap-2" dir="ltr">
                    <span class="border border-gray-200 rounded-xl px-3 py-3 bg-gray-50 text-sm font-bold flex items-center gap-1">🇸🇦 +966</span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                        class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-base bg-gray-50 focus:bg-white transition" placeholder="5xxxxxxxx">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'كلمة المرور' : 'Password' }}</label>
                <div class="relative">
                    <input type="password" name="password" id="pw" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 pe-10 text-base bg-gray-50 focus:bg-white transition">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <button type="button" onclick="var i=document.getElementById('pw');i.type=i.type==='password'?'text':'password'"
                        class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'left-3':'right-3' }} text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full hero-gradient text-white py-3.5 rounded-xl font-black text-base hover:opacity-90 transition">
                {{ app()->getLocale()==='ar' ? 'تسجيل الدخول' : 'Login' }}
            </button>
        </form>
    </div>
    <p class="text-center text-sm text-gray-500 mt-4">
        {{ app()->getLocale()==='ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
        <a href="{{ route('register') }}" class="text-secondary font-black">{{ app()->getLocale()==='ar' ? ' إنشاء حساب' : ' Register' }}</a>
    </p>
</div>
@endsection
