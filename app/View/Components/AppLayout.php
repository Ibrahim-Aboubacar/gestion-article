<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public string $xData = '{}',
    ) {
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {

        return view('layouts.app', ['xData', $this->xData]);
    }
}
