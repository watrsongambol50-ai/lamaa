@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <h1 class="text-xl font-black text-primary">باقات الاشتراك</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card p-4">
        <h3 class="font-bold text-primary mb-3 text-sm">إضافة باقة جديدة</h3>
        <form method="POST" action="{{ route('admin.packages.store') }}" class="grid grid-cols-2 gap-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">اسم الباقة (عربي)</label>
                <input type="text" name="name_ar" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">اسم الباقة (إنجليزي)</label>
                <input type="text" name="name_en" required dir="ltr" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">السعر (ريال)</label>
                <input type="number" step="0.01" name="price" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">عدد القطع المشمولة</label>
                <input type="number" name="pieces_included" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-bold text-gray-600 mb-1">وصف (عربي)</label>
                <textarea name="description_ar" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 resize-none"></textarea>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-bold text-gray-600 mb-1">وصف (إنجليزي)</label>
                <textarea name="description_en" rows="2" dir="ltr" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 resize-none"></textarea>
            </div>
            <label class="col-span-2 flex items-center gap-2 text-sm font-bold text-gray-600">
                <input type="checkbox" name="free_delivery" class="accent-primary"> توصيل مجاني مع الباقة
            </label>
            <button type="submit" class="col-span-2 hero-gradient text-white py-3 rounded-xl font-black text-sm">إضافة الباقة</button>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($packages as $p)
        <div class="card p-4 flex items-center justify-between gap-3">
            <div>
                <p class="font-black text-primary">{{ $p->name_ar }}</p>
                <p class="text-xs text-gray-500">{{ $p->pieces_included }} قطعة — {{ $p->price }} ريال {{ $p->free_delivery ? '+ توصيل مجاني' : '' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $p->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $p->active ? 'فعالة' : 'متوقفة' }}</span>
                <form method="POST" action="{{ route('admin.packages.toggle', $p->id) }}">
                    @csrf
                    <button type="submit" class="text-xs text-secondary font-bold">{{ $p->active ? 'تعطيل' : 'تفعيل' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.packages.destroy', $p->id) }}" onsubmit="return confirm('حذف الباقة؟')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 font-bold">حذف</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card p-8 text-center text-gray-400 font-bold text-sm">لا توجد باقات حالياً</div>
        @endforelse
    </div>
</div>
@endsection
