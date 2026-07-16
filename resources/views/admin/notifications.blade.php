@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-3">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-primary">إشعارات الإدارة</h1>
        @if($notifications->count())
        <form method="POST" action="{{ route('admin.notifications.clearAll') }}" onsubmit="return confirm('مسح كل الإشعارات؟')">
            @csrf
            <button type="submit" class="text-xs text-red-500 font-bold">مسح الكل</button>
        </form>
        @endif
    </div>

    @if($notifications->count())
    @foreach($notifications as $n)
    <div class="card p-4 flex items-start gap-3 {{ !$n->is_read ? 'border-r-4 border-secondary' : '' }}">
        <div class="w-10 h-10 rounded-xl {{ !$n->is_read ? 'hero-gradient' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ !$n->is_read ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm text-primary">{{ $n->title_ar }}</p>
            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $n->body_ar }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
        </div>
    </div>
    @endforeach
    @else
    <div class="card p-10 text-center">
        <div class="text-5xl mb-3">🔔</div>
        <p class="font-bold text-gray-500">لا يوجد إشعارات</p>
    </div>
    @endif
</div>
@endsection
