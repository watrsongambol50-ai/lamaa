@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up">
    <h1 class="text-xl font-black text-primary mb-5 text-center">{{ app()->getLocale()==='ar'?'طلب جديد':'New Order' }}</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm mb-4">
        @foreach($errors->all() as $e)
        <div class="flex items-center gap-1"><svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>{{ $e }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" class="space-y-4" id="orderForm">
        @csrf

        {{-- Service mode --}}
        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">{{ app()->getLocale()==='ar'?'نوع الخدمة':'Service Type' }}</h3>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['wash_iron'=>['ar'=>'غسيل وكوي','en'=>'Wash & Iron'],'wash_only'=>['ar'=>'غسيل فقط','en'=>'Wash Only'],'iron_only'=>['ar'=>'كوي فقط','en'=>'Iron Only']] as $mk=>$ml)
                <label class="text-center border-2 border-gray-100 rounded-xl py-2.5 cursor-pointer transition has-[:checked]:border-primary has-[:checked]:bg-blue-50 text-xs font-bold text-gray-500 has-[:checked]:text-primary">
                    <input type="radio" name="service_mode" value="{{ $mk }}" class="hidden mode-radio" {{ old('service_mode', 'wash_iron')===$mk?'checked':'' }} onchange="recalc()">
                    {{ app()->getLocale()==='ar'?$ml['ar']:$ml['en'] }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Search & category filter --}}
        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">{{ app()->getLocale()==='ar'?'اختر القطع':'Select Items' }}</h3>
            <div class="relative mb-3">
                <input type="text" id="searchBox" oninput="filterServices()" placeholder="{{ app()->getLocale()==='ar'?'بحث عن قطعة...':'Search items...' }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 ps-9 text-sm bg-gray-50 focus:bg-white transition">
                <svg class="w-4 h-4 text-gray-400 absolute top-1/2 -translate-y-1/2 {{ app()->getLocale()==='ar'?'right-3':'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
            </div>
            @php
            $cats = [
                'ثياب_تقليدية' => ['ar'=>'ثياب تقليدية','en'=>'Traditional','c'=>'blue'],
                'ملابس_عامة'   => ['ar'=>'ملابس عامة',  'en'=>'General',    'c'=>'teal'],
                'بدل_وزي'      => ['ar'=>'بدل وأزياء', 'en'=>'Suits',      'c'=>'purple'],
                'ثياب_خارجية'  => ['ar'=>'ثياب خارجية','en'=>'Outer',      'c'=>'rose'],
                'مفروشات'      => ['ar'=>'مفروشات',    'en'=>'Bedding',    'c'=>'amber'],
            ];
            $bg  = ['blue'=>'bg-blue-100','teal'=>'bg-teal-100','purple'=>'bg-purple-100','rose'=>'bg-rose-100','amber'=>'bg-amber-100'];
            $txt = ['blue'=>'text-blue-700','teal'=>'text-teal-700','purple'=>'text-purple-700','rose'=>'text-rose-700','amber'=>'text-amber-700'];
            $grouped = $services->groupBy('category');
            @endphp
            <div class="flex gap-2 overflow-x-auto pb-2 mb-3">
                <button type="button" onclick="swCat('all', this)" class="ctab flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold border-2 bg-primary text-white border-transparent">
                    {{ app()->getLocale()==='ar'?'الكل':'All' }}
                </button>
                @foreach($cats as $k => $m)
                @if(isset($grouped[$k]))
                <button type="button" onclick="swCat('{{ $k }}', this)" class="ctab flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold border-2 bg-white text-gray-500 border-gray-200">
                    {{ app()->getLocale()==='ar'?$m['ar']:$m['en'] }}
                </button>
                @endif
                @endforeach
            </div>

            <div id="serviceList" class="space-y-2 max-h-72 overflow-y-auto">
                @foreach($services as $svc)
                <div class="service-row flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100"
                     data-cat="{{ $svc->category }}"
                     data-name="{{ strtolower($svc->name_ar.' '.$svc->name_en) }}"
                     data-id="{{ $svc->id }}"
                     data-base="{{ $svc->base_price }}"
                     data-wash="{{ $svc->price_wash_only ?? round($svc->base_price*0.8,2) }}"
                     data-iron="{{ $svc->price_iron_only ?? round($svc->base_price*0.6,2) }}"
                     data-name-ar="{{ $svc->name_ar }}" data-name-en="{{ $svc->name_en }}">
                    <div class="flex-1">
                        <div class="font-bold text-sm text-primary">{{ app()->getLocale()==='ar'?$svc->name_ar:$svc->name_en }}</div>
                        <div class="text-xs text-secondary font-bold price-label">{{ $svc->base_price }} {{ app()->getLocale()==='ar'?'ريال / قطعة':'SAR / piece' }}</div>
                    </div>
                    <button type="button" onclick="changeQty({{ $svc->id }}, -1)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 font-black flex items-center justify-center">−</button>
                    <span class="w-6 text-center font-black qty-display" id="qty-{{ $svc->id }}">0</span>
                    <button type="button" onclick="changeQty({{ $svc->id }}, 1)" class="w-8 h-8 rounded-full bg-secondary/20 text-secondary hover:bg-secondary/30 font-black flex items-center justify-center">+</button>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Cart summary --}}
        <div class="card p-4" id="cartSummary">
            <h3 class="font-bold text-primary mb-3 text-sm">{{ app()->getLocale()==='ar'?'سلة الطلب':'Cart Summary' }}</h3>
            <div id="cartLines" class="text-xs text-gray-500 mb-2">{{ app()->getLocale()==='ar'?'لم تختر أي قطعة بعد':'No items selected yet' }}</div>
            <div class="flex justify-between text-sm font-bold text-primary border-t border-gray-100 pt-2">
                <span>{{ app()->getLocale()==='ar'?'إجمالي القطع':'Total Pieces' }}</span>
                <span id="totalPieces">0</span>
            </div>
            <div class="flex justify-between text-sm font-black text-secondary">
                <span>{{ app()->getLocale()==='ar'?'المبلغ التقديري':'Estimated Total' }}</span>
                <span id="totalPrice">0.00 {{ app()->getLocale()==='ar'?'ريال':'SAR' }}</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ app()->getLocale()==='ar'?'* السعر النهائي يشمل خصم الولاء والكوبون إن وجد':'* Final price includes loyalty & coupon discounts if applicable' }}</p>
        </div>

        {{-- Express toggle --}}
        <div class="card p-4">
            <label class="flex items-center justify-between cursor-pointer" onclick="toggleExp()">
                <div>
                    <div class="font-bold text-primary text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ app()->getLocale()==='ar'?'خدمة عاجلة (+1.50 ريال / قطعة)':'Express (+1.50 SAR / piece)' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ app()->getLocale()==='ar'?'استلام خلال 24 ساعة':'Receive within 24 hours' }}</div>
                </div>
                <div>
                    <input type="checkbox" name="express" id="express" class="hidden" {{ old('express')?'checked':'' }} onchange="recalc()">
                    <div id="sw-t" class="sw-track {{ old('express')?'on':'' }}"><div class="sw-thumb"></div></div>
                </div>
            </label>
        </div>

        {{-- Coupon --}}
        <div class="card p-4">
            <h3 class="font-bold text-primary mb-2 text-sm">{{ app()->getLocale()==='ar'?'كود الخصم (اختياري)':'Coupon Code (optional)' }}</h3>
            <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="{{ app()->getLocale()==='ar'?'أدخل الكود إن وجد':'Enter code if any' }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-gray-50 focus:bg-white transition" dir="ltr">
        </div>

        {{-- Address --}}
        <div class="card p-4 space-y-3">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-bold text-primary text-sm">{{ app()->getLocale()==='ar'?'عنوان الاستلام':'Pickup Address' }}</h3>
                <button type="button" onclick="useMyLocation(this)" id="geoBtn" class="flex items-center gap-1.5 text-xs font-bold text-secondary bg-secondary/10 px-3 py-1.5 rounded-full hover:bg-secondary/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ app()->getLocale()==='ar'?'استخدام موقعي الحالي':'Use my location' }}
                </button>
            </div>
            <p id="geoStatus" class="text-xs text-gray-400 -mt-2"></p>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'المدينة':'City' }}</label>
                    <input type="text" name="city" value="{{ old('city') }}" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'الحي (اختياري)':'District (optional)' }}</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'اسم المبنى (اختياري)':'Building Name (optional)' }}</label>
                    <input type="text" name="building_name" value="{{ old('building_name') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'رقم المبنى (اختياري)':'Building No. (optional)' }}</label>
                    <input type="text" name="building_number" value="{{ old('building_number') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'ملاحظات العنوان (اختياري)':'Address Notes (optional)' }}</label>
                <input type="text" name="address_notes" value="{{ old('address_notes') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition" placeholder="{{ app()->getLocale()==='ar'?'مثال: بجانب الصيدلية':'e.g. next to the pharmacy' }}">
            </div>
            <input type="hidden" name="address" id="composedAddress">
            <input type="hidden" name="lat" id="latInput">
            <input type="hidden" name="lng" id="lngInput">
        </div>

        {{-- Pickup date/time & notes --}}
        <div class="card p-4 space-y-3">
            <h3 class="font-bold text-primary mb-1 text-sm">{{ app()->getLocale()==='ar'?'موعد الاستلام':'Pickup Schedule' }}</h3>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'تاريخ الاستلام':'Pickup Date' }}</label>
                <input type="date" name="pickup_date" value="{{ old('pickup_date', date('Y-m-d', strtotime('+1 day'))) }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'وقت الاستلام':'Pickup Time' }}</label>
                <select name="pickup_time_slot" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition">
                    @foreach(['08:00-10:00'=>'08:00 - 10:00 ص','10:00-12:00'=>'10:00 - 12:00 م','12:00-14:00'=>'12:00 - 2:00 م','14:00-16:00'=>'2:00 - 4:00 م','16:00-18:00'=>'4:00 - 6:00 م','18:00-20:00'=>'6:00 - 8:00 م'] as $v=>$l)
                    <option value="{{ $v }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ app()->getLocale()==='ar'?'ملاحظات (اختياري)':'Notes (optional)' }}</label>
                <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition resize-none" placeholder="{{ app()->getLocale()==='ar'?'ملاحظاتك هنا...':'Optional notes...' }}">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Payment method --}}
        <div class="card p-4">
            <h3 class="font-bold text-primary mb-3 text-sm">{{ app()->getLocale()==='ar'?'طريقة الدفع':'Payment Method' }}</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 cursor-pointer transition has-[:checked]:border-primary has-[:checked]:bg-blue-50">
                    <input type="radio" name="payment_method" value="cash" checked class="accent-primary">
                    <div class="flex-1">
                        <div class="font-bold text-sm text-primary">{{ app()->getLocale()==='ar'?'الدفع عند الاستلام':'Pay on Delivery' }}</div>
                        <div class="text-xs text-gray-500">{{ app()->getLocale()==='ar'?'كاش أو شبكة عند تسليم الطلب':'Cash or card upon delivery' }}</div>
                    </div>
                </label>
                @php $onlinePaymentAvailable = filled(config('services.moyasar.publishable_key')); @endphp
                @if($onlinePaymentAvailable)
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 cursor-pointer transition has-[:checked]:border-primary has-[:checked]:bg-blue-50">
                    <input type="radio" name="payment_method" value="online" class="accent-primary">
                    <div class="flex-1">
                        <div class="font-bold text-sm text-primary flex items-center gap-2">
                            {{ app()->getLocale()==='ar'?'الدفع الإلكتروني':'Online Payment' }}
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-bold">mada · Visa · Apple Pay</span>
                        </div>
                        <div class="text-xs text-gray-500">{{ app()->getLocale()==='ar'?'دفع آمن عبر ميسر':'Secure payment via Moyasar' }}</div>
                    </div>
                </label>
                @else
                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 opacity-60 cursor-not-allowed">
                    <input type="radio" name="payment_method_disabled" disabled>
                    <div class="flex-1">
                        <div class="font-bold text-sm text-gray-500 flex items-center gap-2">
                            {{ app()->getLocale()==='ar'?'الدفع الإلكتروني':'Online Payment' }}
                            <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ app()->getLocale()==='ar'?'قريباً':'Coming Soon' }}</span>
                        </div>
                        <div class="text-xs text-gray-400">{{ app()->getLocale()==='ar'?'سيتم تفعيله قريباً إن شاء الله':'Will be enabled soon' }}</div>
                    </div>
                </label>
                @endif
            </div>
        </div>

        <button type="submit" id="submitBtn" class="w-full hero-gradient text-white py-4 rounded-2xl font-black text-base hover:opacity-90 transition">
            {{ app()->getLocale()==='ar'?'تأكيد الطلب':'Confirm Order' }}
        </button>
    </form>
