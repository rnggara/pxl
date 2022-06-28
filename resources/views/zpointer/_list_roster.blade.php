<form action="" method="post">
    <div class="modal-body">
        <div class="row">
            @php
                $total_wage = 0;
            @endphp
            @foreach ($roster as $char)
                @php
                    $notes = json_decode($char->notes, true);
                    $spec = json_decode($char->specification, true);
                    $total_wage += $char->wage_day;
                @endphp
                <div class="bg-hover-primary border border-2 border-primary col-md-12 cursor-pointer mb-5 p-4 text-hover-white" onclick="_assign_roster('{{ $type }}', {{ $char->id }}, '{{ $char->name }}')">
                    <div class="d-flex align-items-center justify-content-around">
                        <div class="symbol symbol-100 symbol-circle mr-3">
                            <img alt="Pic" src="{{ asset("characters/images/$char->picture") }}"/>
                        </div>
                        <div class="d-flex flex-column mr-3">
                            <span class="font-weight-bolder font-size-h4">{{ $char->name }}</span>
                            <div class="d-flex justify-content-between">
                                <table>
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
    </div>
</form>
