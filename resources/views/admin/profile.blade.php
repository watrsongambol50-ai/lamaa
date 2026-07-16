@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <h1 class="text-xl font-black text-primary text-center">الملف الشخصي</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">الاسم</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">رقم الجوال</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required dir="ltr" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" dir="ltr" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">العنوان</label>
                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
            </div>
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs font-bold text-gray-500 mb-2">تغيير كلمة المرور (اختياري)</p>
                <div class="space-y-3">
                    <input type="password" name="current_password" placeholder="كلمة المرور الحالية" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
                    <input type="password" name="new_password" placeholder="كلمة المرور الجديدة" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
                    <input type="password" name="new_password_confirmation" placeholder="تأكيد كلمة المرور الجديدة" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50">
                </div>
            </div>
            <button type="submit" class="w-full hero-gradient text-white py-3.5 rounded-xl font-black text-base">حفظ التغييرات</button>
        </form>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full card py-3.5 rounded-xl font-black text-red-500 border border-red-200">تسجيل الخروج</button>
    </form>
</div>
@endsection
