<?php

namespace App\View\Components;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\View\Component;

class CallsGrid extends Component {
    public array $headers = [
        'Call ID',
        'Date',
        'Duration',
        'Notes',
        'Type',
        'Agent',
        'Customer',
        'Customer Phone',
    ];

    public ResourceCollection $calls;

    public function __construct(ResourceCollection $calls) {
        $this->calls = $calls;
    }

    public function render() {
        return view('components.calls-grid');
    }
}
