<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SocialLinks extends Component
{
    public $links;
    public $size;
    public $color;
    public $showText;

    /**
     * Create a new component instance.
     */
    public function __construct($size = 'md', $color = 'default', $showText = false)
    {
        $this->links = social_links();
        $this->size = $size;
        $this->color = $color;
        $this->showText = $showText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.social-links');
    }
}
