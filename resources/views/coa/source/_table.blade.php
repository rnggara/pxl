<table class="table table-bordered table-hover table-responsive-sm">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">#Paper</th>
            <th class="text-center">Date</th>
            <th class="text-center">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td align="center">{{ $item['i'] }}</td>
                <td align="center">
                    @if (isset($item['url']))
                    <a href="{{ $item['url'] }}" target="_blank" class="label label-inline label-lg label-primary bg-hover-light-primary text-hover-primary">{{ $item['paper'] }}</a>
                    @else
                    <label for="" class="label label-inline label-lg label-primary">{{ $item['paper'] }}</label>
                    @endif
                </td>
                <td align="center">
                    {{ $item['date'] }}
                </td>
                <td align="center">
                    <a href="#" onclick="_signed({{ $item['id'] }}, '{{ $item['type'] }}', {{ (!empty($item['tc_id'])) ? $item['tc_id'] : 'null' }}, '{{ $item['paper'] }}', '{{ (empty($item['code'])) ? 'null' : $item['code'] }}')">{{ (empty($item['code'])) ? 'unassigned' : $item['code'] }}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
