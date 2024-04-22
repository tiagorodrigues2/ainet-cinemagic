<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class toast extends Component
{
    private string $classes = '';
    private string $title = 'Info';

    public function __construct(
        private string $type = 'info',
        private string $message = 'Info message',
    )
    {
        switch ($this->type) {
            case 'success':
                $this->title = 'Success';
                $this->classes = 'bg-green-100 border-green-500 border-2 border-solid text-green-700';
                break;
            case 'warning':
                $this->title = 'Warning';
                $this->classes = 'bg-yellow-100 border-yellow-500 border-2 border-solid text-yellow-700';
                break;
            case 'error':
                $this->title = 'Error';
                $this->classes = 'bg-red-100 border-red-500 border-2 border-solid text-red-700';
                break;
            default:
                $this->title = 'Info';
                $this->classes = 'bg-blue-100 border-blue-500 border-2 border-solid text-blue-700';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toast')->with('title', $this->title)->with('message', $this->message)->with('classes', $this->classes);
    }
}
