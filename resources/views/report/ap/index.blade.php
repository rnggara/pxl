@extends('layouts.template')

@section('css')
    <style>
        @media print {
            @page {
                size: letter landscape;
                margin: 0;
            }

            body * {
                visibility: hidden;
                background-color: white;
            }

            #section-to-print, #section-to-print * {
                visibility: visible;
                font-size: 95%;
            }

            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
                margin-top: -150px;
                padding-top: 50px;
            }

            #table-show_filter {
                display: none;
            }

            .print-hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">
                Account Payable Report
                <button type="button" onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
            </h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-6 mx-auto">
                    <div class="row">
                        <div class="col-4 mx-auto">
                            <select name="filter" class="form-control select2" id="filter">
                                <option value="all">All</option>
                                <option value="<3">Under 3 months</option>
                                <option value="<6">Between 3 and 6 months</option>
                                <option value=">6">More than 6 months</option>
                            </select>
                        </div>
                        <div class="col-4 mx-auto">
                            <input type="date" class="form-control" name="date_to" id="date_to" value="{{ date("Y-m-d") }}">
                        </div>
                        <div class="col-4 mx-auto">
                            <button type="button" id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="legend">
                <div class="col-12">
                    <table>
                        <tr>
                            <td>Legend :</td>
                            <td>
                                <label class="label label-inline label-success">Under 3 month</label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <label class="label label-inline label-warning">Between 3 and 6 month</label>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <label class="label label-inline label-danger">More than 6 month</label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row" id="section-to-print">
                <div class="col-12" id="table-wrap">
                    <h3><span class="font-weight-bold">Account Payable {{ Session::get('company_name_parent') }}</span> : {{ date("d/m/Y") }}</h3>
                    <table class="table table-bordered display table-responsive-lg table-responsive-sm" id="table-show">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">TYPE</th>
                                <th class="text-center">VENDOR</th>
                                <th class="text-center">NO PO/WO</th>
                                <th class="text-center">DATE INVOICING</th>
                                <th class="text-center">AMOUNT (RP)</th>
                                <th class="text-center">BALANCE</th>
                                <th class="text-center">DUE DATE</th>
                                <th class="text-center">REMARKS</th>
                                <th class="text-center print-hide">ACTION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
    <script>