</div>
<script>
var cart = {};
var isAr = "{{ app()->getLocale() }}" === 'ar';

function changeQty(id, delta) {
    var cur = cart[id] || 0;
    var next = cur + delta;
    if (next < 0) next = 0;
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
    var totalPieces = 0, totalPrice = 0;
    var lines = [];

    rows.forEach(function (row) {
        var id = row.dataset.id;
        var qty = cart[id] || 0;
        var price = priceFor(row, mode) + (express ? 1.5 : 0);
        row.querySelector('.price-label').textContent = price.toFixed(2) + (isAr ? ' ريال / قطعة' : ' SAR / piece');
        if (qty > 0) {
            totalPieces += qty;
            totalPrice += qty * price;
            var name = isAr ? row.dataset.nameAr : row.dataset.nameEn;
            lines.push(name + ' × ' + qty);
        }
    });

    document.getElementById('totalPieces').textContent = totalPieces;
    document.getElementById('totalPrice').textContent = totalPrice.toFixed(2) + (isAr ? ' ريال' : ' SAR');
    document.getElementById('cartLines').innerHTML = lines.length ? lines.join('<br>') : (isAr ? 'لم تختر أي قطعة بعد' : 'No items selected yet');
}

function toggleExp() {
    var cb = document.getElementById('express'), tr = document.getElementById('sw-t');
    cb.checked = !cb.checked;
    tr.classList.toggle('on', cb.checked);
    recalc();
}

