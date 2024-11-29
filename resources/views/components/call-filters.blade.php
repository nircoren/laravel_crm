<div class="filters-container">
    <div>
        <label for="from_date">From Date</label>
        <input type="date" id="from_date" name="from_date" value="{{ $fromDate }}">
    </div>

    <div>
        <label for="to_date">To Date</label>
        <input type="date" id="to_date" name="to_date" value="{{ $toDate }}">
    </div>

    <div>
        <label for="filters_call_agent_id">Agent</label>
        <select id="filters_call_agent_id" name="filters[agents][id]">
            <option value="" disabled selected>-- Select Agent --</option>
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}"
                    {{ $selectedAgent == $agent->id ? 'selected' : '' }}>
                    {{ $agent->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Dynamic filters --}}
    <div>
        <label>Dynamic:</label>
        @foreach($models as $model => $fields)
            <div>
                <label for="filters[{{ $model }}]">{{ $model }}</label>
                <select
                    class="form-control form-{{$model}}"
                    data-model="{{ $model }}">
                    <option value="" disabled selected>Select a field</option>
                    @foreach($fields as $field)
                        <option value="{{ $field }}">{{ $field }}</option>
                    @endforeach
                </select>
                <input
                    readonly
                    disabled
                    type="text"
                    class="form-control dynamic-input-{{$model}}"
                    placeholder="Enter value">
            </div>
        @endforeach
    </div>

    <div class="button-container">
        <button id="submit" type="submit">Filter</button>
    </div>
</div>

<script>
    // Add name to input (in order to send the filter in query string)
    document.querySelectorAll('.form-control').forEach(function(element) {
        element.addEventListener('change', function() {
            const input = document.querySelector('.dynamic-input-' + this.dataset.model);
            input.disabled = false;
            input.removeAttribute('readonly');
            input.name = `filters[${ this.dataset.model }][${this.value}]`;

            console.log(input.name);
        });
    });
</script>
