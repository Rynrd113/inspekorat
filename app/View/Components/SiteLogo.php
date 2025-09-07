<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SiteLogo extends Component
{
    public $variant;
    public $size;
    public $showText;
    public $logoUrl;
    public $siteName;

    /**
     * Create a new component instance.
     */
    public function __construct($variant = 'header', $size = 'md', $showText = true)
    {
        $this->variant = $variant;
        $this->size = $size;
        $this->showText = $showText;
        $this->logoUrl = site_logo($variant);
        $this->siteName = config('app.name', 'Portal Inspektorat');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.site-logo');
    }
}
