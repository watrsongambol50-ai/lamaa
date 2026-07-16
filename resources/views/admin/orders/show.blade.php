@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-primary rtl-flip">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="font-black text-primary">{{ $order->order_number }}</span>
        <span class="text-xs font-bold px-3 py-1 rounded-full badge-{{ $order->status }}">{{ $order->status_label }}</span>
    </div>

    {{-- Barcode --}}
    <div class="card p-4 text-center">
        <p class="text-xs text-gray-500 mb-2">{{ app()->getLocale() === 'ar' ? 'الباركود' : 'Barcode' }}</p>
        <div class="font-mono text-sm font-bold text-primary tracking-widest bg-gray-50 rounded-xl py-3 px-4">{{ $order->barcode ?? ('BAR'.$order->id) }}</div>
    </div>

    {{-- Order info --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3">{{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Info' }}</h3>
        <div class="space-y-2.5">
            @foreach([
                ['العميل', $order->customer->name ?? '—'],
                ['الجوال', $order->customer->phone ?? '—'],
                ['الخدمة', $order->service_name_ar],
                ['الكمية', $order->quantity.' قطعة'],
                ['الإجمالي', $order->total_price==0?'مجاني 🎁':$order->total_price.' ريال'],
                ['تاريخ الاستلام', $order->pickup_date],
                ['العنوان', $order->address ?: '—'],
                ['طريقة الدفع', $order->payment_method === 'online' ? ('إلكتروني — ' . ($order->payment_status === 'paid' ? 'مدفوع ✅' : 'غير مكتمل')) : 'الدفع عند الاستلام'],
                ['ملاحظات', $order->notes ?: 'لا شيء'],
            ] as [$label, $val])
            <div class="flex justify-between py-1.5 border-b border-gray-50 last:border-0">
                <span class="text-xs text-gray-500">{{ $label }}</span>
                <span class="text-xs font-bold text-primary">{{ $val }}</span>
            </div>
            @endforeach
        </div>
        @if($order->status === 'cancelled' && $order->cancelled_at)
        <div class="mt-3 bg-red-50 rounded-xl p-3">
            <p class="text-xs font-bold text-red-700">تم إلغاء الطلب من قبل العميل بتاريخ {{ $order->cancelled_at->format('Y-m-d H:i') }}</p>
            @if($order->cancel_reason)<p class="text-xs text-red-600 mt-1">السبب: {{ $order->cancel_reason }}</p>@endif
        </div>
        @endif
    </div>

    {{-- Customer location map — helps the delivery driver find the address --}}
    @if($order->lat && $order->lng)
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            موقع العميل
        </h3>
        <div class="rounded-xl overflow-hidden border border-gray-100" style="height:220px;">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $order->lng-0.01 }}%2C{{ $order->lat-0.01 }}%2C{{ $order->lng+0.01 }}%2C{{ $order->lat+0.01 }}&layer=mapnik&marker={{ $order->lat }}%2C{{ $order->lng }}"
                style="width:100%;height:100%;border:0;" loading="lazy"></iframe>
        </div>
        <a href="https://www.google.com/maps?q={{ $order->lat }},{{ $order->lng }}" target="_blank" class="mt-3 flex items-center justify-center gap-2 text-secondary font-bold text-sm py-2 rounded-xl border-2 border-secondary/30">
            فتح الموقع في خرائط قوقل للتوصيل
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
    @endif

    {{-- Update status --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3">{{ app()->getLocale() === 'ar' ? 'تحديث الحالة' : 'Update Status' }}</h3>
        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-3">
            @csrf
            <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                @php $statusOptions = ['new'=>'طلب جديد','picked_up'=>'تم الاستلام','washing'=>'قيد الغسيل','ready'=>'جاهز للتسليم','out_for_delivery'=>'قيد التوصيل','delivered'=>'تم التسليم','cancelled'=>'ملغي']; @endphp
                @foreach($statusOptions as $val => $label)
                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <textarea name="damage_note" rows="2" placeholder="{{ app()->getLocale() === 'ar' ? 'ملاحظة للعميل (بقع، تلف...)' : 'Note for customer (stains, damage...)' }}"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none bg-gray-50 focus:bg-white transition">{{ $order->damage_note_photo_url }}</textarea>

            <button type="submit" class="w-full hero-gradient text-white py-3 rounded-xl font-bold text-sm">
                {{ app()->getLocale() === 'ar' ? 'تحديث الحالة' : 'Update Status' }}
            </button>
        </form>
    </div>
</div>
@endsection
