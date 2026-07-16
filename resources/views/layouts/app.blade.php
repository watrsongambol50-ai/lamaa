<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ isset($title) ? $title . ' — ' : '' }}لماء LAMAA</title>
    <meta name="theme-color" content="#0d2b6b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary:   '#0d2b6b',
                        'primary-dark': '#091f52',
                        secondary: '#00b4d8',
                        'secondary-light': '#48cae4',
                        accent:    '#023e8a',
                    },
                    fontFamily: { sans: ['Tajawal', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Tajawal', sans-serif; background: #f0f4ff; }

        /* ======= SPLASH SCREEN ======= */
        #splash-screen {
            position: fixed; inset: 0; z-index: 9999;
            background: linear-gradient(145deg, #0d2b6b 0%, #023e8a 55%, #0096c7 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            transition: opacity 0.65s ease;
        }
        #splash-screen.fade-out { opacity: 0; pointer-events: none; }
        #splash-screen.hidden   { display: none !important; }

        .splash-logo-wrap {
            width: 150px; height: 150px;
            background: white; border-radius: 36px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 24px 72px rgba(0,0,0,0.35);
            animation: splashPop 0.7s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .splash-logo-wrap img { width: 122px; height: 122px; object-fit: contain; }

        @keyframes splashPop {
            from { transform: scale(0.4) translateY(20px); opacity: 0; }
            to   { transform: scale(1) translateY(0);      opacity: 1; }
        }
        .splash-brand {
            margin-top: 24px; text-align: center;
            animation: splashUp 0.55s ease 0.45s both;
        }
        .splash-brand p:first-child { color: rgba(255,255,255,0.95); font-size: 22px; font-weight: 900; letter-spacing: 0.05em; }
        .splash-brand p:last-child  { color: rgba(255,255,255,0.55); font-size: 13px; margin-top: 4px; }
        @keyframes splashUp {
            from { opacity:0; transform: translateY(14px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .splash-dots {
            display: flex; gap: 8px; margin-top: 40px;
            animation: splashUp 0.5s ease 0.7s both;
        }
        .splash-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,0.4);
            animation: dotPulse 1.2s ease-in-out infinite;
        }
        .splash-dot:nth-child(2) { animation-delay: 0.2s; }
        .splash-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotPulse {
            0%,100% { background: rgba(255,255,255,0.3); transform: scale(1); }
            50%      { background: rgba(255,255,255,0.9); transform: scale(1.3); }
        }

        /* ======= DARK MODE ======= */
        html.dark body    { background: #0f172a !important; color: #e2e8f0; }
        html.dark .card   { background: #1e293b !important; box-shadow: 0 2px 16px rgba(0,0,0,0.4) !important; }
        html.dark .bottom-nav  { background: #1e293b !important; border-color: #334155 !important; }
        html.dark footer       { background: #0f172a !important; }
        html.dark input, html.dark select, html.dark textarea { background: #1e293b !important; border-color: #475569 !important; color: #e2e8f0 !important; }
        html.dark .bg-gray-50  { background: #0f172a !important; }
        html.dark .bg-white    { background: #1e293b !important; }
        html.dark .text-gray-700 { color: #cbd5e1 !important; }
        html.dark .text-gray-600 { color: #94a3b8 !important; }
        html.dark .text-gray-500 { color: #94a3b8 !important; }
        html.dark .text-gray-400 { color: #64748b !important; }
        html.dark .text-gray-300 { color: #475569 !important; }
        html.dark .text-primary  { color: #60a5fa !important; }
        html.dark .border-gray-200, html.dark .border-gray-100 { border-color: #334155 !important; }
        html.dark .bg-gray-100   { background: #334155 !important; }
        html.dark .bg-blue-50    { background: #1e3a5f !important; }
        html.dark .bg-green-50   { background: #064e3b !important; }
        html.dark .bg-red-50     { background: #450a0a !important; }
        html.dark .bottom-nav a  { color: #64748b !important; }
        html.dark .bottom-nav a.active { color: #60a5fa !important; }

        /* ======= GRADIENTS ======= */
        .hero-gradient { background: linear-gradient(135deg, #0d2b6b 0%, #023e8a 50%, #0096c7 100%); }

        /* ======= CARDS ======= */
        .card { background: white; border-radius: 16px; box-shadow: 0 2px 16px rgba(13,43,107,0.07); }

        /* ======= BOTTOM NAV ======= */
        .bottom-nav { box-shadow: 0 -4px 20px rgba(0,0,0,0.08); }
        .bottom-nav a.active { color: #0d2b6b; }
        .bottom-nav a.active svg { color: #0d2b6b; }

        /* ======= BADGES ======= */
        .badge-new        { background:#dbeafe; color:#1d4ed8; }
        .badge-picked_up  { background:#fef9c3; color:#92400e; }
        .badge-washing    { background:#ede9fe; color:#6d28d9; }
        .badge-ready      { background:#d1fae5; color:#065f46; }
        .badge-out_for_delivery { background:#ffedd5; color:#9a3412; }
        .badge-delivered  { background:#dcfce7; color:#166534; }
        .badge-cancelled  { background:#fee2e2; color:#991b1b; }

        /* ======= ANIMATIONS ======= */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .animate-up   { animation: fadeInUp 0.5s ease both; }
        .animate-up-1 { animation-delay:.1s; }
        .animate-up-2 { animation-delay:.2s; }
        .animate-up-3 { animation-delay:.3s; }

        /* ======= MISC ======= */
        main { padding-bottom: calc(88px + env(safe-area-inset-bottom,8px)); }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #0d2b6b; box-shadow: 0 0 0 3px rgba(13,43,107,0.15); }
        .step-active  { background: #0d2b6b; color: white; }
        .step-done    { background: #00b4d8; color: white; }
        .step-pending { background: #e5e7eb; color: #9ca3af; }
        @keyframes pulse-blue { 0%,100%{box-shadow:0 0 0 0 rgba(13,43,107,0.3);} 50%{box-shadow:0 0 0 8px rgba(13,43,107,0);} }
        .pulse-blue { animation: pulse-blue 2s infinite; }
        [dir="rtl"] .rtl-flip { transform: scaleX(-1); }
        .sw-track { position:relative;width:52px;height:28px;background:#d1d5db;border-radius:9999px;cursor:pointer;transition:background .3s;display:flex;align-items:center; }
        .sw-track.on { background: #0d2b6b; }
        .sw-thumb { position:absolute;left:3px;width:22px;height:22px;background:white;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.25);transition:left .3s; }
        .sw-track.on .sw-thumb { left:27px; }
        .loyalty-section { background:linear-gradient(135deg,#e0f7ff 0%,#cce4ff 100%);border:1px solid #b3d9ff; }
        html.dark .loyalty-section { background:linear-gradient(135deg,#1e3a5f 0%,#1a3055 100%) !important;border-color:#1e40af !important; }
        /* Logo always white background — never changes in dark mode */
        .logo-wrap { background: white !important; }
    </style>
    <script>
        (function(){
            if(localStorage.getItem('theme')==='dark') document.documentElement.classList.add('dark');
        })();
        function toggleDark(){
            var d=document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme',d?'dark':'light');
            document.getElementById('icon-sun').style.display  = d?'block':'none';
            document.getElementById('icon-moon').style.display = d?'none':'block';
        }
        document.addEventListener('DOMContentLoaded',function(){
            var d=document.documentElement.classList.contains('dark');
            document.getElementById('icon-sun').style.display  = d?'block':'none';
            document.getElementById('icon-moon').style.display = d?'none':'block';
        });
    </script>
</head>
<body class="min-h-screen">

{{-- ===== SPLASH SCREEN ===== --}}
@if(!request()->is('admin*'))
<div id="splash-screen">
    <div class="splash-logo-wrap">
        <img src="/images/lamaa-logo.png" alt="لماء LAMAA">
    </div>
    <div class="splash-brand">
        <p>لماء LAMAA</p>
        <p>{{ app()->getLocale() === 'ar' ? 'خدمة غسيل احترافية' : 'Professional Laundry' }}</p>
    </div>
    <div class="splash-dots">
        <div class="splash-dot"></div>
        <div class="splash-dot"></div>
        <div class="splash-dot"></div>
    </div>
</div>
<script>
(function(){
    var s = document.getElementById('splash-screen');
    if(!s) return;
    if(sessionStorage.getItem('lamaa_splash')) { s.classList.add('hidden'); return; }
    sessionStorage.setItem('lamaa_splash','1');
    setTimeout(function(){
        s.classList.add('fade-out');
        setTimeout(function(){ s.classList.add('hidden'); }, 700);
    }, 2200);
})();
</script>
@endif

{{-- ===== TOP NAV ===== --}}
<nav class="hero-gradient text-white sticky top-0 z-50 shadow-lg">
    <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="logo-wrap rounded-xl flex items-center justify-center shadow" style="width:40px;height:40px;padding:4px;background:white!important;">
                <img src="/images/lamaa-logo.png" alt="لماء" style="width:32px;height:32px;object-fit:contain;">
            </div>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="toggleDark()" class="p-1.5 rounded-lg hover:bg-white/15 transition">
                <svg id="icon-moon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg id="icon-sun" class="w-5 h-5" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07l-.71.71M6.34 17.66l-.71.71m12.02 0l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/></svg>
            </button>
            @auth
                @if(!in_array(auth()->user()->role, ['admin', 'staff']))
                <a href="{{ route('notifications.index') }}" class="relative p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @php $unreadCount = $unreadCount ?? 0; @endphp
                    @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
                @endif
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <a href="{{ route('admin.notifications.index') }}" class="relative p-1.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $adminUnreadCount = $adminUnreadCount ?? 0; @endphp
                        @if($adminUnreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.profile') }}" class="p-1.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                    <a href="{{ route('admin.index') }}" class="bg-secondary text-white text-xs px-2.5 py-1.5 rounded-full font-bold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'الإدارة' : 'Admin' }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
                        <button type="submit" class="p-1.5 rounded-lg hover:bg-white/15 transition" title="تسجيل الخروج">
                            <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @endif
                <div class="hidden md:flex items-center gap-3 text-sm">
                    @if(!in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ route('dashboard') }}" class="hover:text-secondary transition">{{ app()->getLocale() === 'ar' ? 'لوحتي' : 'Dashboard' }}</a>
                    <a href="{{ route('orders.index') }}" class="hover:text-secondary transition">{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'Orders' }}</a>
                    <a href="{{ route('loyalty') }}" class="hover:text-secondary transition">{{ app()->getLocale() === 'ar' ? 'الولاء' : 'Loyalty' }}</a>
                    <a href="{{ route('profile') }}" class="hover:text-secondary transition">{{ app()->getLocale() === 'ar' ? 'حسابي' : 'Profile' }}</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
                        <button class="hover:text-red-300 transition">{{ app()->getLocale() === 'ar' ? 'خروج' : 'Logout' }}</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="bg-secondary text-white px-4 py-1.5 rounded-full text-sm font-bold hover:bg-secondary-light transition">
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
<div class="max-w-2xl mx-auto px-4 mt-3">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-2xl mb-3 flex items-center justify-between gap-2 animate-up">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 text-lg leading-none">×</button>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl mb-3 flex items-center gap-2 animate-up">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif
</div>

<main class="max-w-2xl mx-auto px-4 py-4">
    @yield('content')
</main>

<footer class="hidden md:block mt-8 bg-primary text-white/60 text-center py-5 text-sm">
    <p>لماء للمغاسل &copy; {{ date('Y') }} — جميع الحقوق محفوظة</p>
</footer>

{{-- BOTTOM NAV — customers only, hidden for admin/staff --}}
@auth
@if(!in_array(auth()->user()->role, ['admin', 'staff']))
<div class="bottom-nav fixed bottom-0 left-0 right-0 bg-white z-50 md:hidden border-t border-gray-100" style="padding-bottom:env(safe-area-inset-bottom,0px);">
    <div class="grid grid-cols-5 max-w-2xl mx-auto">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2.5 gap-0.5 text-gray-400 hover:text-primary transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-xs font-medium">{{ app()->getLocale() === 'ar' ? 'لوحتي' : 'Home' }}</span>
        </a>
        <a href="{{ route('orders.index') }}" class="flex flex-col items-center py-2.5 gap-0.5 text-gray-400 hover:text-primary transition {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('orders.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-xs font-medium">{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'Orders' }}</span>
        </a>
        <a href="{{ route('orders.create') }}" class="flex flex-col items-center py-1 gap-0.5 text-white">
            <div class="hero-gradient w-12 h-12 rounded-full flex items-center justify-center shadow-lg -mt-4 pulse-blue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </div>
            <span class="text-xs font-medium text-gray-500 mt-0.5">{{ app()->getLocale() === 'ar' ? 'طلب جديد' : 'New' }}</span>
        </a>
        <a href="{{ route('loyalty') }}" class="flex flex-col items-center py-2.5 gap-0.5 text-gray-400 hover:text-primary transition {{ request()->routeIs('loyalty') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('loyalty') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <span class="text-xs font-medium">{{ app()->getLocale() === 'ar' ? 'الولاء' : 'Loyalty' }}</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center py-2.5 gap-0.5 text-gray-400 hover:text-primary transition {{ request()->routeIs('profile') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('profile') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-xs font-medium">{{ app()->getLocale() === 'ar' ? 'حسابي' : 'Profile' }}</span>
        </a>
    </div>
</div>
@endif
@endauth

<script>if('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(()=>{});</script>

{{-- Install-app prompt (PWA) — shows a small banner once the browser signals the app is installable. --}}
<div id="pwaInstallBanner" class="fixed bottom-20 md:bottom-6 left-1/2 -translate-x-1/2 z-50 hidden bg-primary text-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 max-w-sm w-[92%]">
    <img src="/images/lamaa-logo.png" class="w-9 h-9 rounded-lg bg-white p-1 flex-shrink-0" alt="لماء">
    <div class="flex-1 text-xs">
        <p class="font-black">{{ app()->getLocale()==='ar' ? 'ثبّت تطبيق لماء' : 'Install LAMAA' }}</p>
        <p class="text-white/70">{{ app()->getLocale()==='ar' ? 'وصول أسرع، حتى بدون إنترنت' : 'Faster access, even offline' }}</p>
    </div>
    <button id="pwaInstallBtn" class="bg-secondary text-white text-xs font-bold px-3 py-1.5 rounded-full flex-shrink-0">{{ app()->getLocale()==='ar' ? 'تثبيت' : 'Install' }}</button>
    <button onclick="document.getElementById('pwaInstallBanner').classList.add('hidden')" class="text-white/60 text-lg leading-none px-1">×</button>
</div>
<script>
let deferredInstallPrompt = null;
window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    deferredInstallPrompt = e;
    if (!localStorage.getItem('lamaa_install_dismissed')) {
        document.getElementById('pwaInstallBanner').classList.remove('hidden');
    }
});
document.getElementById('pwaInstallBtn').addEventListener('click', function () {
    document.getElementById('pwaInstallBanner').classList.add('hidden');
    if (deferredInstallPrompt) {
        deferredInstallPrompt.prompt();
        deferredInstallPrompt = null;
    }
});
window.addEventListener('appinstalled', function () {
    document.getElementById('pwaInstallBanner').classList.add('hidden');
});
</script>
</body>
</html>
