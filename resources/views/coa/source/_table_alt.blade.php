<table class="table table-bordered table-hover display">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Type WO</th>
            <th class="text-center">Total Items</th>
            <th class="text-center">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $num = 1;
        @endphp
        @foreach ($typewo as $id_type => $item)
            @if (isset($t_wo[$id_type]))
                @php
                    $tc_id = 0;
                    $tc_code = null;
                    foreach ($t_wo[$id_type] as $key => $value) {
                        if(!empty($value)){
                            $tc_id++;
                            $tc_code = $value;
                        }
                    }

                    $code = (isset($tc[$tc_code])) ? $tc[$tc_code] : null;
                @endphp
                <tr>
                    <td align="center">{{ $num++ }}</td>
                    <td align="center">
                        {{ $item }}
                    </td>
                    <td align="center">
                        {{ count($t_wo[$id_type]) }}
                    </td>
                    <td align="center">
                        {{-- <a href="#" onclick="_signed({{ $id_type }}, '{{ $tid }}', {{ (!empty($tc_code)) ? $tc_code : 'null' }}, '{{ $item }}')"> --}}
                        <a href="{{ route('coa.source.assignment', ['type' => $category, 'id' => $id_type]) }}">
                            assign
                            {{-- {{ (empty($code)) ? 'unasigned' : $code }}
                            @if ($tc_id > 0 && $tc_id < count($t_wo[$id_type]))
                                <br> unasigned ({{ count($t_wo[$id_type]) - intval($tc_id) }})
                            @endif --}}
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
