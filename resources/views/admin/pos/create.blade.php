@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up">
    <h1 class="text-xl font-black text-primary mb-5 text-center">طلب حضوري (كاشير)</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm mb-4">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('admin.pos.store') }}" class="space-y-4" id="posForm">
        @csrf

        <div class="card p-4 space-y-3">
            <h3 class="font-bold text-primary mb-1 text-sm">بيانات العميل</h3>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">اسم العميل</label>
                <input type="text" name="customer_name" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">رقم الجوال</label>
                <input type="tel" name="customer_phone" required dir="ltr" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50" placeholder="05xxxxxxxx">
            </div>
        </div>

        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">نوع الخدمة</h3>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['wash_iron'=>'غسيل وكوي','wash_only'=>'غسيل فقط','iron_only'=>'كوي فقط'] as $mk=>$ml)
                <label class="text-center border-2 border-gray-100 rounded-xl py-2.5 cursor-pointer transition has-[:checked]:border-primary has-[:checked]:bg-blue-50 text-xs font-bold text-gray-500 has-[:checked]:text-primary">
                    <input type="radio" name="service_mode" value="{{ $mk }}" class="hidden mode-radio" {{ $mk==='wash_iron'?'checked':'' }} onchange="recalc()">
                    {{ $ml }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">اختر القطع</h3>
            <div id="serviceList" class="space-y-2 max-h-72 overflow-y-auto">
                @foreach($services as $svc)
                <div class="service-row flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100"
                     data-id="{{ $svc->id }}"
                     data-base="{{ $svc->base_price }}"
                     data-wash="{{ $svc->price_wash_only ?? round($svc->base_price*0.8,2) }}"
                     data-iron="{{ $svc->price_iron_only ?? round($svc->base_price*0.6,2) }}"
                     data-name-ar="{{ $svc->name_ar }}">
                    <div class="flex-1">
                        <div class="font-bold text-sm text-primary">{{ $svc->name_ar }}</div>
                        <div class="text-xs text-secondary font-bold price-label">{{ $svc->base_price }} ريال / قطعة</div>
                    </div>
                    <button type="button" onclick="changeQty({{ $svc->id }}, -1)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 font-black flex items-center justify-center">−</button>
                    <span class="w-6 text-center font-black qty-display" id="qty-{{ $svc->id }}">0</span>
                    <button type="button" onclick="changeQty({{ $svc->id }}, 1)" class="w-8 h-8 rounded-full bg-secondary/20 text-secondary hover:bg-secondary/30 font-black flex items-center justify-center">+</button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">ملخص الطلب</h3>
            <div id="cartLines" class="text-xs text-gray-500 mb-2">لم تختر أي قطعة بعد</div>
            <div class="flex justify-between text-sm font-bold text-primary border-t border-gray-100 pt-2">
                <span>إجمالي القطع</span><span id="totalPieces">0</span>
            </div>
            <div class="flex justify-between text-sm font-black text-secondary">
                <span>الإجمالي التقديري</span><span id="totalPrice">0.00 ريال</span>
            </div>
        </div>

        <div class="card p-4">
            <label class="flex items-center justify-between cursor-pointer" onclick="toggleExp()">
                <div class="font-bold text-primary text-sm">خدمة عاجلة (+1.50 ريال / قطعة)</div>
                <div>
                    <input type="checkbox" name="express" id="express" class="hidden" onchange="recalc()">
                    <div id="sw-t" class="sw-track"><div class="sw-thumb"></div></div>
                </div>
            </label>
        </div>

        <div class="card p-4">
            <h3 class="font-bold text-primary mb-2 text-sm">كود الخصم (اختياري)</h3>
            <input type="text" name="coupon_code" dir="ltr" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50">
        </div>

        <button type="submit" class="w-full hero-gradient text-white py-4 rounded-2xl font-black text-base">إنشاء الطلب</button>
    </form>
</div>
<script>
var cart = {};
function changeQty(id, delta) {
    var cur = cart[id] || 0;
    var next = Math.max(0, cur + delta);
    cart[id] = next;
    document.getElementById('qty-' + id).textContent = next;
    recalc();
}
function getMode() {
    var checked = document.querySelector('.mode-radio:checked');
    return checked ? checked.value : 'wash_iron';
}
function priceFor(row, mode) {
    if (mode === 'wash_only') return parseFloat(row.dataset.wash);
    if (mode === 'iron_only') return parseFloat(row.dataset.iron);
    return parseFloat(row.dataset.base);
}
function recalc() {
    var mode = getMode();
    var express = document.getElementById('express').checked;
    var rows = document.querySelectorAll('.service-row');
    var totalPieces = 0, totalPrice = 0, lines = [];
    rows.forEach(function (row) {
        var id = row.dataset.id;
        var qty = cart[id] || 0;
        var price = priceFor(row, mode) + (express ? 1.5 : 0);
        row.querySelector('.price-label').textContent = price.toFixed(2) + ' ريال / قطعة';
        if (qty > 0) {
            totalPieces += qty;
            totalPrice += qty * price;
            lines.push(row.dataset.nameAr + ' × ' + qty);
        }
    });
    document.getElementById('totalPieces').textContent = totalPieces;
    document.getElementById('totalPrice').textContent = totalPrice.toFixed(2) + ' ريال';
    document.getElementById('cartLines').innerHTML = lines.length ? lines.join('<br>') : 'لم تختر أي قطعة بعد';
}
function toggleExp() {
    var cb = document.getElementById('express'), tr = document.getElementById('sw-t');
    cb.checked = !cb.checked;
    tr.classList.toggle('on', cb.checked);
    recalc();
}
document.getElementById('posForm').addEventListener('submit', function (e) {
    var hasItems = Object.values(cart).some(function (q) { return q > 0; });
    if (!hasItems) {
        e.preventDefault();
        alert('الرجاء اختيار قطعة واحدة على الأقل');
        return;
    }
    var form = document.getElementById('posForm');
    document.querySelectorAll('input[name^="items"]').forEach(function (el) { el.remove(); });
    var idx = 0;
    Object.keys(cart).forEach(function (id) {
        if (cart[id] > 0) {
            var i1 = document.createElement('input');
            i1.type = 'hidden'; i1.name = 'items[' + idx + '][service_id]'; i1.value = id;
            var i2 = document.createElement('input');
            i2.type = 'hidden'; i2.name = 'items[' + idx + '][quantity]'; i2.value = cart[id];
            form.appendChild(i1); form.appendChild(i2);
            idx++;
        }
    });
});
recalc();
</script>
@endsection
