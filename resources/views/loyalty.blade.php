@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <h1 class="text-xl font-black text-primary text-center">{{ app()->getLocale() === 'ar' ? 'برنامج الولاء' : 'Loyalty Program' }}</h1>

    {{-- Main progress card --}}
    <div class="hero-gradient rounded-2xl p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <div class="text-right">
                <p class="text-white/70 text-xs">{{ app()->getLocale() === 'ar' ? 'تقدمك' : 'Progress' }}</p>
                <p class="text-4xl font-black">{{ $user->total_orders }}</p>
                <p class="text-white/70 text-xs">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total orders' }}</p>
            </div>
        </div>
        <div class="w-full bg-white/20 rounded-full h-3 mb-2">
            <div class="bg-secondary h-3 rounded-full transition-all" style="width: {{ min(($loyaltyProgress / 5) * 100, 100) }}%"></div>
        </div>
        <p class="text-white/80 text-xs">
            {{ app()->getLocale() === 'ar' ? "$nextFree طلب متبقي حتى طلبك المجاني القادم" : "$nextFree more orders until your next free order" }}
        </p>
    </div>

    {{-- Rule: every 5th order is fully free --}}
    <div class="card p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="font-black text-primary text-sm">{{ app()->getLocale() === 'ar' ? 'كل 4 طلبات مدفوعة = الطلب الخامس مجاني بالكامل' : '4 paid orders = the 5th order is fully free' }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ app()->getLocale() === 'ar' ? 'تُحتسب لكل طلب تطلبه معنا، وتبدأ الدورة من جديد بعد كل طلب مجاني' : 'Counted per order placed, the cycle restarts after every free order' }}</p>
            </div>
            <span class="text-2xl font-black text-secondary">5</span>
        </div>
        <div class="flex gap-2 flex-wrap">
            @for($i=1;$i<=5;$i++)
            @php $filled = $loyaltyProgress >= $i; @endphp
            @if($i < 5)
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black {{ $filled ? 'bg-secondary text-white' : 'bg-gray-100 text-gray-400' }}">
                @if($filled)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                @else
                    {{ $i }}
                @endif
            </div>
            @else
            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
            </div>
            @endif
            @endfor
        </div>
    </div>

    {{-- Free delivery every 3rd order --}}
    <div class="card p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="font-black text-primary text-sm">{{ app()->getLocale() === 'ar' ? 'كل 3 طلبات = توصيل مجاني' : 'Every 3 orders = free delivery' }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ app()->getLocale() === 'ar' ? 'طلبك الثالث وكل طلب ثالث بعده يحصل على توصيل مجاني' : 'Your 3rd order (and every 3rd after) gets free delivery' }}</p>
            </div>
            <span class="text-2xl font-black text-primary">3</span>
        </div>
        <div class="flex gap-1.5 flex-wrap">
            @for($i=1;$i<=3;$i++)
            @php $filled3 = $freeDeliveryProgress >= $i; @endphp
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $filled3 ? 'hero-gradient text-white' : ($i === 3 ? 'bg-green-100 text-green-600 border-2 border-green-300' : 'bg-gray-100 text-gray-400') }}">
                @if($i === 3)
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9M3 5h18M3 5a2 2 0 002 2h14a2 2 0 002-2"/></svg>
                @elseif($filled3)
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                @else
                    {{ $i }}
                @endif
            </div>
            @endfor
        </div>
        <p class="text-xs text-gray-400 mt-2">{{ app()->getLocale() === 'ar' ? "$nextFreeDelivery طلب متبقي حتى التوصيل المجاني القادم" : "$nextFreeDelivery orders left until next free delivery" }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="card p-4 text-center">
            <div class="text-3xl font-black text-secondary">{{ $freePiecesTotal }}</div>
            <div class="text-xs text-gray-500">{{ app()->getLocale() === 'ar' ? 'قطع مجانية مكتسبة (من الطلبات المجانية)' : 'Free pieces earned (from free orders)' }}</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-3xl font-black text-primary">{{ $user->total_orders }}</div>
            <div class="text-xs text-gray-500">{{ app()->getLocale() === 'ar' ? 'إجمالي الطلبات' : 'Total orders' }}</div>
        </div>
    </div>
</div>
@endsection
