@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.customers.index') }}" class="text-primary rtl-flip">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-xl font-black text-primary">{{ $customer->name }}</h1>
    </div>

    <div class="card p-5 text-center">
        <div class="w-16 h-16 hero-gradient rounded-full flex items-center justify-center text-white font-black text-2xl mx-auto mb-3">
            {{ mb_substr($customer->name, 0, 1) }}
        </div>
        <h2 class="font-black text-primary">{{ $customer->name }}</h2>
        <p class="text-gray-500 text-sm">{{ $customer->phone }}</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-secondary">{{ $customer->total_orders }}</div>
            <div class="text-xs text-gray-500">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total Orders' }}</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-primary">{{ intdiv($customer->total_orders, 4) }}</div>
            <div class="text-xs text-gray-500">{{ app()->getLocale() === 'ar' ? 'غسيلات مجانية' : 'Free Washes' }}</div>
        </div>
    </div>

    <div class="card p-5 space-y-2">
        @foreach([
            ['✉️', $customer->email ?: '—'],
            ['📍', $customer->address ?: '—'],
            ['📅', $customer->created_at->format('Y-m-d')],
        ] as [$icon, $val])
        <div class="flex items-center gap-3 py-1.5 border-b border-gray-50 last:border-0">
            <span class="text-base">{{ $icon }}</span>
            <span class="text-sm text-gray-700">{{ $val }}</span>
        </div>
        @endforeach
    </div>

    @if($orders->count())
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3">{{ app()->getLocale() === 'ar' ? 'طلباته' : 'Orders' }}</h3>
        <div class="space-y-2">
            @foreach($orders as $order)
            <a href="{{ route('admin.orders.show', $order->id) }}" class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0 hover:bg-gray-50 px-2 rounded-xl transition">
                <div>
                    <div class="font-bold text-xs text-primary">{{ $order->order_number }}</div>
                    <div class="text-xs text-gray-400">{{ $order->service_name_ar }}</div>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full badge-{{ $order->status }}">{{ $order->status_label }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
