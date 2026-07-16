@extends('layouts.app')
@section('content')

{{-- Hero --}}
<section class="hero-gradient -mx-4 -mt-4 text-white text-center py-12 px-6 mb-8 relative overflow-hidden animate-up">
    <div class="mb-5 flex justify-center">
        <div class="logo-wrap rounded-2xl shadow-xl flex items-center justify-center" style="width:88px;height:88px;background:white!important;">
            <img src="/images/lamaa-logo.png" alt="لماء LAMAA" style="width:72px;height:72px;object-fit:contain;">
        </div>
    </div>
    <h1 class="text-3xl font-black mb-2 leading-tight">{{ app()->getLocale() === 'ar' ? 'خدمة غسيل ملابسك من باب بيتك' : 'Laundry at Your Doorstep' }}</h1>
    <p class="text-secondary-light font-bold text-sm mb-5">{{ app()->getLocale() === 'ar' ? 'نظافة تدوم… وثقة ما تتغيّر' : 'Lasting cleanliness, lasting trust' }}</p>
    <div class="flex justify-center gap-3 flex-wrap">
        @auth
            <a href="{{ route('orders.create') }}" class="bg-secondary text-white px-7 py-3 rounded-2xl font-bold text-sm hover:bg-secondary-light transition inline-flex items-center gap-2 shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                {{ app()->getLocale() === 'ar' ? 'اطلب الآن' : 'Order Now' }}
            </a>
            <a href="{{ route('orders.index') }}" class="border-2 border-white/50 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-white/10 transition">{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}</a>
        @else
            <a href="{{ route('register') }}" class="bg-secondary text-white px-7 py-3 rounded-2xl font-bold text-sm hover:bg-secondary-light transition shadow-lg">{{ app()->getLocale() === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
            <a href="{{ route('login') }}" class="border-2 border-white/50 text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-white/10 transition">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</a>
        @endauth
    </div>
</section>

{{-- Services by category --}}
<section class="mb-8 animate-up animate-up-1">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-black text-primary">{{ app()->getLocale() === 'ar' ? 'الخدمات والأسعار' : 'Services & Prices' }}</h2>
        <a href="{{ route('prices') }}" class="text-secondary text-sm font-bold">{{ app()->getLocale() === 'ar' ? 'عرض الكل ›' : 'View All ›' }}</a>
    </div>
    @php
    $categoryMeta = [
        'ثياب_تقليدية' => ['ar'=>'ثياب تقليدية','en'=>'Traditional','color'=>'blue',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C9 2 7 4 7 4L4 8v2h2l1 10h10l1-10h2V8l-3-4s-2-2-5-2z"/>'],
        'ملابس_عامة'   => ['ar'=>'ملابس عامة',  'en'=>'General',    'color'=>'teal',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H6v10a1 1 0 001 1h10a1 1 0 001-1V10h2.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z"/>'],
        'بدل_وزي'      => ['ar'=>'بدل وأزياء', 'en'=>'Suits',      'color'=>'purple','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H5l-1 4h4v10h8V9h4l-1-4h-4M9 5V3h6v2M9 5l3 4 3-4"/>'],
        'ثياب_خارجية'  => ['ar'=>'ثياب خارجية','en'=>'Outer',      'color'=>'rose',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3l3 3-8 8-8-8 3-3 5 2 5-2zM8 14l4 4 4-4M12 22v-4"/>'],
        'مفروشات'      => ['ar'=>'مفروشات',    'en'=>'Bedding',    'color'=>'amber', 'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12V8a2 2 0 012-2h14a2 2 0 012 2v4M3 12v6h18v-6"/>'],
    ];
    $bgMap   = ['blue'=>'bg-blue-100','teal'=>'bg-teal-100','purple'=>'bg-purple-100','rose'=>'bg-rose-100','amber'=>'bg-amber-100'];
    $txtMap  = ['blue'=>'text-blue-700','teal'=>'text-teal-700','purple'=>'text-purple-700','rose'=>'text-rose-700','amber'=>'text-amber-700'];
    $borMap  = ['blue'=>'border-blue-400','teal'=>'border-teal-400','purple'=>'border-purple-400','rose'=>'border-rose-400','amber'=>'border-amber-400'];
    $grouped = $services->groupBy('category');
    @endphp

    <p class="text-gray-500 text-xs mb-4">{{ app()->getLocale()==='ar' ? 'مرّر لتصفح الأقسام، واضغط لعرض التفاصيل والأسعار' : 'Swipe through categories, tap for details and prices' }}</p>

    {{-- Horizontal category cards (matches "How it Works" grid feel) --}}
    <div class="flex gap-3 overflow-x-auto pb-2 -mx-4 px-4 snap-x snap-mandatory">
        @foreach($categoryMeta as $k => $m)
        @if(isset($grouped[$k]))
        <button onclick="openCatModal('{{ $k }}')" class="card p-4 text-center flex-shrink-0 snap-start hover:shadow-md transition" style="width:130px;">
            <div class="w-12 h-12 {{ $bgMap[$m['color']] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6 {{ $txtMap[$m['color']] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $m['svg'] !!}</svg>
            </div>
            <p class="text-xs font-bold text-primary leading-tight">{{ app()->getLocale()==='ar' ? $m['ar'] : $m['en'] }}</p>
            <p class="text-[11px] text-gray-400 mt-1">{{ $grouped[$k]->count() }} {{ app()->getLocale()==='ar'?'قطعة':'items' }}</p>
        </button>
        @endif
        @endforeach
    </div>

    {{-- Category detail modal --}}
    <div id="catModalBackdrop" class="hidden fixed inset-0 bg-black/40 z-40" onclick="closeCatModal()"></div>
    <div id="catModal" class="hidden fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl z-50 p-5 max-h-[70vh] overflow-y-auto">
        <div class="w-10 h-1.5 bg-gray-200 rounded-full mx-auto mb-4"></div>
        <div class="flex items-center justify-between mb-4">
            <h3 id="catModalTitle" class="font-black text-primary text-lg"></h3>
            <button onclick="closeCatModal()" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @foreach($categoryMeta as $k => $m)
        @if(isset($grouped[$k]))
        <div id="catPanel-{{ $k }}" class="hidden grid grid-cols-2 gap-3">
            @foreach($grouped[$k] as $svc)
            <div class="bg-gray-50 rounded-xl p-3 text-center">
                <h3 class="text-xs font-bold text-primary mb-1 leading-tight">{{ app()->getLocale()==='ar' ? $svc->name_ar : $svc->name_en }}</h3>
                <p class="text-secondary font-black text-sm">{{ $svc->base_price }} {{ app()->getLocale()==='ar' ? 'ريال' : 'SAR' }}</p>
            </div>
            @endforeach
        </div>
        @endif
        @endforeach
        <a href="{{ route('prices') }}" class="block text-center text-secondary text-sm font-bold mt-4">{{ app()->getLocale()==='ar' ? 'عرض كل الأسعار ›' : 'View all prices ›' }}</a>
    </div>
</section>

{{-- Vision 2030 ribbon --}}
<section class="mb-8 animate-up">
    <div class="rounded-2xl p-4 flex items-center justify-center gap-2 text-center border-2 border-secondary/30 bg-secondary/5">
        <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        <p class="text-primary font-bold text-sm">
            {{ app()->getLocale()==='ar' ? 'نساهم بخدماتنا في دعم مستهدفات رؤية المملكة 2030 لتطوير قطاع الخدمات المنزلية' : 'Proudly supporting Saudi Vision 2030 in developing the home-services sector' }}
        </p>
    </div>
</section>

{{-- How it works --}}
<section class="mb-8 animate-up animate-up-2">
    <h2 class="text-xl font-black text-primary text-center mb-5">{{ app()->getLocale() === 'ar' ? 'كيف يعمل؟' : 'How it Works?' }}</h2>
    <div class="grid grid-cols-2 gap-3">
        @foreach([
            ['num'=>1,'ar'=>'اطلب الخدمة',     'en'=>'Place Order',   'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
            ['num'=>2,'ar'=>'نستلم ملابسك',    'en'=>'We Pick Up',    'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>'],
            ['num'=>3,'ar'=>'غسيل واكواء',      'en'=>'Wash & Iron',   'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0C7 4 5 6 5 9s2 3 2 6m0-11h10m0 0c0 0 2 2 2 5s-2 3-2 6M17 4v12M5 20h14"/>'],
            ['num'=>4,'ar'=>'نوصّل لبيتك',      'en'=>'Deliver Home',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ] as $step)
        <div class="card p-4 text-center">
            <div class="w-9 h-9 rounded-full hero-gradient text-white flex items-center justify-center font-black text-sm mx-auto mb-2">{{ $step['num'] }}</div>
            <div class="flex justify-center mb-2"><svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['svg'] !!}</svg></div>
            <p class="text-xs font-bold text-primary">{{ app()->getLocale()==='ar' ? $step['ar'] : $step['en'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- Loyalty --}}
<section class="mb-8 animate-up animate-up-3">
    <div class="loyalty-section rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-primary w-10 h-10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <h2 class="text-lg font-black text-primary">{{ app()->getLocale()==='ar' ? 'برنامج الولاء' : 'Loyalty Program' }}</h2>
        </div>
        <div class="bg-white rounded-xl p-3 flex items-center justify-between shadow-sm mb-2">
            <div>
                <p class="font-black text-primary text-sm">{{ app()->getLocale()==='ar' ? 'كل 5 طلبات = طلب مجاني' : 'Every 5 orders = Free wash' }}</p>
                <p class="text-xs text-gray-500">{{ app()->getLocale()==='ar' ? 'طلبك الخامس مجاناً (قطعة واحدة)' : '5th order is free (1 piece)' }}</p>
            </div>
            <span class="text-2xl font-black text-secondary">5</span>
        </div>
        @guest
        <a href="{{ route('register') }}" class="block w-full hero-gradient text-white text-center py-3 rounded-xl font-bold text-sm mt-3">
            {{ app()->getLocale()==='ar' ? 'إنشاء حساب' : 'Create Account' }}
        </a>
        @endguest
    </div>
</section>

<script>
var catLabels = {
    @foreach($categoryMeta as $k => $m)
    "{{ $k }}": "{{ app()->getLocale()==='ar' ? $m['ar'] : $m['en'] }}",
    @endforeach
};
function openCatModal(cat){
    document.querySelectorAll('[id^="catPanel-"]').forEach(function(p){ p.classList.add('hidden'); });
    var panel = document.getElementById('catPanel-'+cat);
    if (panel) panel.classList.remove('hidden');
    document.getElementById('catModalTitle').textContent = catLabels[cat] || '';
    document.getElementById('catModal').classList.remove('hidden');
    document.getElementById('catModalBackdrop').classList.remove('hidden');
}
function closeCatModal(){
    document.getElementById('catModal').classList.add('hidden');
    document.getElementById('catModalBackdrop').classList.add('hidden');
}
</script>
@endsection
