@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-primary">{{ app()->getLocale() === 'ar' ? 'إدارة الطلبات' : 'Orders Management' }}</h1>
        <a href="{{ route('admin.index') }}" class="text-xs text-secondary font-bold">‹ {{ app()->getLocale() === 'ar' ? 'الإدارة' : 'Admin' }}</a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'بحث برقم الطلب أو اسم العميل...' : 'Search orders...' }}"
            class="flex-1 border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white focus:border-primary transition">
        <button type="submit" class="hero-gradient text-white px-4 rounded-xl text-sm font-bold">{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}</button>
    </form>

    {{-- Status filter tabs --}}
    <div class="flex gap-2 overflow-x-auto pb-1">
        @php $statuses = ['' => 'الكل','new'=>'جديد','picked_up'=>'تم الاستلام','washing'=>'قيد الغسيل','ready'=>'جاهز','out_for_delivery'=>'قيد التوصيل','delivered'=>'تم التسليم','cancelled'=>'ملغي']; @endphp
        @foreach($statuses as $val => $label)
        <a href="{{ route('admin.orders.index', $val ? ['status'=>$val] : []) }}"
            class="whitespace-nowrap text-xs px-3 py-1.5 rounded-full font-bold {{ request('status','') === $val ? 'hero-gradient text-white' : 'bg-white border text-gray-500' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders --}}
    <div class="space-y-2">
        @foreach($orders as $order)
        <a href="{{ route('admin.orders.show', $order->id) }}" class="card p-4 flex items-center gap-3 hover:shadow-md transition">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="font-black text-primary text-sm">{{ $order->order_number }}</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full badge-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                <div class="text-xs text-gray-500">{{ $order->customer->name ?? '—' }} · {{ $order->created_at->format('Y-m-d') }}</div>
                <div class="text-xs text-gray-400">{{ $order->service_name_ar }} × {{ $order->quantity }}</div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="font-black text-sm text-primary">{{ $order->total_price == 0 ? '🎁' : $order->total_price.' ر' }}</div>
                <svg class="w-4 h-4 text-gray-300 mt-1 ms-auto rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="py-2">{{ $orders->links() }}</div>
</div>
@endsection
