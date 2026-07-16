@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-primary">{{ app()->getLocale() === 'ar' ? 'قائمة العملاء' : 'Customers' }}</h1>
        <a href="{{ route('admin.index') }}" class="text-xs text-secondary font-bold">‹ {{ app()->getLocale() === 'ar' ? 'الإدارة' : 'Admin' }}</a>
    </div>

    <div class="space-y-2">
        @foreach($customers as $c)
        <a href="{{ route('admin.customers.show', $c->id) }}" class="card p-4 flex items-center gap-3 hover:shadow-md transition">
            <div class="w-10 h-10 hero-gradient rounded-full flex items-center justify-center text-white font-black text-base flex-shrink-0">
                {{ mb_substr($c->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-primary">{{ $c->name }}</div>
                <div class="text-xs text-gray-500">{{ $c->phone }}</div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="font-black text-primary text-sm">{{ $c->total_orders }}</div>
                <div class="text-xs text-gray-400">{{ app()->getLocale() === 'ar' ? 'طلب' : 'orders' }}</div>
            </div>
            <svg class="w-4 h-4 text-gray-300 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endforeach
    </div>
</div>
@endsection
