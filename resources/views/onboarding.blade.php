@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up">

    {{-- Skip --}}
    <div class="flex justify-end mb-2">
        <button onclick="skipOnboarding()" class="text-xs font-bold text-gray-400 hover:text-primary transition px-3 py-1.5">
            {{ app()->getLocale()==='ar' ? 'تخطي' : 'Skip' }}
        </button>
    </div>

    <div id="slides" class="relative overflow-hidden rounded-3xl shadow-sm" style="min-height:460px;">

        <div class="slide" data-i="0">
            <div class="rounded-3xl mb-5 flex items-center justify-center relative overflow-hidden" style="height:230px; background: linear-gradient(145deg,#0d2b6b 0%,#023e8a 55%,#00b4d8 100%);">
                <span class="absolute top-3 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">1/3</span>
                <div class="bg-white rounded-2xl shadow-lg flex items-center justify-center" style="width:96px;height:96px;">
                    <img src="/images/lamaa-logo.png" alt="لماء" style="width:76px;height:76px;object-fit:contain;">
                </div>
            </div>
            <div class="px-4 text-center">
                <h2 class="text-xl font-black text-primary mb-2">أهلاً بك في لماء</h2>
                <p class="text-gray-500 text-sm leading-relaxed px-2">مغسلتك المفضلة بضغطة واحدة — استلام وتوصيل حتى باب بيتك، بجودة عالية وأسعار مناسبة.</p>
            </div>
        </div>

        <div class="slide hidden" data-i="1">
            <div class="rounded-3xl mb-5 flex items-center justify-center relative overflow-hidden" style="height:230px; background: linear-gradient(145deg,#00b4d8 0%,#0096c7 55%,#0d2b6b 100%);">
                <span class="absolute top-3 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">2/3</span>
                <div class="bg-white/95 rounded-2xl shadow-lg flex items-center justify-center text-primary" style="width:96px;height:96px;">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
            </div>
            <div class="px-4 text-center">
                <h2 class="text-xl font-black text-primary mb-2">برنامج ولاء يستحق الانتظار</h2>
                <p class="text-gray-500 text-sm leading-relaxed px-2">كل خامس قطعة تغسلها معنا... مجانية! وكل طلب ثالث، توصيله علينا.</p>
            </div>
        </div>

        <div class="slide hidden" data-i="2">
            <div class="rounded-3xl mb-5 flex items-center justify-center relative overflow-hidden" style="height:230px; background: linear-gradient(145deg,#023e8a 0%,#0d2b6b 55%,#091f52 100%);">
                <span class="absolute top-3 {{ app()->getLocale()==='ar'?'right-3':'left-3' }} bg-white/20 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full">3/3</span>
                <div class="bg-white/95 rounded-2xl shadow-lg flex items-center justify-center text-secondary" style="width:96px;height:96px;">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="px-4 text-center">
                <h2 class="text-xl font-black text-primary mb-2">اطلب بسهولة وتابع طلبك لحظة بلحظة</h2>
                <p class="text-gray-500 text-sm leading-relaxed px-2">اختر القطع، حدد وقت الاستلام، وتابع حالة طلبك من التطبيق حتى وصوله لباب بيتك.</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center gap-2 my-5">
        <span class="dot h-2 rounded-full bg-primary transition-all" style="width:20px;" data-d="0"></span>
        <span class="dot w-2 h-2 rounded-full bg-gray-200 transition-all" data-d="1"></span>
        <span class="dot w-2 h-2 rounded-full bg-gray-200 transition-all" data-d="2"></span>
    </div>

    <button id="nextBtn" onclick="nextSlide()" class="w-full hero-gradient text-white py-3.5 rounded-2xl font-black text-base shadow-lg">التالي</button>
</div>
<script>
var cur = 0;
var total = 3;
function showSlide(i) {
    document.querySelectorAll('.slide').forEach(function (s) { s.classList.add('hidden'); });
    document.querySelector('.slide[data-i="' + i + '"]').classList.remove('hidden');
    document.querySelectorAll('.dot').forEach(function (d) {
        var active = d.dataset.d === String(i);
        d.classList.toggle('bg-primary', active);
        d.classList.toggle('bg-gray-200', !active);
        d.style.width = active ? '20px' : '8px';
    });
    document.getElementById('nextBtn').textContent = (i === total - 1) ? 'ابدأ الآن' : 'التالي';
}
function nextSlide() {
    if (cur < total - 1) { cur++; showSlide(cur); }
    else { skipOnboarding(); }
}
function skipOnboarding() {
    window.location.href = "{{ route('login') }}";
}
</script>
@endsection
