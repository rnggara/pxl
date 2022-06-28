@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Cash Flow</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <h3 class="card-title">PINBUK</h3>
                    <div class="card-toolbar">
                        <button type="button" data-toggle="modal" data-target="#modalPinbuk" class="btn btn-icon btn-sm btn-outline-info">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($cash as $item)
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <h3 class="card-title">{{ strtoupper(str_replace("_", " ", $item)) }}</h3>
                        <div class="card-toolbar">
                            <button type="button" data-toggle="modal" onclick="_modal('{{ $item }}')" data-target="#modalSetting" class="btn btn-icon btn-sm btn-primary">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Description</td>
                                        <td align="right">Action</td>
                                    </tr>
                                    @if (isset($setting[$item]))
                                        @foreach ($setting[$item] as $st)
                                            @empty($st->parent)
                                            <tr class="bg-light-success">
                                                <td class="font-weight-bold">
                                                    {{ ucwords($st->label) }}
                                                </td>
                                                <td align="right">
                                                    <button type="button" data-toggle="modal" onclick="_modalChild('{{ $item.'-'.$st->label }}', {{ $st->id }})" data-target="#modalSettingChild" class="btn btn-outline-primary btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                                    <button type="button" onclick="_edit({{ $st->id }})" class="btn btn-outline-info btn-icon btn-xs"><i class="fa fa-edit"></i></button>
                                                    <button type="button" onclick="_delete({{ $st->id }})" class="btn btn-outline-danger btn-icon btn-xs"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            @if (!empty($st->child))
                                                @foreach ($st->child as $child)
                                                <tr class="border">
                                                    <td style="width: 60%">
                                                        <i class="fa fa-arrow-right"></i>
                                                        <a href="#" id="view-{{ $child->id }}">
                                                            {{ $child->label }}
                                                        </a>
                                                    </td>
                                                    <td align="right" style="width: 20%">
                                                        <button type="button" onclick="_edit({{ $child->id }})" class="btn btn-outline-info btn-circle btn-icon btn-xs"><i class="fa fa-edit"></i></button>
                                                        <button type="button" onclick="_delete({{ $child->id }})" class="btn btn-outline-danger btn-circle btn-icon btn-xs"><i class="fa fa-times-circle"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            @endempty
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalPinbuk" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span>PINBUK</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('finance.cf.settings')}}">
                    @csrf
                    <div class="modal-body">
                        <fieldset class="border p-5 fl" id="dup-fieldset">
                            <legend class="w-auto">Source 1</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                        <select name="tc[0][]" class="form-control select2 tc" multiple data-allow-clear="true" data-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}" required>
                                            @foreach($coa as $value)
                                                <option value="{{$value->id}}" {{ (!empty($pinbuk) && in_array($value->id, json_decode($pinbuk->tc, true)[0])) ? "SELECTED" : "" }}>{{"[".$value->code."] ".$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        @if (!empty($pinbuk))
                            <input type="hidden" name="id_st" value="{{ $pinbuk->id }}">
                        @endif
                        <input type="hidden" name="label" value="pinbuk">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSettingChild" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="title-add-child"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('finance.cf.settings')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right font-weight-bold">Label</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" required name="label">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right font-weight-bold"></label>
                            <div class="col-md-10 text-right">
                                <button type="button" onclick="_add_row(this)" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <fieldset class="border p-5 fl" id="dup-fieldset">
                            <legend class="w-auto">Source 1</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Project</label>
                                        <select name="prj[0]" class="form-control select2 prj" data-placeholder="All Project">
                                            <option value=""></option>
                                            @foreach ($projects as $item)
                                                <option value="{{ $item->id }}">[{{ sprintf("%02d", $item->id) }}] - {{ $item->prj_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                        <select name="tc[0][]" class="form-control select2 tc" multiple data-allow-clear="true" data-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}" required>
                                            @foreach($coa as $value)
                                                <option value="{{$value->id}}">{{"[".$value->code."] ".$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right div-rm">

                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type_parent" id="type-hide-child">
                        <input type="hidden" name="is_child" value="1">
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
            <div class="modal-content">


            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSetting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="title-add"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('finance.cf.settings')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Label</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" required name="label">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type_parent" id="type-hide">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        function _modal(type){
            $("#title-add").text(type.toUpperCase().replaceAll("_", " "))
            $("#type-hide").val(type)
        }

        function _modalChild(type, id){
            $("#title-add-child").text(type.toUpperCase().replaceAll("_", " "))
            $("#type-hide-child").val(id)
        }

        function _edit(id){
            $("#modalEditChild").modal('show')
            $.ajax({
                url : "{{ route('finance.cf.edit') }}/"+id,
                type : "get",
                success : function(response){
                    $("#modalEditChild .modal-content").html(response)
                    $("#modalEditChild .select2").select2({
                        width : "100%"
                    })
                }
            })
        }

        function _pdf(){
            Swal.fire({
                title: "Generating File",
                text: "proccess",
                onOpen: function() {
                    Swal.showLoading()
                },
                // allowOutsideClick: false
            })
            $.ajax({
                url : "{{ route('finance.cf.data') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    _month : $("#mnth").val(),
                    _year : $("#year").val(),
                    _pdf : 1,
                },
                success : function(response){
                    swal.close()
                    if (response == 1) {
                        Swal.fire('Pdf', 'File has been created', 'success')
                    } else {
                        Swal.fire('Pdf', 'Failed to create file. Please contact your system administrator', 'error')
                    }
                }
            })
        }

        function _delete(id){
            $.ajax({
                url : "{{ route('finance.cf.delete') }}/"+id,
                type : "get",
                dataType : "json",
                beforeSend : function(){
                        Swal.fire({
                            title: "Deleting Data",
                            text: "Please wait",
                            // allowOutsideClick : false,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        })
                    },
                success : function(response){
                    swal.close()
                    if(response == 1){
                        location.reload()
                    } else {
                        Swal.fire('Error', "Can't delete data, this data has child! Please delete the child first!", 'error')
                    }
                }
            })
        }

        function _calculate_sub(){
            var sub_idr = $(".sub-IDR")

            sub_idr.each(function(){
                var id = $(this).data('id')
                console.log(id)
                var _child = $(".sub-IDR-"+id)
                console.log(_child)
                var total = 0
                _child.each(function(){
                    total += parseFloat($(this).text().replaceAll(",", ""))
                })

                $(this).number(total, 2)
            })
        }

        function _add_row(btn){
            var _body = $(btn).parents(".modal-body")

            _body.find("select.select2").select2('destroy')

            var fl = _body.find('fieldset').toArray()
            console.log(fl)

            var _clone = $(fl[0]).clone()

            var id_clone = _clone.attr('id')

            var _sel = _clone.find("select.select2")

            var _prj = _clone.find("select.prj")
            var _tc = _clone.find("select.tc")
            var _legend = _clone.find("legend")
            var fl_num = $(_body).find(".fl").length

            _clone.attr('id', id_clone + fl_num)

            var num = (parseInt(fl_num) + 1)
            _legend.text("Source " + num)
            _prj.attr('name', 'prj['+fl_num+']')
            _tc.attr('name', 'tc['+fl_num+'][]')

            var div_rm = _clone.find('.div-rm')
            var btn_remove = '<button type="button" onclick="_remove_row(this)" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-times"></i></button>';
            div_rm.html(btn_remove)

            _body.append(_clone)

            _body.find("select.select2").select2({
                width : "100%",
                allowClear : true
            })
        }

        function _remove_row(btn){
            var fl = $(btn).parents('fieldset')
            fl.remove()
        }

        $(document).ready(function(){
            $("#btn-view").hide()

            $("#form-source").hide()
            $(".number").number(true, 2)

            $("select.select2").select2({
                width : "100%"
            })

            $("#source").change(function(){
                $("#paper").select2({
                    ajax : {
                        url : "{{ route('finance.cf.find_source') }}?source=" + $("#source").val(),
                        dataType : "json",
                    }
                })
            })

            $("#sel-prj").select2({
                width: "100%",
                placeholder: "All Project",
                allowClear: true
            })
            $("#btn-search").click(function(){
                $(".number").number(0, 2)
                $.ajax({
                    url : "{{ route('finance.cf.data') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        _month : $("#mnth").val(),
                        _year : $("#year").val()
                    },
                    beforeSend : function(){
                        Swal.fire({
                            title: "Processing Data!",
                            text: "Please wait to receive data!",
                            // allowOutsideClick : false,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        })
                    },
                    success : function(response){
                        var data = response.data
                        $("#btn-view").show()
                        $("#btn-view").attr('href', '{{ route('finance.cf.detail') }}?t='+$("#year").val()+"-"+$("#mnth").val())
                        for (const key in data) {
                            var _total = 0;
                            var dataCurr = data[key]
                            for (const curr in dataCurr) {
                                var _total_curr = 0
                                var data_bank = dataCurr[curr]
                                for (const i in data_bank) {
                                    $("."+key+"-balance-"+curr+"-"+i).number(data_bank[i], 2)

                                    $("#view-" + i).attr('href', '{{ route('finance.cf.view') }}/'+i+'?period='+response.period)
                                    $("#view-" + i).attr('target', '_blank')

                                    // console.log( '{{ route('finance.cf.view') }}/'+i+'?period='+response.period)
                                    // console.log(".view-" + i)
                                    _total_curr += parseFloat(data_bank[i])
                                }
                                $("."+key+"-balance-"+curr+"-total").number(_total_curr, 2)

                            }
                        }

                        _calculate_sub()

                        var beginInIDR = parseFloat($(".begin-balance-IDR-total").text().replaceAll(",", ""))
                        var _cash_inIDR = parseFloat($(".cash_in-balance-IDR-total").text().replaceAll(",", ""))
                        var _cash_outIDR = parseFloat($(".cash_out-balance-IDR-total").text().replaceAll(",", ""))

                        surplus_defIDR = beginInIDR + _cash_inIDR - _cash_outIDR
                        var tr = $("#surplus-defisit-IDR").parent()
                        if(surplus_defIDR < 0){
                            tr.addClass('text-danger')
                        } else if(surplus_defIDR > 0) {
                            tr.addClass('text-success')
                        } else {
                            tr.removeClass("text-danger text-success")
                        }
                        $("#surplus-defisit-IDR").number(surplus_defIDR, 2)

                        var beginInUSD = parseFloat($(".begin-balance-USD-total").text().replaceAll(",", ""))
                        var _cash_inUSD = parseFloat($(".cash_in-balance-USD-total").text().replaceAll(",", ""))
                        var _cash_outUSD = parseFloat($(".cash_out-balance-USD-total").text().replaceAll(",", ""))

                        surplus_defUSD = beginInUSD + _cash_inUSD - _cash_outUSD
                        var tr = $("#surplus-defisit-USD").parent()
                        if(surplus_defUSD < 0){
                            tr.addClass('text-danger')
                        } else if(surplus_defUSD > 0) {
                            tr.addClass('text-success')
                        } else {
                            tr.removeClass("text-danger text-success")
                        }
                        $("#surplus-defisit-USD").number(surplus_defUSD, 2)
                        swal.close()
                    }
                })
            })
        })
    </script>
@endsection
