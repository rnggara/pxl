@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>{{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }} Assignment - {{ strtoupper(str_replace("-", " ", $type)) }}</h3><br>
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="card card-custom m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <table>
                        <tr>
                            <td><label for="">Date</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td width="70%">
                                <label class="text-success font-weight-bold">
                                    {{ date("d F Y") }}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Description</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label class="text-success font-weight-bold">
                                    {{ $row['description'] }}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Amount</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label for="" class="text-success font-weight-bold">
                                    {{ number_format($row['amount'], 2) }}
                                </label>
                            </td>
                        </tr>
                        @if ($type == "invoice-out")
                            <tr>
                                <td><label for="">PPN</label></td>
                                <td>&nbsp;<label for="">:</label>&nbsp;</td>
                                <td>
                                    <label for="" class="text-success font-weight-bold">
                                        {{ number_format($row['amount'], 2) }}
                                    </label>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="card card-custom m-5">
                <div class="separator separator-solid separator-white opacity-20"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <form action="{{ route('coa.assign.post', ["type" => $type, "id" => $row['id']]) }}" method="post" id="form-submit" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-right">
                                            <button type="button" id="btn-add-list" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> New Row</button>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="mt-3 row">
                                    <label class="col-md-3 col-form-label text-right">File</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input form-control" name="file_upload" required>
                                            <label for="" class="custom-file-label">
                                                Choose file
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}
                            <table class="table display table-borderless table-hover table-responsive-xl">
                                <thead>
                                <tr>
                                    <th class="text-center bg-success text-white">Debit</th>
                                    <th class="text-center bg-danger text-white">Credit</th>
                                </tr>
                                </thead>
                                <tbody id="tbody_clone">
                                    @if (count($coa_his) > 0)
                                        @foreach ($coa_his as $item)
                                        <tr>
                                            <td>
                                                <input type="text" name="debit[]" class="form-control debit" value="{{ ($item->debit > 0) ? "[$item->no_coa] ".$coa_name[$item->no_coa] : "" }}">
                                                <input type="number" name="de_amount[]" min="0" class="form-control de_amount" value="{{ ($item->debit > 0) ? $item->debit : "" }}">
                                            </td>
                                            <td>
                                                <input type="text" name="credit[]" class="form-control credit" value="{{ ($item->credit > 0) ? "[$item->no_coa] ".$coa_name[$item->no_coa] : "" }}">
                                                <input type="number" name="cre_amount[]" min="0" class="form-control cre_amount" value="{{ ($item->credit > 0) ? $item->credit : "" }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td>
                                            <input type="text" name="debit[]" class="form-control debit" value="">
                                            <input type="number" name="de_amount[]" min="0" class="form-control de_amount" value="">
                                        </td>
                                        <td>
                                            <input type="text" name="credit[]" class="form-control credit" value="">
                                            <input type="number" name="cre_amount[]" min="0" class="form-control cre_amount" value="{{ $row['amount'] }}">
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <input type="hidden" name="_desc" value="{{ $row['description'] }}">
                                        <input type="hidden" name="_comp_id" value="{{ $row['company_id'] }}">
                                        <input type="hidden" name="currency" value="{{ $row['currency'] }}">
                                        <button type="button" id="btn-remove-list" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i> Delete Row</button>
                                        <button type="button" id="btn-save" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet">
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        function button_approve(x){
            Swal.fire({
                title: "Approve",
                text: "Are you sure you want to approve?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.approve')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        function button_reject(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to reject?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('treasury.reject')}}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            '_token' : '{{csrf_token()}}',
                            'val' : x
                        },
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }
        $(document).ready(function(){

            @if(count($coa_his) > 0)
                var deb = $(".debit").toArray()
                if (deb.length > 1){
                    $("#btn-remove-list").show()
                } else {
                    $("#btn-remove-list").hide()
                }
            @else
                $("#btn-remove-list").hide()
            @endif

            $("#btn-save").click(function(){
                var debit = $(".debit").toArray()
                var de_amount = $(".de_amount").toArray()
                var credit = $(".credit").toArray()
                var cre_amount = $(".cre_amount").toArray()
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

                $("#form-submit").submit()
                // if (sumdebit != sumcredit){
                //     Swal.fire('Not Balance', 'need balance between debit and credit', 'warning')
                // } else {
                //     if (debit[0].value == "" && de_amount[0].value == "" || debit[0].value == null && de_amount[0].value == null){
                //         Swal.fire('There is blank input', 'Please fill at least 1 data from debit', 'warning')
                //     } else {
                //         if (debit.length == 1){
                //             if (debit[0].value != "" && de_amount[0].value == "" || debit[0].value != null && de_amount[0].value == null){
                //                 Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //             } else if (de_amount[0].value != "" && debit[0].value == "" || de_amount[0].value != null && debit[0].value == null){
                //                 Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //             } else {
                //                 dpost = 1;
                //             }
                //         } else {
                //             var rpost = 0;
                //             for (let i = 1; i < debit.length; i++) {
                //                 if (debit[i].value !== "" && de_amount[i].value === "" || debit[i].value != null && de_amount[i].value == null){
                //                     Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //                 } else if (de_amount[i].value !== "" && debit[i].value === "" || de_amount[i].value != null && debit[i].value == null){
                //                     Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //                 } else if(debit[i].value !== "" && de_amount[i].value !== "" || debit[i].value != null && de_amount[i].value != null){
                //                     rpost++;
                //                 }
                //             }
                //             if ((rpost + 1) == debit.length){
                //                 dpost = 1
                //             }
                //         }
                //     }


                //     if (credit[0].value == "" && cre_amount[0].value == "" || credit[0].value == null && cre_amount[0].value == null){
                //         Swal.fire('There is blank input', 'Please fill at least 1 data from credit', 'warning')
                //     } else {
                //         if (credit.length == 1){
                //             if (credit[0].value != "" && cre_amount[0].value == "" || credit[0].value != null && cre_amount[0].value == null){
                //                 Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //             } else if (cre_amount[0].value != "" && credit[0].value == "" || cre_amount[0].value != null && credit[0].value == null){
                //                 Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //             } else {
                //                 cpost = 1
                //             }
                //         } else {
                //             var rpost = 0;
                //             for (let i = 1; i < credit.length; i++) {
                //                 if (credit[i].value !== "" && cre_amount[i].value === "" || credit[i].value != null && cre_amount[i].value == null){
                //                     Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //                 } else if (cre_amount[i].value !== "" && credit[i].value === "" || cre_amount[i].value != null && credit[i].value == null){
                //                     Swal.fire('There is blank input', 'Please fill the blank', 'warning')
                //                 } else if(credit[i].value !== "" && cre_amount[i].value !== "" || credit[i].value != null && cre_amount[i].value != null){
                //                     rpost++;
                //                 }
                //             }
                //             if ((rpost + 1) == credit.length){
                //                 cpost = 1
                //             }
                //         }
                //     }
                // }

                // if ((dpost + cpost) == 2){
                //     $("#form-submit").submit()
                // }
            })

            $("#btn-add-list").click(function(){
                var tbody = $("#tbody_clone")
                var tr = tbody.find("tr:last")
                var trNew = tr.clone()
                trNew.find("input").val('')
                tr.after(trNew)
                $("tr input[type=text]").each(function(){
                    $(this).autocomplete({
                        source: "{{route('coa.get')}}",
                        minLength: 1,
                        select: function(event, ui){
                            $(this).val(ui.item.label)
                        }
                    })
                })
                var debit = $(".debit").toArray()
                console.log(debit)
                if (debit.length > 1){
                    $("#btn-remove-list").show()
                } else {
                    $("#btn-remove-list").hide()
                }
            })

            var idcoa = []
            $("#btn-remove-list").click(function(){
                var tbody = $("#tbody_clone")
                var tr = tbody.find("tr:last")
                @if(count($coa_his) > 0)
                    var id_coa = tr.find("input[type=hidden]").val()
                    idcoa.push(id_coa)
                    $("#id_del").val(JSON.stringify(idcoa))
                    console.log()
                @endif
                tr.remove()
                var debit = $(".debit").toArray()
                if (debit.length == 1){
                    $("#btn-remove-list").hide()
                }
            })

            $("tr input[type=text]").each(function(i){
                $(this).autocomplete({
                    source: "{{route('coa.get')}}",
                    minLength: 1,
                    select: function(event, ui){
                        $(this).val(ui.item.label)
                    }
                })
            })
            $("table.display").DataTable({
                pageLength: 100,
                ordering: false,
                searching: false,
                paging: false,
                lengthChange: false,
                bInfo: false,
            })
        })
    </script>
@endsection