function _due_date(e){
            var div = $(e).parent()
            $.ajax({
                url : "{{ route('report.ap.update') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : $(e).data('id'),
                    date : $(e).val(),
                    type : "due_date"
                },
                beforeSend : function(){
                    KTApp.block(div, {})
                },
                success : function(response){
                    KTApp.unblock(div);
                    if(!response.success){
                        Swal.fire("Error", response.message, "error")
                    }
                }
            })
        }

        function _remarks(e){
            var div = $(e).parent()
            $.ajax({
                url : "{{ route('report.ap.update') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : $(e).data('id'),
                    remarks : $(e).val(),
                    type : "remarks"
                },
                beforeSend : function(){
                    KTApp.block(div, {})
                },
                success : function(response){
                    KTApp.unblock(div);
                    if(!response.success){
                        Swal.fire("Error", response.message, "error")
                    }
                }
            })
        }

        function _close(e, _type){
            Swal.fire({
                title: "Are you sure?",
                text: "Close AP",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Close"
            }).then(function(result) {
                if (result.value) {
                    console.log($(e).data('id'), _type)
                    $.ajax({
                        url : "{{ route('report.ap.update') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : $(e).data('id'),
                            paper_type : _type,
                            type : "close"
                        },
                        success : function(response){
                            // KTApp.unblock(div);
                            $("#btn-search").click()
                            if(!response.success){
                                Swal.fire("Error", response.message, "error")
                            }
                        }
                    })
                }
            });
        }

        function _pay_date(e){
            var div = $(e).parent()
            Swal.fire({
                title: "Are you sure?",
                text: "Update Invoice Date",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Update"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url : "{{ route('report.ap.update') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : $(e).data('id'),
                            pay_date : $(e).val(),
                            type : "pay_date"
                        },
                        beforeSend : function(){
                            KTApp.block(div, {})
                        },
                        success : function(response){
                            KTApp.unblock(div);
                            if(!response.success){
                                Swal.fire("Error", response.message, "error")
                            }
                        }
                    })
                } else {
                    $(e).val($(e).data('old-value'))
                }
            });
        }

        $(document).ready(function(){

            $("#legend").hide()

            $("select.select2").select2({
                width : "100%"
            })

            var date_from = $("input[name=date_from]")
            var date_to = $("input[name=date_to]")

            var tb = $("#table-show").DataTable({
                paging : false,
                ordering : false,
                bInfo : false,
                rowsGroup : [
                    1,
                    2
                ],
                columnDefs : [
                    {"targets" : [5, 6], "className" : "text-right"},
                    {"targets" : [0, 1, 4, 7], "className" : "text-center"},
                    {"targets" : [9], "className" : "print-hide"}
                ],
                rowGroup: {
                    startRender: null,
                    endRender: function ( rows, group ) {
                        var sum_amount = rows
                            .data()
                            .pluck(5)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        var sum_after_tax = rows
                            .data()
                            .pluck(6)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        $(".number").number(true, 2)

                        return $('<tr/>')
                            .append( '<td colspan="5">Total Payment for '+group+'</td>' )
                            .append( '<td align="right"><span class="number">'+sum_amount+'</span></td>' )
                            .append( '<td align="right"><span class="number">'+sum_after_tax+'</span></td>' )
                            .append( "<td colspan='3'></td>" )
                    },
                    dataSrc: 1
                },
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
            })

            $("#table-wrap").hide()

            $("#btn-search").click(function(){
                tb.clear().draw()
                $("#table-show").find('tfoot').remove()
                $.ajax({
                    url : "{{ route('report.ap.index.post') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        filter : $("#filter").val(),
                        date_to : $("#date_to").val()
                    },
                    beforeSend : function(){
                        Swal.fire({
                            title: "Processing!",
                            text: "Searching data",
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        })
                    },
                    success : function(response){
                        swal.close()
                        if (response != null) {
                            $("#legend").show()
                            $("#table-wrap").show()
                            var num = 1;

                            var sum_amt = 0
                            var sum_ppn = 0
                            var sum_pph = 0
                            var sum_tot = 0
                            for (const key in response) {
                                var data = response[key]
                                if('PO' in data){
                                    var po = data.PO
                                    for (let i = 0; i < po.length; i++) {
                                        var invoice = po[i].invoice

                                        var _detail = po[i].detail
                                        for (let j = 0; j < invoice.length; j++) {

                                            var remarks = ""
                                            if(typeof invoice[j]['remarks'] !== "undefined" && invoice[j]['remarks'] != null){
                                                remarks += invoice[j]['remarks']
                                            }

                                            var _num = "<label class='label bg-hover-light-primary label-inline label-white'>"+po[i].num+"<label>"
                                            var _class = "success"
                                            if(invoice[j].diff >= 3 && invoice[j].diff < 6){
                                                _class = "warning"
                                            } else if(invoice[j].diff >= 6){
                                                _class = "danger"
                                            }

                                            var accordion = '<div class="accordion accordion-light accordion-toggle-arrow" id="accordionExample'+invoice[j].id+'">'
                                            accordion += '<div class="card bg-transparent">'
                                            accordion += '<div class="card-header" id="headingOne'+invoice[j].id+'">'
                                            accordion += '<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne'+invoice[j].id+'">'
                                            accordion += _num
                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '<div id="collapseOne'+invoice[j].id+'" class="collapse" data-parent="#accordionExample'+invoice[j].id+'">'
                                            accordion += '<div class="card-body">'

                                            for (let k = 0; k < _detail.length; k++) {
                                                accordion += "<div class='d-flex align-items-center mb-2'>"
                                                accordion += "<div class='symbol symbol-20 symbol-dark mr-3 ml-5'>"
                                                accordion += "<span class='symbol-label'>"
                                                accordion += ">"
                                                accordion += "</span>"
                                                accordion += "</div>"
                                                accordion += "<div class='d-flex flex-column font-size-xs'>"
                                                accordion += "<label>"+_detail[k].name+"</label>"
                                                accordion += "<span class='text-muted'>IDR <span class='number'>"+_detail[k].price+"</span>@"+_detail[k].qty+"</span>"
                                                accordion += "</div>"
                                                accordion += "</div>"
                                            }

                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '</div>'

                                            tb.row.add([
                                                num,
                                                "PO",
                                                key,
                                                accordion,
                                                po[i].date_invoicing,
                                                // "<div><input type='date' onchange='_pay_date(this)' data-old-value='"+invoice[j].pay_date+"' data-id='"+invoice[j].id+"' class='form-control' value='"++"'></div>",
                                                "<span class='number'>"+invoice[j].amount+"</span>",
                                                "<span class='number'>"+po[i].value+"</span>",
                                                "<div><input type='date' onchange='_due_date(this)' data-id='"+invoice[j].id+"' class='form-control bg-"+_class+"' value='"+invoice[j].due_date+"'></div>",
                                                // "<span class='label label-inline label-"+_class+"'>"+invoice[j].pay_date+"</span>",
                                                "<div><textarea onchange='_remarks(this)' class='form-control' data-id='"+invoice[j].id+"'>"+remarks+"</textarea></div>",
                                                "<button data-id='"+invoice[j].id+"' onclick='_close(this, `PO`)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i> Close</button>"
                                            ]).draw()
                                            num++

                                            sum_amt += parseFloat(invoice[j].amount)
                                            sum_tot += parseFloat(po[i].value)
                                        }
                                    }
                                }
                            }


                            var _ikey = 1
                            for (const key in response) {
                                var data = response[key]
                                if('WO' in data){
                                    var po = data.WO
                                    for (let i = 0; i < po.length; i++) {
                                        var invoice = po[i].invoice
                                        var _detail = po[i].detail
                                        for (let j = 0; j < invoice.length; j++) {

                                            var remarks = ""
                                            if(typeof invoice[j]['remarks'] !== "undefined" && invoice[j]['remarks'] != null){
                                                remarks += invoice[j]['remarks']
                                            }

                                            var _num = "<label class='label bg-hover-light-primary label-inline label-white'>"+po[i].num+"<label>"
                                            var _class = "success"
                                            if(invoice[j].diff >= 3 && invoice[j].diff < 6){
                                                _class = "warning"
                                            } else if(invoice[j].diff >= 6){
                                                _class = "danger"
                                            }

                                            var accordion = '<div class="accordion accordion-light accordion-toggle-arrow" id="accordionExample'+invoice[j].id+'">'
                                            accordion += '<div class="card bg-transparent">'
                                            accordion += '<div class="card-header" id="headingOne'+invoice[j].id+'">'
                                            accordion += '<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne'+invoice[j].id+'">'
                                            accordion += _num
                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '<div id="collapseOne'+invoice[j].id+'" class="collapse" data-parent="#accordionExample'+invoice[j].id+'">'
                                            accordion += '<div class="card-body">'
                                            for (let k = 0; k < _detail.length; k++) {
                                                accordion += "<div class='d-flex align-items-center mb-2'>"
                                                accordion += "<div class='symbol symbol-20 symbol-dark mr-3 ml-5'>"
                                                accordion += "<span class='symbol-label'>"
                                                accordion += ">"
                                                accordion += "</span>"
                                                accordion += "</div>"
                                                accordion += "<div class='d-flex flex-column font-size-xs'>"
                                                accordion += "<label>"+_detail[k].name+"</label>"
                                                accordion += "<span class='text-muted'>IDR <span class='number'>"+_detail[k].price+"</span>@"+_detail[k].qty+"</span>"
                                                accordion += "</div>"
                                                accordion += "</div>"
                                            }
                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '</div>'
                                            accordion += '</div>'

                                            var newRow = tb.row.add([
                                                num,
                                                "WO",
                                                key,
                                                accordion,
                                                po[i].date_invoicing,
                                                // "<div><input type='date' onchange='_pay_date(this)' data-old-value='"+invoice[j].pay_date+"' data-id='"+invoice[j].id+"' class='form-control' value='"+invoice[j].pay_date+"'></div>",
                                                "<span class='number'>"+invoice[j].amount+"</span>",
                                                "<span class='number'>"+po[i].value+"</span>",
                                                "<div><input type='date' onchange='_due_date(this)' data-id='"+invoice[j].id+"' class='form-control bg-"+_class+"' value='"+invoice[j].due_date+"'></div>",
                                                // "<span class='label label-inline label-"+_class+"'>"+invoice[j].due_date+"</span>",
                                                "<div><textarea onchange='_remarks(this)' class='form-control' data-id='"+invoice[j].id+"'>"+remarks+"</textarea></div>",
                                                "<button data-id='"+invoice[j].id+"' onclick='_close(this, `WO`)' class='btn btn-sm btn-danger'><i class='fa fa-times'></i> Close</button>"
                                            ])
                                            if((_ikey % 2) == 0){
                                                tb.row(newRow).column(2).nodes().to$().addClass('bg-secondary');
                                            }
                                            tb.row(newRow).draw()
                                            num++

                                            sum_amt += parseFloat(invoice[j].amount)
                                            sum_tot += parseFloat(po[i].value)
                                        }
                                    }
                                    _ikey++
                                }
                            }

                            var tfoot = ""
                                tfoot += "<tr class='bg-light-primary'>"
                                tfoot += "<th colspan='5' class='text-center font-weight-bold'>"
                                tfoot += "Grand Total"
                                tfoot += "</th>"
                                tfoot += "<th class='text-right'><span class='number'>"+sum_amt+"</span></th>"
                                tfoot += "<th class='text-right'><span class='number'>"+sum_tot+"</span></th>"
                                tfoot += "<th colspan='3'></th>"
                                tfoot += "</tr>"
                                tfoot += ""

                            $("#table-show").append(tfoot)
                        }

                        $(".number").number(true, 2)

                        tb.on( 'search.dt', function () {
                            $(".number").number(true, 2)
                        } );
                    }
                })
            })
        })
    </script>
@endsection
