<?php

namespace App\View\Components\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationsMenu extends Component
{
    public $notifications;
    // public $count;
    public $newCount;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($count = 10)
    {
        $user = Auth::user();
        // notifications() is a relationship inside Notifiable trait which used in User model || Biulted by Laravel
        // $this->notifications = $user->notifications()->limit(8)->get();
        $this->notifications = $user->notifications()->take($count)->get(); // notifications() ==> here is adding on relationship query [Query Builder]
        $this->newCount = $user->unreadNotifications->count(); // unreadNotifications  ==> here is counting Collection [Collections]
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.notifications-menu');
    }
}
