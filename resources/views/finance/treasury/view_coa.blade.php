@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>TC Assignment</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('treasury.coa', $tre_his->id_treasure)}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
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
                                <label class="text-success">
                                    {{date('d F Y', strtotime($tre_his->date_input))}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Description</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label class="text-success">
                                    {{strip_tags($tre_his->description)}}
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Amount</label></td>
                            <td>&nbsp;<label for="">:</label>&nbsp;</td>
                            <td>
                                <label class="{{($tre_his->IDR < 0) ? "text-danger" : "text-success"}}">
                                    {{number_format($tre_his->IDR, 2)}}
                                </label>
                            </td>
                        </tr>
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
                            <form action="{{(count($coa_his) == 0) ? route('treasury.setcoa') : route('treasury.editcoa')}}" method="post" id="form-submit" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-right">
                                            <button type="button" id="btn-add-list" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> New Row</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <label class="col-md-3 col-form-label text-right">File</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input form-control" name="file_upload" required>
                                            <label for="" class="custom-file-label">
                                                Choose file
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <table class="table display table-borderless table-hover table-responsive-xl">
                                <thead>
                                <tr>
                                    <th class="text-center bg-success text-white">Credit</th>
                                    <th class="text-center bg-danger text-white">Debit</th>
                                </tr>
                                </thead>
                                <tbody id="tbody_clone">
                                @if(count($coa_his) == 0)
                                @php
                                    $debit_coa = [];
                                    $credit_coa = [];
                                    if(!empty($coa_adm)){
                                        if($tre_his->IDR > 0){
                                            $debit_coa = $coa_adm;
                                        } else {
                                            $credit_coa = $coa_adm;
                                        }
                                    }

                                    $debit_code = "";
                                    $debit_coa_id = "";
                                    if(!empty($debit_coa)){
                                        $debit_code = "[$debit_coa->code] $debit_coa->name";
                                        $debit_coa_id = $debit_coa->id;
                                    }

                                    $credit_code = "";
                                    $credit_coa_id = "";
                                    if(!empty($credit_coa)){
                                        $credit_code = "[$credit_coa->code] $credit_coa->name";
                                        $credit_coa_id = $credit_coa->id;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <input type="text" name="credit[]" class="form-control credit" value="{{(isset($coa[$treasury->bank_code]) && $tre_his->IDR > 0) ? $coa[$treasury->bank_code] : ""}}">
                                        <input type="number" name="cre_amount[]" min="0" class="form-control cre_amount" value="{{(isset($coa[$treasury->bank_code]) && $tre_his->IDR > 0) ? abs($tre_his->IDR) : ""}}">
                                    </td>
                                    <td>
                                        <input type="text" name="debit[]" class="form-control debit" value="{{(isset($coa[$treasury->bank_code]) && $tre_his->IDR < 0) ? $coa[$treasury->bank_code] : ""}}">
                                        <input type="number" name="de_amount[]" min="0" class="form-control de_amount" value="{{(isset($coa[$treasury->bank_code]) && $tre_his->IDR < 0) ? abs($tre_his->IDR) : ""}}">
                                    </td>
                                </tr>
                                @else
                                    <?php
                                        $n = (count($data_coa['debit']) > count($data_coa['credit'])) ? count($data_coa['debit']) : count($data_coa['credit'])
                                    ?>
                                    @for($i = 0; $i < $n ; $i++)
                                        @php
                                            $debit_rl = "";
                                            $credit_rl = "";

                                            if(isset($data_coa['debit'][$i]) && $data_coa['debit'][$i]['lock'] == 1){
                                                $debit_rl = "readonly";
                                            }

                                            if(isset($data_coa['credit'][$i]) && $data_coa['credit'][$i]['lock'] == 1){
                                                $credit_rl = "readonly";
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="text" {{ $credit_rl }} name="credit[]" class="form-control credit" value="{{(isset($data_coa['credit'][$i]['code'])) ? $coa[$data_coa['credit'][$i]['code']] : ""}}">
                                                <input type="number" {{ $credit_rl }} name="cre_amount[]" min="0" class="form-control cre_amount" value="{{(isset($data_coa['credit'][$i]['amount'])) ? $data_coa['credit'][$i]['amount'] : ""}}">
                                                <input type="hidden" name="id_coa_credit[]" value="{{(isset($data_coa['credit'][$i]['amount'])) ? $data_coa['credit'][$i]['id'] : ""}}">
                                            </td>
                                            <td>
                                                <input type="text" {{ $debit_rl }} name="debit[]" class="form-control debit" value="{{(isset($data_coa['debit'][$i]['code'])) ? $coa[$data_coa['debit'][$i]['code']] : ""}}">
                                                <input type="number" {{ $debit_rl }} name="de_amount[]" min="0" class="form-control de_amount" value="{{(isset($data_coa['debit'][$i]['amount'])) ? $data_coa['debit'][$i]['amount'] : ""}}">
                                                <input type="hidden" name="id_coa_debit[]" value="{{(isset($data_coa['debit'][$i]['amount'])) ? $data_coa['debit'][$i]['id'] : ""}}">
                                            </td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-right">
                                        @if(count($coa_his) > 0)
                                            <input type="hidden" name="id_del" id="id_del">
                                        @endif
                                        <input type="hidden" name="id_his" value="{{$tre_his->id}}">
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

                // console.log(sumdebit + " = " + sumcredit)
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

                if ((dpost + cpost) == 2){
                    $("#form-submit").submit()
                }
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
                console.log(debit)
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
