<div class="filters-container" style="margin-bottom: 20px; display: flex; gap: 15px; justify-content: center;">
    <div>
        <label for="from_date" style="display: block; font-weight: bold; margin-bottom: 5px;">From Date</label>
        <input type="date" id="from_date" name="from_date" value="{{ $fromDate }}"
               style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>

    <div>
        <label for="to_date" style="display: block; font-weight: bold; margin-bottom: 5px;">To Date</label>
        <input type="date" id="to_date" name="to_date" value="{{ $toDate }}"
               style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>

    <div>
        <label for="filters_call_agent_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Agent</label>
        <select id="filters_call_agent_id" name="filters[call][agent_id]"
                style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; min-width: 150px;">
            <option value="">-- Select Agent --</option>
            @foreach($agents as $agent)
                <option value="{{ $agent->id }}"
                    {{ $selectedAgent == $agent->id ? 'selected' : '' }}>
                    {{ $agent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; align-items: end">
        <button type="submit" style="align-self:end; padding: 10px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Filter
        </button>
    </div>
</div>