function swCat(cat, btn) {
    document.querySelectorAll('.ctab').forEach(function (t) {
        t.classList.remove('bg-primary', 'text-white', 'border-transparent');
        t.classList.add('bg-white', 'text-gray-500', 'border-gray-200');
    });
    btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-200');
    btn.classList.add('bg-primary', 'text-white', 'border-transparent');
    filterServices(cat);
}

function filterServices(catOverride) {
    var search = document.getElementById('searchBox').value.toLowerCase();
    var activeCat = catOverride || (document.querySelector('.ctab.bg-primary') ? null : 'all');
    document.querySelectorAll('.service-row').forEach(function (row) {
        var matchesSearch = row.dataset.name.includes(search);
        var matchesCat = !catOverride || catOverride === 'all' || row.dataset.cat === catOverride;
        row.style.display = (matchesSearch && matchesCat) ? '' : 'none';
    });
}

document.getElementById('orderForm').addEventListener('submit', function (e) {
    var itemsContainer = document.getElementById('orderForm');
    var hasItems = Object.values(cart).some(function (q) { return q > 0; });
    if (!hasItems) {
        e.preventDefault();
        alert(isAr ? 'الرجاء اختيار قطعة واحدة على الأقل' : 'Please select at least one item');
        return;
    }
    document.querySelectorAll('input[name^="items"]').forEach(function (el) { el.remove(); });
    var idx = 0;
    Object.keys(cart).forEach(function (id) {
        if (cart[id] > 0) {
            var i1 = document.createElement('input');
            i1.type = 'hidden'; i1.name = 'items[' + idx + '][service_id]'; i1.value = id;
            var i2 = document.createElement('input');
            i2.type = 'hidden'; i2.name = 'items[' + idx + '][quantity]'; i2.value = cart[id];
            itemsContainer.appendChild(i1);
            itemsContainer.appendChild(i2);
            idx++;
        }
    });

    var city = document.querySelector('input[name="city"]').value;
    var district = document.querySelector('input[name="district"]').value;
    var bname = document.querySelector('input[name="building_name"]').value;
    var bnum = document.querySelector('input[name="building_number"]').value;
    document.getElementById('composedAddress').value = [city, district, bname, bnum].filter(Boolean).join(' - ');
});

