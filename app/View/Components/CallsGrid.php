<?php

namespace App\View\Components;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\View\Component;

class CallsGrid extends Component {
    public array $headers;
    public ResourceCollection $calls;

    /**
     * Create a new component instance.
     *
     * @param array $headers
     * @param array $calls
     */
    public function __construct(array $headers, ResourceCollection $calls) {
        $this->headers = $headers;
        $this->calls = $calls;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render() {
        return view('components.calls-grid');
    }
}
