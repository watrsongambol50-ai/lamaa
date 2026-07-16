@extends('layouts.app')
@section('content')
<div class="animate-up space-y-5">

    {{-- Header stats --}}
    <div class="hero-gradient -mx-4 -mt-4 text-white px-6 pt-8 pb-10 mb-2">
        <div class="flex items-center gap-3 mb-4">
            <div class="logo-wrap rounded-xl flex items-center justify-center shadow" style="width:48px;height:48px;background:white!important;">
                <img src="/images/lamaa-logo.png" alt="لماء" style="width:38px;height:38px;object-fit:contain;">
            </div>
            <div>
                <h1 class="text-xl font-black">لوحة الإدارة</h1>
                <p class="text-white/70 text-xs">مرحباً {{ auth()->user()->name }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['num'=>$totalOrders,        'label'=>'إجمالي الطلبات','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                ['num'=>$pendingOrders,      'label'=>'قيد التنفيذ',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['num'=>$todayOrders,        'label'=>'طلبات اليوم',   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>'],
            ] as $s)
            <div class="bg-white/15 rounded-xl p-3 text-center backdrop-blur-sm">
                <div class="flex justify-center mb-1"><svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $s['icon'] !!}</svg></div>
                <div class="text-2xl font-black">{{ $s['num'] }}</div>
                <div class="text-white/70 text-xs">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Expanded stats --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-secondary">{{ $revenue }}</div>
            <div class="text-xs text-gray-500">إجمالي الإيرادات (ريال)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-primary">{{ $todayRevenue }}</div>
            <div class="text-xs text-gray-500">إيرادات اليوم (ريال)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-primary">{{ $monthRevenue }}</div>
            <div class="text-xs text-gray-500">إيرادات الشهر (ريال)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-secondary">{{ $avgOrderValue }}</div>
            <div class="text-xs text-gray-500">متوسط قيمة الطلب</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-green-600">{{ $totalCustomers }}</div>
            <div class="text-xs text-gray-500">إجمالي العملاء</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-green-600">{{ $freePiecesGiven }}</div>
            <div class="text-xs text-gray-500">قطع مجانية (ولاء)</div>
        </div>
    </div>

    {{-- Quick links --}}
    <div class="flex gap-3 flex-wrap">
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.services.index') }}" class="hero-gradient text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            الأسعار والخدمات
        </a>
        @endif
        <a href="{{ route('admin.customers.index') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            العملاء
        </a>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.coupons.index') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            الكوبونات
        </a>
        <a href="{{ route('admin.packages.index') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            الباقات
        </a>
        <a href="{{ route('admin.staff.index') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            فريق العمل
        </a>
        <a href="{{ route('admin.reports.index') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            التقارير المالية
        </a>
        @endif
        <a href="{{ route('admin.pos.create') }}" class="card px-4 py-2.5 text-primary font-bold text-sm flex items-center gap-2 border border-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            الكاشير (طلب حضوري)
        </a>
    </div>

    {{-- Filters --}}
    <div class="card p-4">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="status" onchange="this.form.submit()" class="flex-1 min-w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 focus:bg-white">
                <option value="">كل الحالات</option>
                @foreach(['new'=>'جديد','picked_up'=>'تم الاستلام','washing'=>'يُغسل','ready'=>'جاهز','out_for_delivery'=>'جاري التوصيل','delivered'=>'تم التسليم','cancelled'=>'ملغي'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث..." class="flex-1 min-w-28 border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
            <button type="submit" class="hero-gradient text-white px-4 py-2 rounded-xl text-sm font-bold">بحث</button>
        </form>
    </div>

    {{-- Orders list --}}
    <div class="space-y-3">
        @forelse($allOrders as $order)
        @php
        $statusMap=['new'=>['جديد','bg-blue-100 text-blue-700'],'picked_up'=>['استُلم','bg-yellow-100 text-yellow-700'],'washing'=>['يُغسل','bg-purple-100 text-purple-700'],'ready'=>['جاهز','bg-green-100 text-green-700'],'out_for_delivery'=>['بالطريق','bg-orange-100 text-orange-700'],'delivered'=>['سُلِّم','bg-emerald-100 text-emerald-700'],'cancelled'=>['ملغي','bg-red-100 text-red-700']];
        $s=$statusMap[$order->status]??[$order->status,'bg-gray-100 text-gray-700'];
        @endphp
        <div class="card p-4">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div>
                    <p class="font-black text-primary">#{{ $order->id }} — {{ $order->customer->name ?? 'مجهول' }}</p>
                    <p class="text-xs text-gray-500">{{ $order->customer->phone ?? '' }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold flex-shrink-0 {{ $s[1] }}">{{ $s[0] }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 mb-3">
                <div><span class="font-bold">الخدمة:</span> {{ $order->service->name_ar ?? '—' }}</div>
                <div><span class="font-bold">الكمية:</span> {{ $order->quantity }} قطعة</div>
                <div><span class="font-bold">الإجمالي:</span> <span class="text-secondary font-black">{{ $order->total_price }} ريال</span></div>
                <div><span class="font-bold">الاستلام:</span> {{ $order->pickup_date ?? '—' }}</div>
                @if($order->address)<div class="col-span-2"><span class="font-bold">العنوان:</span> {{ $order->address }}</div>@endif
                @if($order->notes)<div class="col-span-2"><span class="font-bold">ملاحظات:</span> {{ $order->notes }}</div>@endif
            </div>
            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="flex gap-2">
                @csrf
                <select name="status" class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50 focus:bg-white">
                    @foreach(['new'=>'جديد','picked_up'=>'تم الاستلام','washing'=>'يُغسل','ready'=>'جاهز','out_for_delivery'=>'جاري التوصيل','delivered'=>'تم التسليم','cancelled'=>'ملغي'] as $v=>$l)
                    <option value="{{ $v }}" {{ $order->status===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition">تحديث</button>
            </form>
        </div>
        @empty
        <div class="card p-10 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400 font-bold text-sm">لا توجد طلبات</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
