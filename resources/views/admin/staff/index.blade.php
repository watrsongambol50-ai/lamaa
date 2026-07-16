@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <h1 class="text-xl font-black text-primary">فريق العمل (حسابات فرعية)</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card p-4">
        <h3 class="font-bold text-primary mb-3 text-sm">إضافة موظف جديد</h3>
        <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">اسم الموظف</label>
                <input type="text" name="name" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">رقم الجوال</label>
                <input type="tel" name="phone" required dir="ltr" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">كلمة المرور</label>
                <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <button type="submit" class="w-full hero-gradient text-white py-3 rounded-xl font-black text-sm">إنشاء حساب الموظف</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($staff as $s)
        <div class="card p-4 flex items-center justify-between gap-3">
            <div>
                <p class="font-black text-primary">{{ $s->name }}</p>
                <p class="text-xs text-gray-500" dir="ltr">{{ $s->phone }}</p>
            </div>
            <form method="POST" action="{{ route('admin.staff.destroy', $s->id) }}" onsubmit="return confirm('حذف حساب الموظف؟')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 font-bold">حذف</button>
            </form>
        </div>
        @empty
        <div class="card p-8 text-center text-gray-400 font-bold text-sm">لا يوجد موظفون حالياً</div>
        @endforelse
    </div>
</div>
@endsection
