<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private $users,
        private $type
    )
    {
        if ($type != 'customers' || $type != 'employees') {
            $type = 'customers';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-list')->with('users', $this->users)->with('type', $this->type);
    }
}
