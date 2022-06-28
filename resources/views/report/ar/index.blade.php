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
                /* font-size: 95%; */
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
            <h3 class="card-title">Account Receivable
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
                    <h3><span class="font-weight-bold">Account Receivable {{ Session::get('company_name_parent') }}</span> : {{ date("d/m/Y") }}</h3>
                    <table class="table table-bordered table-hover display" id="table-show">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th class="text-center">CUSTOMER</th>
                                {{-- <th class="text-center w-100">PROJECT</th> --}}
                                <th class="text-center w-100">NO INVOICE</th>
                                {{-- <th class="text-center">WAPU</th> --}}
                                <th class="text-center">NO KONTRAK</th>
                                <th class="text-center">DATE INVOICING</th>
                                {{-- <th class="text-center">ITEM</th> --}}
                                <th class="text-center">AMOUNT (RP)</th>
                                <th class="text-center w-50">PPN 10% (RP)</th>
                                <th class="text-center">PPH 23 (RP)</th>
                                <th class="text-center">TOTAL AFTER TAX</th>
                                <th class="text-center">DUE DATE</th>
                                <th class="text-center print-hide w-25">REMARKS</th>
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
                url : "{{ route('report.ar.update') }}",
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
                url : "{{ route('report.ar.update') }}",
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

        $(document).ready(function(){

            $("#legend").hide()

            $("select.select2").select2({
                width : "100%"
            })

            var tb = $("#table-show").DataTable({
                paging : false,
                ordering : false,
                bInfo : false,
                rowsGroup : [
                    1, 5
                ],
                columnDefs : [
                    {"targets" : [5,6,7,8], "className" : "text-right"},
                    {"targets" : [4], "className" : "text-nowrap text-center"},
                    {"targets" : [0,2,3, 9], "className" : "text-center"},
                    {"targets" : [10], "className" : "print-hide"}
                ],
                rowGroup: {
                    startRender: null,
                    endRender: function ( rows, group ) {
                        // console.log(rows.data().pluck(10))
                        var sum_amount = rows
                            .data()
                            .pluck(6)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        var sum_ppn = rows
                            .data()
                            .pluck(7)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        var sum_pph = rows
                            .data()
                            .pluck(8)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        var sum_after_tax = rows
                            .data()
                            .pluck(9)
                            .reduce( function (a, b) {
                                return a + parseFloat($(b).text());
                            }, 0);

                        return $('<tr/>')
                            .append( '<td colspan="6">Total Payment for '+group+'</td>' )
                            .append( '<td align="right"><span class="number">'+sum_amount+'</span></td>' )
                            .append( '<td align="right"><span class="number">'+sum_ppn+'</span></td>' )
                            .append( '<td align="right"><span class="number">'+sum_pph+'</span></td>' )
                            // .append( '<td align="right"><span class="number">'+sum_after_tax+'</span></td>' )
                            .append( "<td colspan='2'></td>" )
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
                    url : "{{ route('report.ar.index.post') }}",
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

                                var data = response[key].data
                                for (const ikey in data) {
                                    var invoice = data[ikey].invoice
                                    for (let i = 0; i < invoice.length; i++) {

                                        var ppn_val = invoice[i].ppn

                                        var _wapu = "<label class='label label-inline label-success'>WAPU</label>"

                                        if(invoice[i].wapu == null || invoice[i].wapu == 0){
                                            // ppn_val = 0
                                            _wapu = ""
                                        }

                                        var remarks = ""
                                        if(invoice[i].remarks != null){
                                            remarks += invoice[i].remarks
                                        }

                                        var _class = "success"
                                        if(invoice[i].diff >= 3 && invoice[i].diff < 6){
                                            _class = "primary"
                                        } else if(invoice[i].diff >= 6){
                                            _class = "danger"
                                        }

                                        var total_after_tax = (parseFloat(invoice[i].value_d) + parseFloat(ppn_val)) + parseFloat(invoice[i].pph23)
                                        tb.row.add([
                                            num,
                                            response[key].client_name,
                                            "<div class=''><label class='font-weight-bold text-nowrap'>"+invoice[i].no_inv+"</label><br>"+invoice[i].activity+"</div>"+
                                            "<br><span class='font-size-xs'>"+data[ikey].prj_name+"</span>",
                                            data[ikey].no_invoice,
                                            invoice[i].date,
                                            "<span class='number'>"+invoice[i].value_d+"</span>",
                                            _wapu + "<br><span class='number'>"+ppn_val+"</span>",
                                            "<span class='number'>"+invoice[i].pph23+"</span>",
                                            "<span class='number'>"+total_after_tax+"</span>",
                                            "<span class='label label-inline text-nowrap label-"+_class+"'>"+invoice[i].due_date+"</span>",
                                            // "<div><input type='date' onchange='_due_date(this)' data-id='"+invoice[i].id+"' class='form-control bg-"+_class+"' value='"+invoice[i].due_date+"'></div>",
                                            "<div><textarea onchange='_remarks(this)' class='form-control' data-id='"+invoice[i].id+"'>"+remarks+"</textarea></div>"
                                        ]).draw()
                                        num++

                                        sum_amt += parseFloat(invoice[i].value_d)
                                        sum_ppn += ppn_val
                                        sum_pph += invoice[i].pph23
                                        sum_tot += total_after_tax
                                    }
                                }
                            }

                            var tfoot = ""
                                tfoot += "<tr class='bg-light-primary'>"
                                tfoot += "<th colspan='5' class='text-center font-weight-bold'>"
                                tfoot += "Grand Total"
                                tfoot += "</th>"
                                tfoot += "<th class='text-right'><span class='number font-weight-bold'>"+sum_amt+"</span></th>"
                                tfoot += "<th class='text-right'><span class='number font-weight-bold'>"+sum_ppn+"</span></th>"
                                tfoot += "<th class='text-right'><span class='number font-weight-bold'>"+sum_pph+"</span></th>"
                                tfoot += "<th class='text-right'><span class='number font-weight-bold'>"+sum_tot+"</span></th>"
                                tfoot += "<th colspan='2'></th>"
                                tfoot += "</tr>"
                                tfoot += ""

                            $("#table-show").append(tfoot)
                        }

                        $(".number").number(true, 2)
                    }
                })
            })
        })
    </script>
@endsection
