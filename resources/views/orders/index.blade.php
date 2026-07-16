@extends('layouts.app')
@section('content')
<div class="animate-up">
    <h1 class="text-xl font-black text-primary mb-5 text-center">{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}</h1>

    {{-- Filter tabs --}}
    <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
        @php
        $filters = [
            '' => app()->getLocale() === 'ar' ? 'الكل' : 'All',
            'new' => app()->getLocale() === 'ar' ? 'طلب جديد' : 'New',
            'picked_up' => app()->getLocale() === 'ar' ? 'تم الاستلام' : 'Picked Up',
            'washing' => app()->getLocale() === 'ar' ? 'قيد الغسيل' : 'Washing',
            'ready' => app()->getLocale() === 'ar' ? 'جاهز للتسليم' : 'Ready',
            'out_for_delivery' => app()->getLocale() === 'ar' ? 'قيد التوصيل' : 'Delivering',
            'delivered' => app()->getLocale() === 'ar' ? 'تم التسليم' : 'Delivered',
            'cancelled' => app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled',
        ];
        $active = request('status', '');
        @endphp
        @foreach($filters as $val => $label)
        <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
            class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-bold transition {{ $active === $val ? 'hero-gradient text-white' : 'bg-white text-gray-500 border border-gray-200' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders list --}}
    @if($orders->count())
    <div class="space-y-3">
        @foreach($orders as $order)
        <a href="{{ route('orders.show', $order->id) }}" class="card p-4 flex items-center gap-3 hover:shadow-md transition">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-black text-primary text-sm">{{ app()->getLocale() === 'ar' ? $order->service_name_ar : $order->service_name }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $order->order_number }}</div>
                <div class="text-xs text-gray-400">{{ $order->quantity }} {{ app()->getLocale() === 'ar' ? 'قطعة' : 'pieces' }} · {{ $order->pickup_date }}</div>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="text-xs font-bold px-2.5 py-1 rounded-full badge-{{ $order->status }}">
                    {{ $order->status_label }}
                </span>
                <div class="text-xs font-black text-primary mt-1 flex items-center justify-end gap-1">
                    @if($order->total_price == 0)
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        <span class="text-green-600">{{ app()->getLocale()==='ar'?'مجاني':'Free' }}</span>
                    @else
                        {{ $order->total_price }} {{ app()->getLocale()==='ar'?'ر':'SAR' }}
                    @endif
                </div>
                @if($order->isCancellable())
                <span class="inline-block mt-1 text-xs text-red-500 font-bold">{{ app()->getLocale()==='ar' ? 'قابل للإلغاء' : 'Cancellable' }}</span>
                @endif
            </div>
            <svg class="w-4 h-4 text-gray-300 flex-shrink-0 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endforeach
    </div>
    @else
    <div class="card p-10 text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <p class="font-black text-gray-600 mb-1">{{ app()->getLocale() === 'ar' ? 'لا يوجد طلبات' : 'No orders' }}</p>
        <p class="text-xs text-gray-400 mb-5">{{ app()->getLocale() === 'ar' ? 'ابدأ بطلبك الأول الآن!' : 'Start with your first order!' }}</p>
        <a href="{{ route('orders.create') }}" class="inline-block hero-gradient text-white px-6 py-2.5 rounded-xl font-bold text-sm">
            {{ app()->getLocale() === 'ar' ? 'طلب جديد' : 'New Order' }}
        </a>
    </div>
    @endif
</div>
@endsection
