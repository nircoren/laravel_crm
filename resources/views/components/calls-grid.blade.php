@props(['calls', 'headers'])

<div class="calls-grid">
    <table class="table">
        <thead>
        <tr>
            @foreach($headers as $label => $key)
                <th>{{ $key }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($calls as $call)
            <tr>
                @foreach($call as $prop)
                    <td>{{ $prop }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">
    <nav aria-label="Page navigation example">
        {{-- Get page calls and keep query filters --}}
        {{ $calls->appends(request()->query())->links('pagination::bootstrap-4') }}
    </nav>
</div>
