<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $type;
    public string $variant;
    public string $size;
    public bool $disabled;
    public ?string $href;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'button',
        string $variant = 'primary',
        string $size = 'md',
        bool $disabled = false,
        ?string $href = null
    ) {
        $this->type = $type;
        $this->variant = $variant;
        $this->size = $size;
        $this->disabled = $disabled;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
