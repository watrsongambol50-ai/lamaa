@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up">
    <div class="flex flex-col items-center mb-5 mt-4">
        <div class="bg-white rounded-2xl shadow-lg mb-2 flex items-center justify-center" style="width:88px;height:88px;">
            <img src="/images/lamaa-logo.png" alt="لماء LAMAA" style="width:70px;height:70px;object-fit:contain;display:block;">
        </div>
        <h1 class="text-2xl font-black text-primary text-center">{{ app()->getLocale()==='ar' ? 'إنشاء حساب جديد' : 'Create Account' }}</h1>
        <p class="text-gray-500 text-sm mt-1 text-center">{{ app()->getLocale()==='ar' ? 'انضم إلى لماء اليوم' : 'Join LAMAA today' }}</p>
    </div>
    <div class="card p-6">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-4 text-sm">
            @foreach($errors->all() as $e)
            <div class="flex items-center gap-1"><svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>{{ $e }}</div>
            @endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 bg-gray-50 focus:bg-white transition">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'رقم الجوال' : 'Phone' }}</label>
                <div class="flex gap-2" dir="ltr">
                    <select name="country_code" class="border border-gray-200 rounded-xl px-2 py-3 bg-gray-50 focus:bg-white transition text-sm font-bold" style="max-width:120px;">
                        <option value="+966" selected>🇸🇦 +966</option>
                        <option value="+971">🇦🇪 +971</option>
                        <option value="+973">🇧🇭 +973</option>
                        <option value="+965">🇰🇼 +965</option>
                        <option value="+968">🇴🇲 +968</option>
                        <option value="+974">🇶🇦 +974</option>
                        <option value="+20">🇪🇬 +20</option>
                        <option value="+962">🇯🇴 +962</option>
                    </select>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required class="flex-1 border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="5xxxxxxxx">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'كلمة المرور' : 'Password' }}</label>
                <div class="relative">
                    <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 bg-gray-50 focus:bg-white transition">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" required class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 bg-gray-50 focus:bg-white transition">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'البريد الإلكتروني (اختياري)' : 'Email (optional)' }}</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 bg-gray-50 focus:bg-white transition" placeholder="example@mail.com" dir="ltr">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale()==='ar' ? 'العنوان (اختياري)' : 'Address (optional)' }}</label>
                <div class="relative">
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 ps-10 bg-gray-50 focus:bg-white transition" placeholder="{{ app()->getLocale()==='ar'?'عنوانك':'Your address' }}">
                    <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                </div>
            </div>
            <button type="submit" class="w-full hero-gradient text-white py-3.5 rounded-xl font-black text-base hover:opacity-90 transition mt-2">{{ app()->getLocale()==='ar' ? 'إنشاء حساب' : 'Create Account' }}</button>
        </form>
    </div>
    <p class="text-center text-sm text-gray-500 mt-4">
        {{ app()->getLocale()==='ar' ? 'لديك حساب؟' : 'Already have an account?' }}
        <a href="{{ route('login') }}" class="text-secondary font-black">{{ app()->getLocale()==='ar' ? ' تسجيل الدخول' : ' Login' }}</a>
    </p>
</div>
@endsection
