@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <h3 class="card-title text-muted">Create DO - {{ $pre['fr_num'] }}</h3>
            <form action="{{URL::route('do.add')}}" id="form-add" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <h4>Detail</h4>
                        <hr>
                        <input type="hidden" name="deliver_by" value="{{Auth::user()->username}}">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">From</label>
                            <div class="col-md-6">
                                <select name="from" id="from" class="form-control" required>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">To</label>
                            <div class="col-md-6">
                                <select name="to" id="to" class="form-control" required>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Location</label>
                            <div class="col-md-6">
                                <input type="text" name="location" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Division</label>
                            <div class="col-md-6">
                                <input type="text" name="division" class="form-control" value="{{ $pre->division }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Delivery Time</label>
                            <div class="col-md-6">
                                <input type="date" name="delivery_time" id="delivery_time" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Notes</label>
                            <div class="col-md-6">
                                <textarea name="notes" id="fr_note" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Moving Item</h4>
                        <hr>
                        <div class="form-group row">
                            <table class="table table-bordered" id="list_item">
                                <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>UoM</th>
                                    <th>Quantity</th>
                                    <th>Transfer Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if (count($detail) == 0)
                                        <tr>
                                            <td align="center" colspan="5">
                                                Cannot list items, because the items is in purchasing
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($detail as $i => $item)
                                            @if (isset($data_item[$item->item_id]))
                                                <tr>
                                                    <td>
                                                        {{ $item->item_id }}
                                                        <input type="hidden" name="code[]" value="{{ $item->item_id }}">
                                                        <input type="hidden" name="qty[]" value="{{ ($item->qty_deliver == 0) ? $item->qty : $item->qty_deliver }}">
                                                    </td>
                                                    <td>{{ $data_item[$item->item_id]['name'] }}</td>
                                                    <td>{{ $data_item[$item->item_id]['uom'] }}</td>
                                                    <td>
                                                        {{ ($item->qty_deliver == 0) ? $item->qty : $item->qty_deliver }}
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="transfer_type[]" id="transfer_type">
                                                            <option value="Transfer">Transfer</option>
                                                            <option value="Transfer & Use">Transfer & Use</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <span class="form-text text-muted">* UoM is Unit of Measurement</span>
                        </div>

                    </div>
                    <div class="col-md-12 text-right">
                        <input type="hidden" name="fr_id" value="{{ $pre['id'] }}">
                        @if (count($detail) > 0)
                        <button type="submit" id="btn-submit" class="btn btn-primary">Add</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function getURLProject(){
            var url = "{{URL::route('do.getWh')}}";
            return url;
        }
        $(document).ready(function(){
            $('#from').select2({
                ajax: {
                    url: function (params) {
                        return getURLProject()
                    },
                    type: "GET",
                    placeholder: 'From',
                    allowClear: true,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function (response) {
                        console.log(response)
                        return {
                            results: response
                        };
                    },
                    cache: false
                },
                width:"100%"
            })
            $('#to').select2({
                ajax: {
                    url: function (params) {
                        return getURLProject()
                    },
                    type: "GET",
                    placeholder: 'To',
                    allowClear: true,
                    dataType: 'json',
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: false
                },
                width:"100%"
            })
        })
    </script>
@endsection
