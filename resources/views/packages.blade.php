@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <h1 class="text-xl font-black text-primary text-center">{{ app()->getLocale()==='ar'?'باقات الاشتراك':'Subscription Packages' }}</h1>
    <p class="text-gray-500 text-sm text-center">{{ app()->getLocale()==='ar'?'وفّر أكثر مع باقاتنا الشهرية':'Save more with our monthly packages' }}</p>

    <div class="space-y-3">
        @forelse($packages as $p)
        <div class="card p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="font-black text-primary">{{ app()->getLocale()==='ar'?$p->name_ar:$p->name_en }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ app()->getLocale()==='ar'?$p->description_ar:$p->description_en }}</p>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black text-secondary">{{ $p->price }}</div>
                    <div class="text-xs text-gray-400">{{ app()->getLocale()==='ar'?'ريال / شهرياً':'SAR / month' }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-primary bg-blue-50 rounded-xl px-3 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $p->pieces_included }} {{ app()->getLocale()==='ar'?'قطعة مشمولة':'pieces included' }}
                @if($p->free_delivery) &middot; {{ app()->getLocale()==='ar'?'توصيل مجاني':'Free delivery' }} @endif
            </div>
            <a href="{{ route('orders.create') }}" class="mt-3 block w-full text-center hero-gradient text-white py-2.5 rounded-xl font-black text-sm">
                {{ app()->getLocale()==='ar'?'اطلب الآن':'Order Now' }}
            </a>
        </div>
        @empty
        <div class="card p-10 text-center text-gray-400 font-bold text-sm">{{ app()->getLocale()==='ar'?'لا توجد باقات متاحة حالياً':'No packages available right now' }}</div>
        @endforelse
    </div>
</div>
@endsection
