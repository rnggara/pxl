@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>General Journal</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add Journal</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center" rowspan="2">#</th>
                    <th class="text-left" rowspan="2">Description</th>
                    <th class="text-center" rowspan="2">Date</th>
                    <th class="text-center" rowspan="2">File</th>
                    <th class="text-center" colspan="2">Posting</th>
                    <th class="text-center" rowspan="2">Approval</th>
                    <th class="text-center" rowspan="2">Journal</th>
                </tr>
                <tr>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Credit</th>
                </tr>
                </thead>
                <tbody>
                    <?php $num = 1; ?>
                    @foreach($coa as $value)
                        <tr>
                            <td align="center">{{$num++}}</td>
                            <td>{{$value['description']}}</td>
                            <td align="center">{{date('d F Y', strtotime($value['date']))}}</td>
                            <td align="center" class="text-center">
                                @if(empty($value['file_hash']))
                                    no file attached
                                @else
                                    <a href="{{route('download', $value['file_hash'])}}" target="_blank" class="fa fa-download"></a>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['debit']))
                                    @foreach($value['debit'] as $key => $item)
                                        <div class="accordion ui-accordion-icons accordion-toggle-arrow" id="accordionExample2">
                                            <div class="card">
                                                <div class="card-header" id="headingOne2">
                                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse{{$num."-".$key}}">
                                                        {{(isset($coa_name[$item['no_coa']])) ? $coa_name[$item['no_coa']] : ""}}
                                                    </div>
                                                </div>
                                                <div id="collapse{{$num."-".$key}}" class="collapse" data-parent="#accordionExample2">
                                                    <div class="card-body">
                                                        Amount : {{number_format($item['debit'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(isset($value['credit']))
                                    @foreach($value['credit'] as $key => $item)
                                        <div class="accordion ui-accordion-icons accordion-toggle-arrow" id="accordionExample2">
                                            <div class="card">
                                                <div class="card-header" id="headingOne2">
                                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse{{$num."-".$item['no_coa']}}">
                                                        {{(isset($coa_name[$item['no_coa']])) ? $coa_name[$item['no_coa']] : ""}}
                                                    </div>
                                                </div>
                                                <div id="collapse{{$num."-".$item['no_coa']}}" class="collapse" data-parent="#accordionExample2">
                                                    <div class="card-body">
                                                        Amount : {{number_format($item['credit'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td align="center">
                                @if($value['approved_at'] == null)
                                    <button type="button" class="btn btn-xs btn-primary" onclick="button_approve('{{$value['md5']}}')">Approve</button>
                                @else
                                    Approved at {{date('d F Y', strtotime($value['approved_at']))}} by {{$value['approved_by']}}
                                @endif
                            </td>
                            <td align="center">
                                <button class="btn btn-icon btn-primary" onclick="button_edit('{{$value['md5']}}')"><i class="fa fa-pencil-alt"></i></button>
                                <button class="btn btn-icon btn-danger" onclick="button_delete('{{$value['md5']}}')"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('gj.add')}}" id="form-add" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="gj_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">File</label>
                            <div class="col-md-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input form-control" name="file_upload" required>
                                    <label for="" class="custom-file-label">
                                        Choose file
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Posting</label>
                            <div class="col-md-9 mx-auto">
                                <table class="table table-responsive-xl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center justify-content-center" style="width: 10%">

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody-add">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control coa-input coa_debit" name="coa_code_debit[]">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control amount_debit number" name="amount_debit[]" placeholder="Amount">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control coa-input coa_credit" name="coa_code_credit[]">
                                                <div class="coa-target"></div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control amount_credit number" name="amount_credit[]" placeholder="Amount">
                                            </div>
                                        </td>
                                        <td align="center" class="justify-content-center">
                                            <div class="form-group mt-7">
                                                <button type="button" class="btn btn-icon btn-primary" id="btn-add-list"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div id="coa-target"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="button" id="btn-add" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('gj.edit')}}" id="form-edit-post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" id="date-edit" name="gj_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" id="desc-edit" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-right">Posting</label>
                            <div class="col-md-9 mx-auto">
                                <table class="table table-responsive-xl">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Debit</th>
                                        <th class="text-center">Credit</th>
                                        <th class="text-center justify-content-center" style="width: 10%">

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody-edit">
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control coa-input-edit coa_debit-edit" id="coa_debit_0" name="coa_code_debit[]">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control amount_debit-edit number" name="amount_debit[]" id="amount_debit_0" placeholder="Amount">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control coa-input-edit coa_credit-edit" id="coa_credit_0" name="coa_code_credit[]">
                                                <div class="coa-target"></div>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control amount_credit-edit number" name="amount_credit[]" id="amount_credit_0" placeholder="Amount">
                                            </div>
                                        </td>
                                        <td align="center" class="justify-content-center">
                                            <div class="form-group mt-7">
                                                <button type="button" class="btn btn-icon btn-primary" id="btn-add-list-edit"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div id="coa-target-edit"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="md5" name="md5">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-submit-edit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Approve",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{route('gj.approve')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'hash' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "Please contact your administrator"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_delete(x){
            Swal.fire({
                title: "Delete",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('gj.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "Please contact your administrator"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_edit(x){
            @if(!empty($coa))
            var jsoncoa = '{{json_encode($coa)}}'.replaceAll('&quot;', '\"')
            var jsonName = '{{json_encode($coa_name)}}'.replaceAll('&quot;', '\"')
            var coa = JSON.parse(jsoncoa)
            var coaName = JSON.parse(jsonName)
            var data = coa[x]
            console.log(data)
            var c = 0

            if (data['debit'].length > data['credit'].length){
                var c = data['debit'].length - 1
            } else {
                var c = data['credit'].length - 1
            }

            console.log(c)
            $("#editItem").modal('show')
            $("#date-edit").val(data['date'])
            $("#desc-edit").val(data['description'])
            $("#coa_debit_0").val(coaName[data['debit'][0]['no_coa']])
            $("#amount_debit_0").val(data['debit'][0]['debit'])
            $("#coa_credit_0").val(coaName[data['credit'][0]['no_coa']])
            $("#amount_credit_0").val(data['credit'][0]['credit'])
            $("#md5").val(data['md5'])
            if (c > 0){
                for (let j = 0; j < c; j++) {
                    var tbody = $("#tbody-edit")
                    var trLast = tbody.find("tr:last")
                    var trNew = trLast.clone()
                    var btn = trNew.find('button')
                    var countBtn = tbody.find('button').toArray()
                    console.log(countBtn)
                    var i = btn.find('i')
                    i.removeClass('fa-plus')
                    i.addClass('fa-times')
                    btn.removeClass('btn-primary')
                    btn.addClass('btn-danger')
                    btn.addClass("btn-delete")
                    btn.attr('onclick', 'btn_remove(this)')
                    var txt = trNew.find('input[type=text]')
                    trNew.find('input').val('')
                    if (data['debit'].hasOwnProperty(j+1)){
                        trNew.find('input.coa_debit-edit').val(coaName[data['debit'][j+1]['no_coa']])
                        trNew.find('input.amount_debit-edit').val(data['debit'][j+1]['debit'])
                    }
                    trNew.find('input.coa_debit-edit').attr('id', 'coa_debit_'+(j+1))
                    trNew.find('input.amount_debit-edit').attr('id', 'amount_debit_'+(j+1))

                    if (data['credit'].hasOwnProperty(j+1)){
                        trNew.find('input.coa_credit-edit').val(coaName[data['credit'][j+1]['no_coa']])
                        trNew.find('input.amount_credit-edit').val(data['credit'][j+1]['credit'])
                    }
                    trNew.find('input.coa_credit-edit').attr('id', 'coa_credit_'+(j+1))
                    trNew.find('input.amount_credit-edit').attr('id', 'amount_credit_'+(j+1))

                    trLast.after(trNew)
                    $(txt).each(function(){
                        $(this).autocomplete({
                            source: "{{route('coa.get')}}",
                            minLength: 1,
                            appendTo: "#coa-target-edit",
                            select: function(event, ui){
                                $(this).val(ui.item.label)
                            }
                        })
                    })
                }
            }

            console.log(c)
            @endif

        }

        function btn_remove(btn){
            console.log('hey')
            console.log(btn)
            var tr = $(btn).closest('tr')
            console.log(tr)
            tr.remove()
        }
        $(document).ready(function(){
            $("#editItem").on('hidden.bs.modal', function () {
                var tr = $("#tbody-edit tr").toArray()
                for (let i = tr.length; i > 0 ; i--) {
                    $(tr[i]).remove()
                }
            })
            console.log()
            $(".coa-input").each(function(){
                $(this).autocomplete({
                    source: "{{route('coa.get')}}",
                    minLength: 1,
                    appendTo: "#coa-target",
                    select: function(event, ui){
                        $(this).val(ui.item.label)
                    }
                })
            })

            $(".number").number(true, 2)

            $(".coa-input-edit").each(function(){
                $(this).autocomplete({
                    source: "{{route('coa.get')}}",
                    minLength: 1,
                    appendTo: "#coa-target-edit",
                    select: function(event, ui){
                        $(this).val(ui.item.label)
                    }
                })
            })

            $("#btn-add").click(function(e){
                var coa_debit = $(".coa_debit").toArray()
                var amount_debit = $(".amount_debit").toArray()
            })

            $("#btn-add-list").click(function(e){
                e.preventDefault()
                var tbody = $("#tbody-add")
                var trLast = tbody.find("tr:last")
                var trNew = trLast.clone()
                var btn = trNew.find('button')
                var countBtn = tbody.find('button').toArray()
                console.log(countBtn)
                var i = btn.find('i')
                i.removeClass('fa-plus')
                i.addClass('fa-times')
                btn.removeClass('btn-primary')
                btn.addClass('btn-danger')
                btn.addClass("btn-delete")
                btn.attr('onclick', 'btn_remove(this)')
                var txt = trNew.find('input[type=text]')
                console.log(txt)
                trNew.find('input').val('')
                trLast.after(trNew)
                $(txt).each(function(){
                    $(this).autocomplete({
                        source: "{{route('coa.get')}}",
                        minLength: 1,
                        appendTo: "#coa-target",
                        select: function(event, ui){
                            $(this).val(ui.item.label)
                        }
                    })
                })
            })

            $("#btn-add-list-edit").click(function(e){
                e.preventDefault()
                var tbody = $("#tbody-edit")
                var trLast = tbody.find("tr:last")
                var trNew = trLast.clone()
                var btn = trNew.find('button')
                var countBtn = tbody.find('button').toArray()
                console.log(countBtn)
                var i = btn.find('i')
                i.removeClass('fa-plus')
                i.addClass('fa-times')
                btn.removeClass('btn-primary')
                btn.addClass('btn-danger')
                btn.addClass("btn-delete")
                btn.attr('onclick', 'btn_remove(this)')
                var txt = trNew.find('input[type=text]')
                console.log(txt)
                trNew.find('input').val('')
                trLast.after(trNew)
                $(txt).each(function(){
                    $(this).autocomplete({
                        source: "{{route('coa.get')}}",
                        minLength: 1,
                        appendTo: "#coa-target-edit",
                        select: function(event, ui){
                            $(this).val(ui.item.label)
                        }
                    })
                })
            })

            $("#btn-add").click(function(e){
                e.preventDefault()
                var debit = $(".coa_debit").toArray()
                var de_amount = $(".amount_debit").toArray()
                var credit = $(".coa_credit").toArray()
                var cre_amount = $(".amount_credit").toArray()
                var dpost = 0
                var sumdebit = 0
                var sumcredit = 0
                var cpost = 0

                for (let i = 0; i < de_amount.length; i++) {
                    if (de_amount[i].value == "" || de_amount[i].value == null){
                        sumdebit += 0
                    } else {
                        sumdebit += parseInt(de_amount[i].value)
                    }

                }

                for (let i = 0; i < cre_amount.length; i++) {
                    if (cre_amount[i].value == "" || cre_amount[i].value == null){
                        sumcredit += 0
                    } else {
                        sumcredit += parseInt(cre_amount[i].value)
                    }
                }

                console.log(sumdebit + " = " + sumcredit)
                if (sumdebit != sumcredit){
                    Swal.fire('Not Balance', 'need balance between debit and credit', 'warning')
                } else {
                    if (debit[0].value == "" && de_amount[0].value == "" || debit[0].value == null && de_amount[0].value == null){
                        Swal.fire('There is blank input', 'Please fill at least 1 data from debit', 'warning')
                    } else {
                        if (debit.length == 1){
                            if (debit[0].value != "" && de_amount[0].value == "" || debit[0].value != null && de_amount[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else if (de_amount[0].value != "" && debit[0].value == "" || de_amount[0].value != null && debit[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else {
                                dpost = 1;
                            }
                        } else {
                            var rpost = 0;
                            for (let i = 1; i < debit.length; i++) {
                                if (debit[i].value !== "" && de_amount[i].value === "" || debit[i].value != null && de_amount[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if (de_amount[i].value !== "" && debit[i].value === "" || de_amount[i].value != null && debit[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if(debit[i].value !== "" && de_amount[i].value !== "" || debit[i].value != null && de_amount[i].value != null){
                                    rpost++;
                                }
                            }
                            if ((rpost + 1) == debit.length){
                                dpost = 1
                            }
                        }
                    }


                    if (credit[0].value == "" && cre_amount[0].value == "" || credit[0].value == null && cre_amount[0].value == null){
                        Swal.fire('There is blank input', 'Please fill at least 1 data from credit', 'warning')
                    } else {
                        if (credit.length == 1){
                            if (credit[0].value != "" && cre_amount[0].value == "" || credit[0].value != null && cre_amount[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else if (cre_amount[0].value != "" && credit[0].value == "" || cre_amount[0].value != null && credit[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else {
                                cpost = 1
                            }
                        } else {
                            var rpost = 0;
                            for (let i = 1; i < credit.length; i++) {
                                if (credit[i].value !== "" && cre_amount[i].value === "" || credit[i].value != null && cre_amount[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if (cre_amount[i].value !== "" && credit[i].value === "" || cre_amount[i].value != null && credit[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if(credit[i].value !== "" && cre_amount[i].value !== "" || credit[i].value != null && cre_amount[i].value != null){
                                    rpost++;
                                }
                            }
                            if ((rpost + 1) == credit.length){
                                cpost = 1
                            }
                        }
                    }
                }

                if ((dpost + cpost) == 2){
                    $("#form-add").submit()
                }
            })

            $("#btn-submit-edit").click(function(e){
                e.preventDefault()
                var debit = $(".coa_debit-edit").toArray()
                var de_amount = $(".amount_debit-edit").toArray()
                var credit = $(".coa_credit-edit").toArray()
                var cre_amount = $(".amount_credit-edit").toArray()
                var dpost = 0
                var sumdebit = 0
                var sumcredit = 0
                var cpost = 0

                for (let i = 0; i < de_amount.length; i++) {
                    if (de_amount[i].value == "" || de_amount[i].value == null){
                        sumdebit += 0
                    } else {
                        sumdebit += parseInt(de_amount[i].value)
                    }

                }

                for (let i = 0; i < cre_amount.length; i++) {
                    if (cre_amount[i].value == "" || cre_amount[i].value == null){
                        sumcredit += 0
                    } else {
                        sumcredit += parseInt(cre_amount[i].value)
                    }
                }

                console.log(sumdebit + " = " + sumcredit)
                if (sumdebit != sumcredit){
                    Swal.fire('Not Balance', 'need balance between debit and credit', 'warning')
                } else {
                    if (debit[0].value == "" && de_amount[0].value == "" || debit[0].value == null && de_amount[0].value == null){
                        Swal.fire('There is blank input', 'Please fill at least 1 data from debit', 'warning')
                    } else {
                        if (debit.length == 1){
                            if (debit[0].value != "" && de_amount[0].value == "" || debit[0].value != null && de_amount[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else if (de_amount[0].value != "" && debit[0].value == "" || de_amount[0].value != null && debit[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else {
                                dpost = 1;
                            }
                        } else {
                            var rpost = 0;
                            for (let i = 1; i < debit.length; i++) {
                                if (debit[i].value !== "" && de_amount[i].value === "" || debit[i].value != null && de_amount[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if (de_amount[i].value !== "" && debit[i].value === "" || de_amount[i].value != null && debit[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if(debit[i].value !== "" && de_amount[i].value !== "" || debit[i].value != null && de_amount[i].value != null){
                                    rpost++;
                                }
                            }
                            if ((rpost + 1) == debit.length){
                                dpost = 1
                            }
                        }
                    }


                    if (credit[0].value == "" && cre_amount[0].value == "" || credit[0].value == null && cre_amount[0].value == null){
                        Swal.fire('There is blank input', 'Please fill at least 1 data from credit', 'warning')
                    } else {
                        if (credit.length == 1){
                            if (credit[0].value != "" && cre_amount[0].value == "" || credit[0].value != null && cre_amount[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else if (cre_amount[0].value != "" && credit[0].value == "" || cre_amount[0].value != null && credit[0].value == null){
                                Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                            } else {
                                cpost = 1
                            }
                        } else {
                            var rpost = 0;
                            for (let i = 1; i < credit.length; i++) {
                                if (credit[i].value !== "" && cre_amount[i].value === "" || credit[i].value != null && cre_amount[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if (cre_amount[i].value !== "" && credit[i].value === "" || cre_amount[i].value != null && credit[i].value == null){
                                    Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                                } else if(credit[i].value !== "" && cre_amount[i].value !== "" || credit[i].value != null && cre_amount[i].value != null){
                                    rpost++;
                                }
                            }
                            if ((rpost + 1) == credit.length){
                                cpost = 1
                            }
                        }
                    }
                }

                if ((dpost + cpost) == 2){
                    $("#form-edit-post").submit()
                }
            })

            $(".btn-delete").click(function(){
                console.log("delete")

            })

            $("#btn-submit").hide()
            $("#btn-deposit").click(function(){
                Swal.fire({
                    title: "Add Deposit",
                    text: "Are you sure you want to submit this data?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Submit",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#btn-submit").click()
                    }
                })
            })

            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
            $("select.select2").select2({
                width: "100%"
            })
        })

    </script>
@endsection
