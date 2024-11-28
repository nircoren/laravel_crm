<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CallsGrid extends Component
{
    public $headers;
    public $calls;
    public $page;

    /**
     * Create a new component instance.
     *
     * @param array $headers
     * @param array $calls
     */
    public function __construct($headers, $calls)
    {
        $this->headers = $headers;
        $this->calls = $calls;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.calls-grid');
    }
}