recalc();

function useMyLocation(btn) {
    var statusEl = document.getElementById('geoStatus');
    if (!navigator.geolocation) {
        statusEl.textContent = isAr ? 'المتصفح لا يدعم تحديد الموقع' : 'Geolocation not supported by this browser';
        return;
    }
    btn.disabled = true;
    statusEl.textContent = isAr ? 'جاري تحديد موقعك...' : 'Locating you...';

    navigator.geolocation.getCurrentPosition(function (pos) {
        var lat = pos.coords.latitude, lng = pos.coords.longitude;
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;

        // Reverse-geocode via OpenStreetMap Nominatim (free, no API key needed) to auto-fill city/district.
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=ar')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var addr = data.address || {};
                var city = addr.city || addr.town || addr.village || addr.county || '';
                var district = addr.suburb || addr.neighbourhood || addr.quarter || '';
                if (city) document.querySelector('input[name="city"]').value = city;
                if (district) document.querySelector('input[name="district"]').value = district;
                statusEl.textContent = isAr ? '✓ تم تحديد موقعك وتعبئة العنوان' : '✓ Location detected and address filled';
                btn.disabled = false;
            })
            .catch(function () {
                statusEl.textContent = isAr ? '✓ تم تحديد الموقع (تعذر تعبئة العنوان تلقائياً)' : '✓ Location detected (could not auto-fill address)';
                btn.disabled = false;
            });
    }, function (err) {
        statusEl.textContent = isAr ? 'تعذر تحديد موقعك — تأكد من السماح بالوصول للموقع' : 'Could not get your location — please allow location access';
        btn.disabled = false;
    }, { enableHighAccuracy: true, timeout: 10000 });
}
</script>
@endsection
