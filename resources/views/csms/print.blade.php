<table style="width: 100%" border="1">
    <tr>
        <td align="center">
            <h1>{{ $csms->name }}</h1>
        </td>
    </tr>
    @foreach ($step as $item)
        <tr>
            <td>
                <h3>{!! $item->name !!}</h3>
                <div>
                    @if (isset($file[$item->id]))
                        @foreach ($file[$item->id] as $up)
                            @if (isset($get_file[$up->file_code]))
                                @php
                                    $file = explode('.', $get_file[$up->file_code]);
                                    if (in_array(end($file), ["pdf", "xls", "xlsx", 'csv', "docx", "docx"])){

                                    }
                                @endphp
                                <iframe src="{{ str_replace('public', 'public_html', asset($get_file[$up->file_code])) }}" frameborder="0" style="width: 100%; height: 100%"></iframe>
                            @endif
                        @endforeach
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
</table>
