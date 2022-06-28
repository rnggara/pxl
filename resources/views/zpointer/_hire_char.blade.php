<div class="d-flex align-items-center">
    <div class="symbol symbol-100 symbol-circle mr-3">
        <img alt="Pic" src="{{ asset("characters/images/$char->picture") }}"/>
    </div>
    <div class="d-flex flex-column mr-3 justify-content-between">
        <span class="font-weight-bolder font-size-h4">{{ $char->name }}</span>
        <div class="d-flex justify-content-between">
            {{-- <table>
                <tr><td>AGE</td> <td>:</td> <td>{{ $char->price2 }}</td></tr>
                <tr><td>VIT</td> <td>:</td> <td>{{ $notes['vit'] }}</td></tr>
                <tr><td>SPD</td> <td>:</td> <td>{{ $notes['spd'] }}</td></tr>
                <tr><td>LUC</td> <td>:</td> <td>{{ $notes['luc'] }}</td></tr>
            </table>
            <table>
                <tr><td>HP</td> <td>:</td> <td>{{ $spec['hp'] }}</td></tr>
                <tr><td>STR</td> <td>:</td> <td>{{ $spec['str'] }}</td></tr>
                <tr><td>AGI</td> <td>:</td> <td>{{ $spec['agi'] }}</td></tr>
                <tr><td>KI</td> <td>:</td> <td>{{ $spec['ki'] }}</td></tr>
            </table> --}}
            <form action="{{ route("home.hire.char") }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $char->id }}">
                <button type="submit" class="btn btn-primary btn-sm w-100px">Hire ({{ (\Auth::user()->first_hire == 1) ? "Free" : "60 Nc" }})</button>
                @if ($skip > 0)
                <button type="button" class="btn btn-google btn-sm w-100px" data-skip="1" onclick="_find_char(this)">Skip</button>
                @endif
            </form>
        </div>
        @if (\Auth::user()->first_hire == 1)
        <span class="text-muted">*free for first hire</span>
        @endif
    </div>
    <div class="d-flex flex-column justify-content-around">
        {{-- <form action="{{ route("home.hire.char") }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $char->id }}">
            <button type="submit" class="btn btn-primary btn-sm h-50px mb-5 w-100px">Hire ({{ $char->wage_start }} Nc)</button>
        </form>
        @if ($skip > 0)
        <button type="button" class="btn btn-google btn-sm h-50px w-100px" data-skip="1" onclick="_find_char(this)">Skip</button>
        @endif --}}
    </div>
</div>
