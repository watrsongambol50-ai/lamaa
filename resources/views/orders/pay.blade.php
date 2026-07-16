@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <div class="flex items-center justify-between">
        <a href="{{ route('orders.show', $order->id) }}" class="text-primary rtl-flip">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="font-black text-primary">{{ app()->getLocale()==='ar'?'إتمام الدفع':'Complete Payment' }}</span>
        <span></span>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="card p-5 text-center">
        <p class="text-xs text-gray-500 mb-1">{{ app()->getLocale()==='ar'?'المبلغ المطلوب':'Amount Due' }}</p>
        <p class="text-3xl font-black text-primary">{{ number_format($order->total_price, 2) }} <span class="text-base">{{ app()->getLocale()==='ar'?'ريال':'SAR' }}</span></p>
        <p class="text-xs text-gray-400 mt-1">{{ __('messages.order') ?? 'Order' }} {{ $order->order_number }}</p>
    </div>

    <div class="card p-5">
        <div id="moyasar-form"></div>
    </div>

    <p class="text-xs text-gray-400 text-center leading-relaxed">
        {{ app()->getLocale()==='ar'
            ? 'الدفع مؤمّن بالكامل عبر بوابة ميسر (Moyasar) — لا تُخزَّن بيانات بطاقتك في تطبيقنا.'
            : 'Payment is fully secured by Moyasar — your card details are never stored on our servers.' }}
    </p>
</div>

<link rel="stylesheet" href="https://unpkg.com/[email protected]/dist/moyasar.css">
<script src="https://unpkg.com/[email protected]/dist/moyasar.umd.js"></script>
<script>
document.getElementById('moyasar-form').innerHTML = '<div class="mysr-form"></div>';
Moyasar.init({
    element: '.mysr-form',
    amount: {{ (int) round($order->total_price * 100) }}, // Moyasar expects halalas (SAR x 100)
    currency: 'SAR',
    description: 'LAMAA Order {{ $order->order_number }}',
    publishable_api_key: '{{ config('services.moyasar.publishable_key') }}',
    callback_url: '{{ route('orders.pay.callback', $order->id) }}',
    methods: ['creditcard', 'applepay'],
});
</script>
@endsection
