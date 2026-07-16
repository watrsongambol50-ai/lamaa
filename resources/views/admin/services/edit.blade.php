@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <h1 class="text-xl font-black text-primary text-center">{{ app()->getLocale() === 'ar' ? 'تعديل الخدمة' : 'Edit Service' }}</h1>

    <div class="card p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-4 text-sm">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.update', $service->id) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale() === 'ar' ? 'الاسم (عربي)' : 'Name (Arabic)' }} *</label>
                <input type="text" name="name_ar" value="{{ old('name_ar', $service->name_ar) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale() === 'ar' ? 'الاسم (إنجليزي)' : 'Name (English)' }} *</label>
                <input type="text" name="name_en" value="{{ old('name_en', $service->name_en) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white transition" dir="ltr">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale() === 'ar' ? 'السعر الأساسي (ريال)' : 'Base Price (SAR)' }} *</label>
                <input type="number" name="base_price" value="{{ old('base_price', $service->base_price) }}" required min="0" step="0.5"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white transition" dir="ltr">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ app()->getLocale() === 'ar' ? 'ملاحظة (اختياري)' : 'Notes (optional)' }}</label>
                <input type="text" name="description" value="{{ old('description', $service->description) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white transition" dir="ltr">
            </div>

            <button type="submit" class="w-full hero-gradient text-white py-3.5 rounded-xl font-black hover:opacity-90 transition">
                {{ app()->getLocale() === 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}
            </button>
        </form>
    </div>

    <a href="{{ route('admin.services.index') }}" class="block text-center text-secondary text-sm font-bold">
        {{ app()->getLocale() === 'ar' ? '← رجوع' : '← Back' }}
    </a>
</div>
@endsection
