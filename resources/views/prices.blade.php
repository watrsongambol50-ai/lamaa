@extends('layouts.app')
@section('content')
<div class="animate-up space-y-4">
    <div class="hero-gradient -mx-4 -mt-4 text-white py-8 px-6 mb-6 text-center">
        <div class="flex justify-center mb-3">
            <div class="bg-white rounded-xl flex items-center justify-center shadow" style="width:56px;height:56px;">
                <img src="/images/lamaa-logo.png" alt="لماء" style="width:44px;height:44px;object-fit:contain;">
            </div>
        </div>
        <h1 class="text-2xl font-black mb-1">{{ app()->getLocale()==='ar' ? 'قائمة الأسعار' : 'Price List' }}</h1>
        <p class="text-white/70 text-sm">{{ app()->getLocale()==='ar' ? 'أسعار الغسيل للقطعة الواحدة' : 'Wash price per piece' }}</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-3 flex items-start gap-2">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-blue-700"><span class="font-bold text-blue-800">{{ app()->getLocale()==='ar'?'ملاحظة: ':'Note: ' }}</span>{{ app()->getLocale()==='ar'?'السعر للقطعة الواحدة. الخدمة العاجلة تضاف إليها 50% من السعر الأساسي.':'Per piece. Express adds 50% to base price.' }}</p>
    </div>

    @php
    $categoryMeta = [
        'ثياب_تقليدية' => ['ar'=>'الثياب التقليدية','en'=>'Traditional Thobes','col'=>'blue',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C9 2 7 4 7 4L4 8v2h2l1 10h10l1-10h2V8l-3-4s-2-2-5-2z"/>'],
        'ملابس_عامة'   => ['ar'=>'الملابس العامة',  'en'=>'General Clothes',  'col'=>'teal',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H6v10a1 1 0 001 1h10a1 1 0 001-1V10h2.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z"/>'],
        'بدل_وزي'      => ['ar'=>'البدل والأزياء', 'en'=>'Suits & Uniforms', 'col'=>'purple','svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H5l-1 4h4v10h8V9h4l-1-4h-4M9 5V3h6v2M9 5l3 4 3-4"/>'],
        'ثياب_خارجية'  => ['ar'=>'الثياب الخارجية','en'=>'Outer Garments',   'col'=>'rose',  'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3l3 3-8 8-8-8 3-3 5 2 5-2zM8 14l4 4 4-4M12 22v-4"/>'],
        'مفروشات'      => ['ar'=>'المفروشات',      'en'=>'Bedding & Linens', 'col'=>'amber', 'svg'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M3 12V8a2 2 0 012-2h14a2 2 0 012 2v4M3 12v6h18v-6"/>'],
    ];
    $hdrMap = ['blue'=>'bg-blue-600','teal'=>'bg-teal-600','purple'=>'bg-purple-600','rose'=>'bg-rose-600','amber'=>'bg-amber-600'];
    $bgMap  = ['blue'=>'bg-blue-50', 'teal'=>'bg-teal-50', 'purple'=>'bg-purple-50', 'rose'=>'bg-rose-50', 'amber'=>'bg-amber-50'];
    $grouped = $services->groupBy('category');
    @endphp

    @foreach($categoryMeta as $catKey => $m)
    @if(isset($grouped[$catKey]))
    <div class="card overflow-hidden">
        <div class="{{ $hdrMap[$m['col']] }} p-4 flex items-center gap-3">
            <div class="bg-white/20 rounded-xl w-9 h-9 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $m['svg'] !!}</svg>
            </div>
            <div>
                <h2 class="font-black text-white text-sm">{{ app()->getLocale()==='ar' ? $m['ar'] : $m['en'] }}</h2>
                <p class="text-white/70 text-xs">{{ $grouped[$catKey]->count() }} {{ app()->getLocale()==='ar'?'صنف':'items' }}</p>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="{{ $bgMap[$m['col']] }} text-gray-600 text-xs font-bold">
                    <th class="px-4 py-2 text-right">{{ app()->getLocale()==='ar'?'النوع':'Type' }}</th>
                    <th class="px-3 py-2 text-center">{{ app()->getLocale()==='ar'?'عادي':'Regular' }}</th>
                    <th class="px-3 py-2 text-center">{{ app()->getLocale()==='ar'?'عاجل':'Express' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($grouped[$catKey] as $svc)
                @php
                    $exp = null;
                    if($svc->description && str_contains($svc->description,'express:')) {
                        preg_match('/express:(\d+)/',$svc->description,$mm);
                        if(isset($mm[1])&&$mm[1]>0) $exp=$mm[1];
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2.5 font-bold text-primary text-sm">{{ app()->getLocale()==='ar' ? $svc->name_ar : $svc->name_en }}</td>
                    <td class="px-3 py-2.5 text-center font-black text-secondary text-sm">{{ $svc->base_price }} {{ app()->getLocale()==='ar'?'ر':'SAR' }}</td>
                    <td class="px-3 py-2.5 text-center">
                        @if($exp)<span class="font-black text-orange-600 text-sm">{{ $exp }} {{ app()->getLocale()==='ar'?'ر':'SAR' }}</span>@else<span class="text-gray-300 text-lg">—</span>@endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endforeach

    @auth
    <a href="{{ route('orders.create') }}" class="block w-full hero-gradient text-white text-center py-4 rounded-2xl font-black text-base hover:opacity-90 transition">{{ app()->getLocale()==='ar'?'اطلب الآن':'Order Now' }}</a>
    @else
    <a href="{{ route('register') }}" class="block w-full hero-gradient text-white text-center py-4 rounded-2xl font-black text-base hover:opacity-90 transition">{{ app()->getLocale()==='ar'?'سجّل وابدأ الطلب':'Register & Order' }}</a>
    @endauth
</div>
@endsection
