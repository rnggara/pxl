@extends('layouts.templateContract')

@section('content')
    <div class="card card-custom gutter-b ">
        <div class="card-header">
            <h3 class="card-title">Employee PPE : {{ $emp->emp_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($ppe->enable == 0)
            <div class="card-body">
                <div class="row">
                    <div class="col-4 mx-auto">
                        <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">Request has been <span class="font-weight-bold">DISABLED</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                @if (!empty($ppe->do_id))
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mx-auto">
                            <div class="alert alert-custom alert-outline-success fade show mb-5" role="alert">
                                <div class="alert-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="alert-text">
                                    No DO : {{ $do->no_do }}
                                    <br>
                                    Please give this QR Code to Asset to receive your belongings
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mx-auto text-center">
                            <img src="{{ $qr }}" alt="">
                        </div>
                    </div>
                </div>
                @else
                <form action="{{ route('employee.hrd.ppe_do') }}" method="post">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered display">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($template as $item)
                                        <tr>
                                            <td style="width: 30%">{{ $item->description }}</td>
                                            <td>
                                                <select name="item[{{ $item->id }}]" class="form-control select2 item-post" data-placeholder="Choose Size" required>
                                                    <option value=""></option>
                                                    @php
                                                        $js = json_decode($item->items, true)
                                                    @endphp
                                                    @foreach ($js as $val)
                                                        @if (isset($_items[$val]))
                                                            <option value="{{ $val }}">{{ $_items[$val] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" align="right">
                                            @csrf
                                            <input type="hidden" name="id_ppe" value="{{ $ppe->id }}">
                                            <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                @endif
            @endif

        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })

            $("#btn-submit").click(function(e){
                var item = 0
                var count_item = $(".item-post").toArray().length
                $(".item-post").each(function(){
                    if($(this).val() != ""){
                        item++
                    }
                })
                if(item == count_item){
                    e.preventDefault()
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes!"
                    }).then(function(result) {
                        if (result.value) {
                            var form = $("#btn-submit").parents("form")
                            form.submit()
                        }
                    });
                }
            })
        })
    </script>
@endsection
