@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-4">
    <div class="card p-6 text-center">
        <div class="w-20 h-20 hero-gradient rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <h1 class="text-xl font-black text-primary">{{ $user->name }}</h1>
        <p class="text-gray-500 text-sm">{{ $user->phone }}</p>
        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold {{ $user->role==='admin'?'bg-purple-100 text-purple-700':'bg-blue-50 text-blue-700' }}">{{ $user->role==='admin'?'مدير النظام':'عميل' }}</span>
    </div>

    {{-- Info summary --}}
    <div class="card p-5 space-y-3">
        @foreach([
            ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>','label'=>'الاسم','val'=>$user->name],
            ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>','label'=>'الجوال','val'=>$user->phone],
            ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>','label'=>'البريد','val'=>$user->email ?: '—'],
            ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>','label'=>'العنوان','val'=>$user->address ?: '—'],
            ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>','label'=>'المنطقة','val'=>$user->region ?? '—'],
        ] as $row)
        <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
            <div class="w-9 h-9 bg-primary/10 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $row['icon'] !!}</svg>
            </div>
            <div><p class="text-xs text-gray-500">{{ $row['label'] }}</p><p class="font-bold text-sm text-primary">{{ $row['val'] }}</p></div>
        </div>
        @endforeach
        @if($user->role !== 'admin')
        <div class="flex items-center gap-3 py-2">
            <div class="w-9 h-9 bg-secondary/10 rounded-xl flex items-center justify-center"><svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
            <div><p class="text-xs text-gray-500">إجمالي الطلبات</p><p class="font-black text-lg text-secondary">{{ $user->total_orders }}</p></div>
        </div>
        @endif
    </div>

    {{-- Edit profile --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            تعديل بياناتي
        </h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
            @csrf
            @foreach([
                ['name'=>'name',   'label'=>'الاسم الكامل',       'type'=>'text',  'val'=>$user->name,        'req'=>true,  'dir'=>''],
                ['name'=>'phone',  'label'=>'رقم الجوال',          'type'=>'tel',   'val'=>$user->phone,       'req'=>true,  'dir'=>'ltr'],
                ['name'=>'email',  'label'=>'البريد الإلكتروني',   'type'=>'email', 'val'=>$user->email,       'req'=>false, 'dir'=>'ltr'],
                ['name'=>'region', 'label'=>'المنطقة / الحي',      'type'=>'text',  'val'=>$user->region??'',  'req'=>false, 'dir'=>''],
                ['name'=>'address','label'=>'العنوان التفصيلي',    'type'=>'text',  'val'=>$user->address,     'req'=>false, 'dir'=>''],
            ] as $f)
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ $f['label'] }}</label>
                <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" value="{{ old($f['name'],$f['val']) }}" {{ $f['req']?'required':'' }}
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition" dir="{{ $f['dir'] }}">
                @error($f['name'])<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
            <button type="submit" class="w-full hero-gradient text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 transition">حفظ التغييرات</button>
        </form>
    </div>

    {{-- Change password --}}
    <div class="card p-5">
        <h3 class="font-black text-primary mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            تغيير كلمة المرور
        </h3>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="name"  value="{{ $user->name }}">
            <input type="hidden" name="phone" value="{{ $user->phone }}">
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">كلمة المرور الحالية</label>
                <input type="password" name="current_password" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition" placeholder="••••••••">
                @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">كلمة المرور الجديدة</label>
                <input type="password" name="new_password" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition" placeholder="••••••••">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">تأكيد كلمة المرور الجديدة</label>
                <input type="password" name="new_password_confirmation" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white transition" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-xl font-bold text-sm hover:opacity-90 transition">تحديث كلمة المرور</button>
        </form>
    </div>

    <form method="POST" action="{{ route('logout') }}">@csrf
        <button type="submit" class="w-full border-2 border-red-200 text-red-500 py-3 rounded-xl font-bold hover:bg-red-50 transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            تسجيل الخروج
        </button>
    </form>
</div>
@endsection
