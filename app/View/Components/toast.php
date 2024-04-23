<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class toast extends Component
{
    private string $title = 'Info';

    public function __construct(
        private string $type = 'info',
        private string $message = 'Info message',
    )
    {
        switch ($this->type) {
            case 'success':
                $this->title = 'Success';
                break;
            case 'warning':
                $this->title = 'Warning';
                break;
            case 'error':
                $this->title = 'Error';
                break;
            default:
                $this->title = 'Info';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toast')->with('title', $this->title)->with('message', $this->message)->with('type', $this->type);
    }
}
