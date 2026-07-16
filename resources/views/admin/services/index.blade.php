@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-primary">{{ app()->getLocale() === 'ar' ? 'إدارة الأسعار' : 'Price Management' }}</h1>
            <p class="text-xs text-gray-500">{{ $services->count() }} {{ app()->getLocale() === 'ar' ? 'خدمة' : 'services' }}</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="hero-gradient text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ app()->getLocale() === 'ar' ? 'إضافة خدمة' : 'Add Service' }}
        </a>
    </div>

    {{-- Services list --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs font-bold">
                        <th class="px-4 py-3 text-right">#</th>
                        <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'ar' ? 'الاسم (عربي)' : 'Name (AR)' }}</th>
                        <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'ar' ? 'الاسم (إنجليزي)' : 'Name (EN)' }}</th>
                        <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</th>
                        <th class="px-4 py-3 text-right">{{ app()->getLocale() === 'ar' ? 'وصف / ملاحظة' : 'Description' }}</th>
                        <th class="px-4 py-3 text-center">{{ app()->getLocale() === 'ar' ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($services as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $service->id }}</td>
                        <td class="px-4 py-3 font-bold text-primary">{{ $service->name_ar }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $service->name_en }}</td>
                        <td class="px-4 py-3 font-black text-secondary">{{ $service->base_price }} {{ app()->getLocale() === 'ar' ? 'ر' : 'SAR' }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs max-w-xs truncate">{{ $service->description ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.services.edit', $service->id) }}"
                                    class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-xs font-bold hover:bg-blue-100 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    {{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}"
                                    onsubmit="return confirm('{{ app()->getLocale()==='ar' ? 'هل تريد حذف هذه الخدمة؟' : 'Delete this service?' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-700 px-2.5 py-1 rounded-lg text-xs font-bold hover:bg-red-100 transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        {{ app()->getLocale() === 'ar' ? 'حذف' : 'Delete' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-start">
        <a href="{{ route('admin.index') }}" class="text-secondary text-sm font-bold flex items-center gap-1">
            <svg class="w-4 h-4 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ app()->getLocale() === 'ar' ? 'العودة للوحة الإدارة' : 'Back to Admin' }}
        </a>
    </div>
</div>
@endsection
