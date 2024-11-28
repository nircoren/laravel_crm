@props(['calls', 'headers'])

<div class="calls-grid" style="display: flex; justify-content: center; margin-top: 20px;">
    <table class="table" style="border-collapse: collapse; width: 80%; text-align: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden;">
        <thead>
        <tr style="background-color: #007BFF; color: white; border-bottom: 2px solid #0056b3;">
            @foreach($headers as $label => $key)
                <th style="padding: 15px 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; text-align: center;">{{ $key }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($calls as $call)
            <tr style="border-bottom: 1px solid #f1f1f1; background-color: {{ 1 % 2 == 0 ? '#fafafa' : '#fff' }};">
                @foreach($call as $prop)
                    <td style="padding: 12px 20px; font-size: 14px; color: #333;">{{ $prop }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="pagination" style="display: flex; justify-content: center; margin-top: 20px;">
    <nav aria-label="Page navigation example">
        {{ $calls->links('pagination::bootstrap-4') }}
    </nav>
</div>

<style>
    nav ul {
        display: flex;
        gap: 15px;
    }

    li {
        list-style: none;
        font-size: 20px;
    }

</style>
