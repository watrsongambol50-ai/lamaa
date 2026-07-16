@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <h1 class="text-xl font-black text-primary">كوبونات الخصم</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card p-4">
        <h3 class="font-bold text-primary mb-3 text-sm">إضافة كوبون جديد</h3>
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="grid grid-cols-2 gap-3">
            @csrf
            <div class="col-span-2">
                <label class="block text-xs font-bold text-gray-600 mb-1">كود الخصم</label>
                <input type="text" name="code" required dir="ltr" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50" placeholder="RAMADAN25">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">نسبة الخصم %</label>
                <input type="number" name="percentage" min="1" max="100" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">حد الاستخدام (اختياري)</label>
                <input type="number" name="usage_limit" min="1" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-bold text-gray-600 mb-1">تاريخ الانتهاء (اختياري)</label>
                <input type="date" name="expires_at" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <button type="submit" class="col-span-2 hero-gradient text-white py-3 rounded-xl font-black text-sm">إضافة الكوبون</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($coupons as $c)
        <div class="card p-4 flex items-center justify-between gap-3">
            <div>
                <p class="font-black text-primary" dir="ltr">{{ $c->code }}</p>
                <p class="text-xs text-gray-500">خصم {{ $c->percentage }}% — استُخدم {{ $c->used_count }} {{ $c->usage_limit ? 'من '.$c->usage_limit : '' }} مرة</p>
                @if($c->expires_at)<p class="text-xs text-gray-400">ينتهي في {{ $c->expires_at }}</p>@endif
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $c->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $c->active ? 'فعال' : 'متوقف' }}</span>
                <form method="POST" action="{{ route('admin.coupons.toggle', $c->id) }}">
                    @csrf
                    <button type="submit" class="text-xs text-secondary font-bold">{{ $c->active ? 'تعطيل' : 'تفعيل' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.coupons.destroy', $c->id) }}" onsubmit="return confirm('حذف الكوبون؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 font-bold">حذف</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card p-8 text-center text-gray-400 font-bold text-sm">لا توجد كوبونات حالياً</div>
        @endforelse
    </div>
</div>
@endsection
