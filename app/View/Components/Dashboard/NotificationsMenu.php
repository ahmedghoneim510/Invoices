<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationsMenu extends Component
{
    public $notifications;
    public $count;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user=auth()->user();
        $this->notifications=$user->unreadNotifications()->take(5)->get();
        $this->count=$user->unreadNotifications()->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.notifications-menu');
    }
}
