@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto animate-up space-y-3">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-black text-primary">{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}</h1>
        @if($notifications->count())
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="text-xs text-secondary font-bold">{{ app()->getLocale() === 'ar' ? 'قراءة الكل' : 'Mark all read' }}</button>
            </form>
            <form method="POST" action="{{ route('notifications.clearAll') }}" onsubmit="return confirm('{{ app()->getLocale() === 'ar' ? 'مسح كل الإشعارات؟' : 'Clear all notifications?' }}')">
                @csrf
                <button type="submit" class="text-xs text-red-500 font-bold">{{ app()->getLocale() === 'ar' ? 'مسح الكل' : 'Clear all' }}</button>
            </form>
        </div>
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
            <p class="font-bold text-sm text-primary {{ !$n->is_read ? '' : 'text-gray-600' }}">
                {{ app()->getLocale() === 'ar' ? $n->title_ar : $n->title }}
            </p>
            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ app()->getLocale() === 'ar' ? $n->body_ar : $n->body }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
        </div>
        @if(!$n->is_read)
        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
            @csrf
            <button type="submit" class="w-2 h-2 rounded-full bg-secondary flex-shrink-0 mt-1.5"></button>
        </form>
        @endif
    </div>
    @endforeach
    @else
    <div class="card p-10 text-center">
        <div class="text-5xl mb-3">🔔</div>
        <p class="font-bold text-gray-500">{{ app()->getLocale() === 'ar' ? 'لا يوجد إشعارات' : 'No notifications' }}</p>
    </div>
    @endif
</div>
@endsection
