<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public string $type;
    public ?string $label;
    public ?string $placeholder;
    public bool $required;
    public ?string $error;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'text',
        ?string $label = null,
        ?string $placeholder = null,
        bool $required = false,
        ?string $error = null
    ) {
        $this->type = $type;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->error = $error;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
