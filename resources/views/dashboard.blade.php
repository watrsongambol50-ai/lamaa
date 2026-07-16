@extends('layouts.app')
@section('content')
<div class="space-y-4">

    {{-- Welcome Card --}}
    <div class="hero-gradient rounded-2xl p-5 text-white relative overflow-hidden animate-up">
        <div class="absolute -left-4 -bottom-4 opacity-10">
            <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <p class="text-white/70 text-xs mb-1">{{ app()->getLocale() === 'ar' ? 'أهلاً بك' : 'Welcome' }}</p>
        <h1 class="text-2xl font-black leading-tight">{{ $user->name }}</h1>
        <div class="flex items-center gap-1.5 text-white/70 text-sm mt-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>{{ $user->total_orders }} {{ app()->getLocale() === 'ar' ? 'طلب سابق' : 'previous orders' }}</span>
        </div>
    </div>

    {{-- New Order CTA --}}
    <a href="{{ route('orders.create') }}" class="block rounded-2xl p-4 text-center text-white font-black text-lg animate-up animate-up-1"
        style="background: linear-gradient(90deg, #00b4d8, #48cae4);">
        <div class="flex items-center justify-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>{{ app()->getLocale() === 'ar' ? 'طلب جديد' : 'New Order' }}</span>
        </div>
    </a>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 gap-3 animate-up animate-up-2">
        <a href="{{ route('notifications.index') }}" class="card p-4 flex flex-col items-center gap-2 hover:shadow-md transition">
            <div class="relative">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $unreadCount }}</span>
                @endif
            </div>
            <span class="text-sm font-bold text-gray-700">{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}</span>
        </a>
        <a href="{{ route('orders.index') }}" class="card p-4 flex flex-col items-center gap-2 hover:shadow-md transition">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-sm font-bold text-gray-700">{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}</span>
        </a>
    </div>

    {{-- Loyalty Progress --}}
    <div class="card p-5 animate-up animate-up-3">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="font-black text-primary">{{ app()->getLocale() === 'ar' ? 'برنامج الولاء' : 'Loyalty Program' }}</span>
            </div>
            <a href="{{ route('loyalty') }}" class="text-secondary text-xs font-bold">›</a>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2.5 mb-2">
            <div class="hero-gradient h-2.5 rounded-full transition-all" style="width: {{ min(($loyaltyProgress / 5) * 100, 100) }}%"></div>
        </div>
        <p class="text-xs text-gray-500">
            {{ app()->getLocale() === 'ar' ? "$nextFree طلبات حتى طلبك المجاني" : "$nextFree orders until your free wash" }}
        </p>
    </div>

    {{-- Recent Orders --}}
    @if($orders->count())
    <div class="animate-up">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-black text-primary">{{ app()->getLocale() === 'ar' ? 'آخر النشاطات' : 'Recent Activity' }}</h2>
            <a href="{{ route('orders.index') }}" class="text-secondary text-xs font-bold">{{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}</a>
        </div>
        <div class="space-y-2">
            @foreach($orders as $order)
            <a href="{{ route('orders.show', $order->id) }}" class="card p-4 flex items-center justify-between hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-primary">{{ app()->getLocale() === 'ar' ? $order->service_name_ar : $order->service_name }}</div>
                        <div class="text-xs text-gray-400">{{ $order->order_number }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    <div class="text-xs text-gray-400 mt-1">{{ $order->total_price == 0 ? (app()->getLocale()==='ar'?'مجاني':'Free') : $order->total_price.' '.(app()->getLocale()==='ar'?'ريال':'SAR') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="card p-8 text-center animate-up">
        <div class="flex justify-center mb-3">
            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <p class="font-bold text-gray-600 mb-1">{{ app()->getLocale() === 'ar' ? 'لا يوجد طلبات بعد' : 'No orders yet' }}</p>
        <p class="text-xs text-gray-400 mb-4">{{ app()->getLocale() === 'ar' ? 'اطلب خدمتك الأولى الآن!' : 'Place your first order!' }}</p>
        <a href="{{ route('orders.create') }}" class="inline-block hero-gradient text-white px-6 py-2.5 rounded-xl font-bold text-sm">
            {{ app()->getLocale() === 'ar' ? 'اطلب الآن' : 'Order Now' }}
        </a>
    </div>
    @endif

</div>
@endsection
