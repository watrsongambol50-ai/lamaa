@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('orders.index') }}" class="text-primary rtl-flip">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="text-xs font-bold px-3 py-1.5 rounded-full badge-{{ $order->status }}">{{ $order->status_label }}</span>
        <span class="font-black text-primary text-sm">{{ $order->order_number }}</span>
    </div>

    {{-- Barcode card --}}
    <div class="card p-5 text-center">
        <p class="text-xs text-gray-500 mb-2">{{ app()->getLocale() === 'ar' ? 'الباركود' : 'Barcode' }}</p>
        <div class="font-mono text-sm font-bold text-primary tracking-widest bg-gray-50 rounded-xl py-3 px-4 border border-gray-200">
            {{ $order->barcode ?? ('BAR'.$order->id) }}
        </div>
        <div class="flex justify-center gap-0.5 mt-3 h-10">
            @for($i=0;$i<40;$i++)
            <div class="bg-primary rounded-full" style="width:{{ rand(1,3) }}px; height:{{ rand(20,40) }}px; margin-top: auto;"></div>
            @endfor
        </div>
    </div>

    {{-- Status steps --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-4">{{ app()->getLocale() === 'ar' ? 'تتبع الطلب' : 'Track Order' }}</h3>
        @php
        $steps_ar = ['new'=>'طلب جديد','picked_up'=>'تم الاستلام','washing'=>'جاري الغسيل','ready'=>'جاهز للتسليم','out_for_delivery'=>'جاري التوصيل','delivered'=>'تم التسليم'];
        $steps_en = ['new'=>'New Order','picked_up'=>'Picked Up','washing'=>'Washing','ready'=>'Ready','out_for_delivery'=>'Delivering','delivered'=>'Delivered'];
        $step_keys = array_keys($steps_ar);
        $cur = array_search($order->status, $step_keys);
        if($order->status === 'cancelled') $cur = -1;
        @endphp
        @if($order->status === 'cancelled')
            <div class="text-center py-4">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <p class="font-bold text-red-600">{{ app()->getLocale() === 'ar' ? 'تم إلغاء الطلب' : 'Order Cancelled' }}</p>
            </div>
        @else
        <div class="space-y-3">
            @foreach($step_keys as $i => $key)
            @php $done = $i <= $cur; $active = $i === $cur; @endphp
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 {{ $active ? 'hero-gradient text-white' : ($done ? 'bg-secondary text-white' : 'bg-gray-100 text-gray-400') }}">
                    @if($done && !$active)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @elseif($active)
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    @else
                        <span class="text-xs font-bold">{{ $i+1 }}</span>
                    @endif
                </div>
                <span class="text-sm font-{{ $active ? 'black text-primary' : ($done ? 'bold text-gray-600' : 'medium text-gray-300') }}">
                    {{ app()->getLocale() === 'ar' ? $steps_ar[$key] : $steps_en[$key] }}
                </span>
                @if($active)
                    <span class="text-xs bg-secondary/20 text-secondary px-2 py-0.5 rounded-full font-bold ms-auto">
                        {{ app()->getLocale() === 'ar' ? 'الحالة الحالية' : 'Current' }}
                    </span>
                @endif
            </div>
            @if($i < count($step_keys)-1)
            <div class="ms-3.5 w-0.5 h-3 {{ $i < $cur ? 'bg-secondary' : 'bg-gray-200' }}"></div>
            @endif
            @endforeach
        </div>
        @endif
    </div>

    {{-- Order Details --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-4">{{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Details' }}</h3>
        <div class="space-y-3">
            @foreach([
                [app()->getLocale()==='ar'?'العميل':'Client', $order->customer->name ?? '—'],
                [app()->getLocale()==='ar'?'الجوال':'Phone', $order->customer->phone ?? '—'],
                [app()->getLocale()==='ar'?'الخدمة':'Service', app()->getLocale()==='ar'?$order->service_name_ar:$order->service_name],
                [app()->getLocale()==='ar'?'الكمية':'Quantity', $order->quantity . (app()->getLocale()==='ar'?' قطعة':' pieces')],
                [app()->getLocale()==='ar'?'المجموع الفرعي':'Subtotal', ($order->subtotal_price ?? $order->total_price).' '.(app()->getLocale()==='ar'?'ريال':'SAR')],
                [app()->getLocale()==='ar'?'الخصم':'Discount', $order->discount_amount ? '-'.$order->discount_amount.' '.(app()->getLocale()==='ar'?'ريال':'SAR') : (app()->getLocale()==='ar'?'لا يوجد':'None')],
                [app()->getLocale()==='ar'?'الإجمالي':'Total', $order->total_price==0?(app()->getLocale()==='ar'?'مجاني':'Free'):$order->total_price.' '.(app()->getLocale()==='ar'?'ريال':'SAR')],
                [app()->getLocale()==='ar'?'طريقة الدفع':'Payment', $order->payment_method==='cash' ? (app()->getLocale()==='ar'?'الدفع عند الاستلام':'Pay on Delivery') : (app()->getLocale()==='ar'?'إلكتروني':'Online')],
                [app()->getLocale()==='ar'?'تاريخ الاستلام':'Pickup Date', $order->pickup_date],
                [app()->getLocale()==='ar'?'العنوان':'Address', $order->address ?? '—'],
                [app()->getLocale()==='ar'?'ملاحظات':'Notes', $order->notes ?: (app()->getLocale()==='ar'?'لا شيء':'None')],
            ] as [$label, $val])
            <div class="flex justify-between items-start gap-3 py-2 border-b border-gray-50 last:border-0">
                <span class="text-xs text-gray-500 font-medium">{{ $label }}</span>
                <span class="text-xs font-bold text-primary text-right">{{ $val }}</span>
            </div>
            @endforeach
        </div>
        @if($order->free_pieces_count > 0)
        <div class="mt-3 bg-green-50 rounded-xl p-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            <p class="text-xs font-bold text-green-700">{{ app()->getLocale() === 'ar' ? $order->free_pieces_count.' قطعة مجانية ضمن برنامج الولاء' : $order->free_pieces_count.' free piece(s) — Loyalty reward' }}</p>
        </div>
        @endif
        @if($order->free_delivery)
        <div class="mt-3 bg-blue-50 rounded-xl p-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9M3 5h18M3 5a2 2 0 002 2h14a2 2 0 002-2"/></svg>
            <p class="text-xs font-bold text-blue-700">{{ app()->getLocale() === 'ar' ? 'توصيل مجاني على هذا الطلب 🎉' : 'Free delivery on this order 🎉' }}</p>
        </div>
        @endif
    </div>

    {{-- Items breakdown --}}
    @if($order->items && $order->items->count())
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3">{{ app()->getLocale() === 'ar' ? 'تفاصيل القطع' : 'Items Breakdown' }}</h3>
        <div class="space-y-2">
            @foreach($order->items as $item)
            <div class="flex justify-between items-center text-xs py-2 border-b border-gray-50 last:border-0">
                <span class="font-bold text-primary">{{ app()->getLocale()==='ar'?$item->service_name_ar:$item->service_name }}</span>
                <span class="text-gray-500">
                    {{ $item->quantity }} {{ app()->getLocale()==='ar'?'قطعة':'pcs' }}
                    @if($item->free_quantity > 0)
                        <span class="text-green-600 font-bold">({{ $item->free_quantity }} {{ app()->getLocale()==='ar'?'مجاني':'free' }})</span>
                    @endif
                </span>
                <span class="font-bold text-secondary">{{ $item->subtotal }} {{ app()->getLocale()==='ar'?'ريال':'SAR' }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Confirmation / SMS note --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex gap-2 items-start">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <p class="text-xs text-blue-800 leading-relaxed">
            {{ app()->getLocale() === 'ar'
                ? 'سيتواصل معك أحد ممثلي خدمة العملاء لتأكيد الطلب قبل الاستلام، وسيصلك إشعار داخل التطبيق بكل تحديث على حالة طلبك.'
                : 'A customer service representative will contact you to confirm the order before pickup, and you will receive an in-app notification with every status update.' }}
        </p>
    </div>

    {{-- Admin damage note --}}
    @if($order->damage_note_photo_url)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
        <div class="flex gap-2 items-start">
            <svg class="w-5 h-5 text-yellow-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <p class="font-bold text-yellow-800 text-sm">{{ app()->getLocale() === 'ar' ? 'ملاحظة من المغسلة' : 'Laundry Note' }}</p>
                <p class="text-xs text-yellow-700 mt-1">{{ $order->damage_note_photo_url }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Cancel order (customer-initiated) --}}
    @if($order->isCancellable())
    <div class="card p-5">
        <button type="button" onclick="document.getElementById('cancelBox').classList.toggle('hidden')" class="w-full flex items-center justify-center gap-2 text-red-600 font-bold text-sm py-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ app()->getLocale() === 'ar' ? 'إلغاء الطلب' : 'Cancel Order' }}
        </button>
        <div id="cancelBox" class="hidden mt-3 space-y-2">
            <form method="POST" action="{{ route('orders.cancel', $order->id) }}" onsubmit="return confirm('{{ app()->getLocale()==='ar' ? 'هل أنت متأكد من إلغاء هذا الطلب؟' : 'Are you sure you want to cancel this order?' }}')">
                @csrf
                <textarea name="reason" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none bg-gray-50 mb-2" placeholder="{{ app()->getLocale()==='ar' ? 'سبب الإلغاء (اختياري)' : 'Cancellation reason (optional)' }}"></textarea>
                <button type="submit" class="w-full bg-red-50 text-red-700 border border-red-200 py-3 rounded-xl font-bold text-sm hover:bg-red-100 transition">
                    {{ app()->getLocale() === 'ar' ? 'تأكيد الإلغاء' : 'Confirm Cancellation' }}
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Continue payment (unpaid online order) --}}
    @if($order->payment_method === 'online' && $order->payment_status !== 'paid' && $order->status !== 'cancelled')
    <a href="{{ route('orders.pay', $order->id) }}" class="block w-full bg-amber-500 text-white text-center py-3.5 rounded-2xl font-black hover:opacity-90 transition">
        {{ app()->getLocale() === 'ar' ? 'إتمام الدفع الإلكتروني' : 'Complete Online Payment' }}
    </a>
    @endif

    {{-- Confirm delivery --}}
    @if($order->status === 'out_for_delivery')
    <form method="POST" action="{{ route('orders.confirm', $order->id) }}">
        @csrf
        <button type="submit" class="w-full hero-gradient text-white py-3.5 rounded-2xl font-black text-base hover:opacity-90 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ app()->getLocale() === 'ar' ? 'تأكيد استلام الطلب' : 'Confirm Receipt' }}
        </button>
    </form>
    @endif

    {{-- Rating --}}
    @if($order->status === 'delivered' && !$order->rating)
    <div class="card p-5">
        <h3 class="font-black text-primary mb-3">{{ app()->getLocale() === 'ar' ? 'تقييم الطلب' : 'Rate Order' }}</h3>
        <form method="POST" action="{{ route('orders.rate', $order->id) }}" class="space-y-3">
            @csrf
            <div class="flex gap-2 justify-center">
                @for($i=1;$i<=5;$i++)
                <label class="cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                    <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center font-black text-gray-400 hover:border-secondary hover:text-secondary transition text-sm rating-btn" onclick="selectRating({{ $i }})">{{ $i }}</div>
                </label>
                @endfor
            </div>
            <textarea name="comment" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none bg-gray-50"
                placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب تعليقك (اختياري)' : 'Optional comment' }}"></textarea>
            <button type="submit" class="w-full hero-gradient text-white py-3 rounded-xl font-bold">
                {{ app()->getLocale() === 'ar' ? 'إرسال التقييم' : 'Submit Rating' }}
            </button>
        </form>
    </div>
    @elseif($order->rating)
    <div class="card p-4 text-center">
        <p class="text-sm font-bold text-gray-600 mb-2">{{ app()->getLocale() === 'ar' ? 'تقييمك' : 'Your Rating' }}</p>
        <div class="flex justify-center gap-1">
            @for($s=1;$s<=5;$s++)
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-black {{ $s <= $order->rating ? 'hero-gradient text-white' : 'bg-gray-100 text-gray-400' }}">{{ $s }}</div>
            @endfor
        </div>
        @if($order->rating_comment)<p class="text-xs text-gray-500 mt-2">{{ $order->rating_comment }}</p>@endif
    </div>
    @endif

</div>
<script>
function selectRating(val) {
    document.querySelectorAll('.rating-btn').forEach((b,i) => {
        if(i < val) { b.classList.add('border-secondary','text-secondary'); b.classList.remove('border-gray-200','text-gray-400'); }
        else { b.classList.remove('border-secondary','text-secondary'); b.classList.add('border-gray-200','text-gray-400'); }
    });
}
</script>
@endsection
