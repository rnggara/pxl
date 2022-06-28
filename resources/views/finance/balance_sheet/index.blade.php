@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Balance Sheet <br>
                @if (isset($from))
                    Period : {{ date("d F Y", strtotime($from)) }} - {{ date("d F Y", strtotime($to)) }}
                @else
                    Period: {{date('d F Y', strtotime(date('Y')."-01-01"))}} - {{date('d F Y', strtotime(date('Y')."-".date('m')."-".date('t')))}}
                @endif
            </div>
            <div class="card-toolbar">
                <a href="{{ route('bs.list') }}" class="btn btn-icon btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8 mx-auto">
                    <form class="form" action="{{route('bs.find')}}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <select name="project[]" multiple class="form-control select2" id="sel-prj" data-placeholder="All Project">
                                    @foreach ($projects as $id => $name)
                                        <option value="{{ $id }}" {{ (!empty($prj_selected) && in_array($id, $prj_selected)) ? "SELECTED" : "" }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" id="start-date" class="form-control mr-3" value="{{(isset($from)) ? $from : date('Y')."-01-01"}}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" id="end-date" class="form-control" value="{{(isset($to)) ? $to : date('Y')."-".date('m')."-".date('t')}}">
                            </div>
                            <div class="col-md-2">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="submit" id="btn-search" class="btn btn-primary" ><i class="fa fa-search"></i>Search</button>
                                    @if (!empty($from))
                                        <button type="submit" name="pdf" value="1" class="btn btn-info"><i class="fa fa-file-pdf"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-10">
        <div class="col-md-8 mx-auto">
        </div>
    </div>

    {{-- @if (!empty($from)) --}}
    <?php

        function _child_tr($list_child, $item, $level, $coa_code, $from, $to, $position){
            $tr = "";
            $level += 1;
            $strip = "";
            for ($i=0; $i < $level; $i++) {
                $strip .= "<i class='flaticon2-line opacity-1'></i>";
                if (($level - $i) == 1) {
                    $strip .= "<i class='la la-level-up-alt' style='transform: rotate(90deg)'></i>";
                }
            }
            if(isset($list_child[$item->id])){
                foreach($list_child[$item->id] as $child){
                    $btn = "";
                    if(empty($child->tc)){
                        $btn .= '<button type="button" data-label="'.$child->description.'" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, \''.$child->type.'\', '.$child->id.', '.$position.')"><i class="fa fa-plus"></i></button>';
                    }
                    if (!isset($list_child[$child->id])) {
                        $btn .= '<button type="button" data-label="'.$child->description.'" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_edit_modal('.$child->id.')"><i class="fa fa-cog"></i></button>';
                        $btn .= '<button type="button" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete('.$child->id.')"><i class="fa fa-trash"></i></button>';
                    }

                    $tcSpan = "";
                    $align = "";

                    if (!empty($child->tc)) {
                        $btn .= '<a href="'.route('bs.export', $child->id).'?s='.$from.'&to='.$to.'" data-label="'.$child->description.'" class="btn btn-icon btn-xs btn-outline-success ml-2"><i class="fa fa-file-csv"></i></a>';
                        $tc = json_decode($child->tc);
                        if(is_array($tc) && !empty($tc)){
                            foreach ($tc as $tcVal) {
                                if(isset($coa_code[$tcVal])){
                                    $tcSpan .='<span class="tc-value" data-value="'.$coa_code[$tcVal].'"></span>';
                                }
                            }
                            $tcSpan .= "IDR <span class='tc-sum'>0</span>";
                            $align = "right";
                        }
                    }
                    $_desc = $child->description;
                    if(!empty($child->tc)){
                        $uri = route('bs.view', $child->id);
                        $_desc = "<a href='$uri'>$child->description</a>";
                    }
                    $desc = "<div class='row'><div class='col-8'>$strip $_desc</div><div class='col-4 text-right'>$btn</div></div>";
                    $tr .= "<tr><td>$desc</td><td align='$align'>$tcSpan</td></tr>";
                    if (isset($list_child[$child->id])) {
                        $tr .= _child_tr($list_child, $child, $level, $coa_code, $from, $to, $position);
                    }
                }
            }

            return $tr;
        }

    ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Assets</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-primary ml-2 btn-icon" onclick="_modal(this, 'asset', null, 'AKTIVA')">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($detail['asset']))
                        <table class="table table-hover  table-responsive-sm">
                            @foreach ($detail['asset'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                @if (empty($item->tc))
                                                    {{ $item->description }}
                                                @else
                                                <a href="{{ route('bs.view', $item->id) }}">{{ $item->description }}</a>
                                                @endif
                                            </div>
                                            <div class="col-4 text-right">
                                                <div class="button-group">
                                                    @if (empty($item->tc))
                                                        <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, 'asset', {{ $item->id }}, 'AKTIVA')"><i class="fa fa-plus"></i></button>
                                                        @if (!isset($detail_child[$item->id]))
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_edit_modal({{ $item->id }})"><i class="fa fa-cog"></i></button>
                                                            {{-- <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button> --}}
                                                        @endif
                                                    @endif

                                                    @if (!isset($detail_child[$item->id]))
                                                    <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $span .= "<span class='tc-value' data-aktifa='1' data-value='$coa_code[$value]'></span>";
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>0</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child_tr($detail_child, $item, 0, $coa_code, $from, $to, "AKTIVA") !!}
                            @endforeach
                        </table>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <h3>Total</h3>
                    <h3>IDR <span class="tc-total" id="total-asset">0</span></h3>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Liability</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-primary ml-2 btn-icon" onclick="_modal(this, 'liability', null, 'PASIVA')"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($detail['liability']))
                        <table class="table table-hover  table-responsive-sm">
                            @foreach ($detail['liability'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                @if (empty($item->tc))
                                                    {{ $item->description }}
                                                @else
                                                    <a href="{{ route('bs.view', $item->id) }}">{{ $item->description }}</a>
                                                @endif
                                            </div>
                                            <div class="col-4 text-right">
                                                <div class="button-group">

                                                    @if (empty($item->tc))
                                                        <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, 'asset', {{ $item->id }}, 'PASIVA')"><i class="fa fa-plus"></i></button>
                                                        @if (!isset($detail_child[$item->id]))
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_edit_modal({{ $item->id }})"><i class="fa fa-cog"></i></button>
                                                            {{-- <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button> --}}
                                                        @endif
                                                    @endif
                                                    @if (!isset($detail_child[$item->id]))
                                                    <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $span .= "<span class='tc-value' data-aktifa='0' data-value='$coa_code[$value]'></span>";
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>0</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child_tr($detail_child, $item, 0, $coa_code, $from, $to, "PASIVA") !!}
                            @endforeach
                        </table>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <h3>Total</h3>
                    <h3>IDR <span class="tc-total" id="total-liability">0</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1"></div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-footer d-flex justify-content-between">
                    <h3>Sub Total</h3>
                    <h3>IDR <span id="sub-total-left"></span></h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Equity</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" id="btn-search" class="btn btn-primary ml-2 btn-icon" onclick="_modal(this, 'equity', null, 'PASIVA')"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($detail['equity']))
                        <table class="table table-hover  table-responsive-sm">
                            @foreach ($detail['equity'] as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-8">
                                                @if (empty($item->tc))
                                                    {{ $item->description }}
                                                @else
                                                    <a href="{{ route('bs.view', $item->id) }}">{{ $item->description }}</a>
                                                @endif
                                            </div>
                                            <div class="col-4 text-right">
                                                <div class="button-group">

                                                    @if (empty($item->tc))
                                                        <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-primary ml-2" onclick="_modal(this, 'asset', {{ $item->id }}, 'PASIVA')"><i class="fa fa-plus"></i></button>
                                                        @if (!isset($detail_child[$item->id]))
                                                            <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-dark ml-2" onclick="_edit_modal({{ $item->id }})"><i class="fa fa-cog"></i></button>
                                                            {{-- <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button> --}}
                                                        @endif
                                                    @endif

                                                    @if (!isset($detail_child[$item->id]))
                                                    <button type="button" data-label="{{ $item->description }}" class="btn btn-icon btn-xs btn-outline-danger ml-2" onclick="_delete({{ $item->id }})"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        $span = "";
                                        $align ="";
                                    @endphp
                                    @if (!empty($item->tc))
                                            @php
                                                $tc = json_decode($item->tc, true);
                                                if (is_array($tc) && !empty($tc)) {
                                                    foreach ($tc as $key => $value) {
                                                        if (isset($coa_code[$value])) {
                                                            $span .= "<span class='tc-value' data-aktifa='0' data-value='$coa_code[$value]'></span>";
                                                        }
                                                    }
                                                    $span .= "IDR <span class='tc-sum'>0</span>";
                                                    $align = "right";
                                                }
                                            @endphp
                                        @endif
                                    <td align="{{ $align }}">
                                        {!! $span !!}
                                    </td>
                                </tr>
                                {!! _child_tr($detail_child, $item, 0, $coa_code, $from, $to, 'PASIVA') !!}
                            @endforeach
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-8">
                                            PROFIT & LOSS
                                        </div>
                                    </div>
                                </td>
                                <td align="right">
                                    IDR <span id="pl">{{ number_format($pl_val, 2) }}</span>
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <h3>Total</h3>
                    <h3>IDR <span class="tc-total" id="total-equity">0</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1"></div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Total</h3>
                    </div>
                    <div class="card-toolbar">
                        <h3>IDR <span id="total-left"></span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Total</h3>
                    </div>
                    <div class="card-toolbar">
                        <h3>IDR <span id="total-right"></span></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}



    <div class="modal fade" id="modalAddChild" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="title-add"></span> - Add New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('bs.detail.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="asset" value="1">
                        <div class="form-group row">
                            <label for="" class="col-md-2 col-form-label">Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                            <div class="col-md-10">
                                <select name="tc[]" class="form-control select2" multiple id="">
                                    <option value="">&nbsp;</option>
                                    @foreach($coa as $value)
                                        <option value="{{$value->id}}"
                                        @if($setting != null)
                                            @foreach(json_decode($setting->assets) as $item)
                                                {{($item == $value->id) ? "SELECTED" : ""}}
                                                @endforeach
                                            @endif
                                        >{{"[".$value->code."] ".$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="coa-target"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="position" id="position">
                        <input type="hidden" name="type" id="type-hide">
                        <input type="hidden" name="parent_id" id="parent-id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditChild" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="edit-child-content">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        function _delete(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = '{{ route('bs.child_delete') }}/'+id
                }
            });
        }

        function _edit_modal(id){
            $("#modalEditChild").modal('show')
            $.ajax({
                url : "{{ route('bs.child_edit') }}/"+id,
                type : "get",
                cache : false,
                success : function(response){
                    $("#edit-child-content").html(response)

                    $("#edit-child-content .select2").select2({
                        width: "100%"
                    })
                }
            })
        }

        function _modal(input, type, parent, position){
            $("#modalAddChild").modal('show')
            $("#type-hide").val(type)
            var title = type.toUpperCase()
            if(parent !== null){
                title += " - " +$(input).attr('data-label').toUpperCase()
            }
            $("#title-add").text(title)
            $("#parent-id").val(parent)
            $("#position").val(position)
        }

        $(document).ready(function () {

            $("#sel-prj").select2({
                width: "100%",
                placeholder : "All Project",
                allowClear : true
            })

            $("#modalSettingAsset select.select2").select2({
                width: "100%"
            })
            $("#modalSettingLia select.select2").select2({
                width: "100%"
            })
            $("#modalSettingEq select.select2").select2({
                width: "100%"
            })

            $("select.select2").select2({
                width : "100%"
            })



            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })


            @if (!empty($from))
                $(".tc-value").each(function(){
                    console.log($(this).attr('data-value'))
                    var aktifa = $(this).data('aktifa')
                    var code = $(this).attr('data-value')
                    var json = null
                    var res = function() {
                        var tmp = null;
                        $.ajax({
                            url : "{{ route('bs.detail.search_value') }}",
                            type : "post",
                            dataType : "json",
                            global : false,
                            async : false,
                            data : {
                                _token : "{{ csrf_token() }}",
                                code : code,
                                from : $("#start-date").val(),
                                to : $("#end-date").val(),
                                projects : $("#sel-prj").val(),
                                isAktifa : aktifa
                            },
                            cache : false,
                            success : function(response){
                                tmp = response
                            },
                        });

                        return tmp
                    }();

                    var td = $(this).parent()
                    var span_total = td.find("span.tc-sum")
                    var sum = parseInt(span_total.text().replaceAll(',', ""))
                    sum += res.total
                    span_total.number(sum, 2)
                    var card = $(this).parents('div.card')
                    var foot = card.find('div.card-footer')
                    var label_total = foot.find('.tc-total')
                    var total = parseInt(label_total.text().replaceAll(',', ""))
                    total += res.total
                    label_total.number(total, 2)


                })


                    var asset = $("#total-asset").text().replaceAll(',', "")
                    var liability = parseFloat($("#total-liability").text().replaceAll(',', ""))
                    var equity = parseFloat($("#total-equity").text().replaceAll(',', ""))

                    var pl = parseFloat($("#pl").text().replaceAll(",", ""))

                    console.log("Equity : " + equity)
                    console.log("pl : " + pl)

                    var sumequity = equity + pl

                    $("#total-equity").number(sumequity, 2)

                    $("#sub-total-left").number(asset, 2)
                    $("#total-left").text($("#sub-total-left").text())
                    $("#total-right").number(liability + sumequity, 2)
            @endif



        });

        function loop_data(t, arguments){
            for (const argumentsKey in arguments) {
                var sum = 0
                for (let i = 0; i < arguments[argumentsKey].amount.length; i++) {
                    sum += parseInt(arguments[argumentsKey].amount[i])
                }

                t.row.add([
                    arguments[argumentsKey].code,
                    sum.toFixed(2),
                    ''
                ]).draw(false)
            }
        }
    </script>
@endsection
