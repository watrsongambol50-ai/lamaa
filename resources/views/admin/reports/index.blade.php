@extends('layouts.app')
@section('content')
<div class="animate-up space-y-5">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.index') }}" class="text-primary rtl-flip">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-lg font-black text-primary">التقارير المالية</h1>
        <span></span>
    </div>

    {{-- Date range filter --}}
    <div class="card p-4">
        <form method="GET" class="flex gap-2 items-end flex-wrap">
            <div class="flex-1 min-w-28">
                <label class="block text-xs font-bold text-gray-600 mb-1">من</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
            </div>
            <div class="flex-1 min-w-28">
                <label class="block text-xs font-bold text-gray-600 mb-1">إلى</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm bg-gray-50">
            </div>
            <button type="submit" class="hero-gradient text-white px-4 py-2 rounded-xl text-sm font-bold">تحديث</button>
        </form>
        <div class="flex gap-2 mt-2 text-xs">
            <a href="{{ route('admin.reports.index', ['from'=>now()->startOfMonth()->format('Y-m-d'),'to'=>now()->format('Y-m-d')]) }}" class="text-secondary font-bold">هذا الشهر</a>
            <a href="{{ route('admin.reports.index', ['from'=>now()->subDays(7)->format('Y-m-d'),'to'=>now()->format('Y-m-d')]) }}" class="text-secondary font-bold">آخر 7 أيام</a>
            <a href="{{ route('admin.reports.index', ['from'=>now()->startOfYear()->format('Y-m-d'),'to'=>now()->format('Y-m-d')]) }}" class="text-secondary font-bold">هذه السنة</a>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-secondary">{{ number_format($totalRevenue, 2) }}</div>
            <div class="text-xs text-gray-500">إجمالي الإيرادات (ريال)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-primary">{{ $totalOrders }}</div>
            <div class="text-xs text-gray-500">عدد الطلبات</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-green-600">{{ number_format($cashRevenue, 2) }}</div>
            <div class="text-xs text-gray-500">نقدي عند الاستلام</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-blue-600">{{ number_format($onlineRevenue, 2) }}</div>
            <div class="text-xs text-gray-500">دفع إلكتروني (ميسر)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-amber-600">{{ number_format($totalDiscount, 2) }}</div>
            <div class="text-xs text-gray-500">خصومات ممنوحة (كوبونات)</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-black text-red-500">{{ $cancelledCount }}</div>
            <div class="text-xs text-gray-500">طلبات ملغاة</div>
        </div>
    </div>

    {{-- Daily revenue bar chart (no external chart lib needed) --}}
    <div class="card p-4">
        <h3 class="font-black text-primary mb-3 text-sm">الإيرادات اليومية</h3>
        @if($byDay->count())
        <div class="flex items-end gap-1.5 overflow-x-auto pb-1" style="height:120px;">
            @foreach($byDay as $d)
            <div class="flex flex-col items-center flex-shrink-0" style="width:28px;">
                <div class="w-full bg-secondary/70 rounded-t-md" style="height: {{ max(4, ($d->revenue / $maxDayRevenue) * 90) }}px;" title="{{ $d->day }}: {{ number_format($d->revenue,2) }} ريال"></div>
                <span class="text-[9px] text-gray-400 mt-1 whitespace-nowrap">{{ \Carbon\Carbon::parse($d->day)->format('d/m') }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-xs text-gray-400 text-center py-6">لا توجد بيانات في هذه الفترة</p>
        @endif
    </div>

    {{-- Top services --}}
    <div class="card p-4">
        <h3 class="font-black text-primary mb-3 text-sm">أكثر الخدمات طلباً</h3>
        <div class="space-y-2">
            @forelse($byService as $s)
            <div class="flex justify-between items-center text-xs py-2 border-b border-gray-50 last:border-0">
                <span class="font-bold text-primary">{{ $s->service_name_ar }}</span>
                <span class="text-gray-500">{{ $s->orders_count }} طلب · {{ $s->pieces }} قطعة</span>
                <span class="font-bold text-secondary">{{ number_format($s->revenue, 2) }} ريال</span>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">لا توجد بيانات</p>
            @endforelse
        </div>
    </div>

    {{-- Status breakdown --}}
    <div class="card p-4">
        <h3 class="font-black text-primary mb-3 text-sm">توزيع حالات الطلبات</h3>
        @php
        $statusLabels = ['new'=>'جديد','picked_up'=>'تم الاستلام','washing'=>'قيد الغسيل','ready'=>'جاهز','out_for_delivery'=>'قيد التوصيل','delivered'=>'تم التسليم','cancelled'=>'ملغي'];
        @endphp
        <div class="grid grid-cols-2 gap-2">
            @foreach($statusLabels as $k=>$l)
            <div class="flex justify-between items-center bg-gray-50 rounded-lg px-3 py-2">
                <span class="text-xs font-bold text-gray-600">{{ $l }}</span>
                <span class="text-sm font-black text-primary">{{ $statusBreakdown[$k] ?? 0 }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
