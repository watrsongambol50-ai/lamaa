<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $unreadCount = \App\Models\OrderNotification::where('customer_id', $user->id)
                    ->where('is_read', false)->count();
                $view->with('unreadCount', $unreadCount);

                if (in_array($user->role, ['admin', 'staff'], true)) {
                    $adminUnreadCount = \App\Models\OrderNotification::where('audience', 'admin')
                        ->where('is_read', false)->count();
                    $view->with('adminUnreadCount', $adminUnreadCount);
                }
            } else {
                $view->with('unreadCount', 0);
            }
        });
    }
}
