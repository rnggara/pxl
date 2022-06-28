@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Tax</h3><br>
            </div>
        </div>
        <div class="card-body">
            <div class="row mx-auto">
                <div class="col-md-8 mx-auto">
                    <div class="card card-custom gutter-b bg-secondary">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>Filter</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Range date</label>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <table>
                                                    <tr>
                                                        <td><input type="date" class="form-control" name="sdate" id="sdate"></td>
                                                        <td><input type="date" class="form-control" name="edate" id="edate"></td>
                                                        <td align="center"><button type="button" id="btn-clear-date" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-times-circle"></i></button></td>
                                                    </tr>
                                                    <tr>

                                                    </tr>
                                                    <tr>

                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <div class="radio-inline">
                                            <label class="radio radio-square radio-outline radio-success">
                                                <input type="radio" checked="checked" id="radio-all" name="rtype" value="all"/>
                                                <span class="bg-white"></span>
                                                All
                                            </label>
                                            <label class="radio radio-square radio-outline radio-success">
                                                <input type="radio" name="rtype" value="in"/>
                                                <span class="bg-white"></span>
                                                In
                                            </label>
                                            <label class="radio radio-square radio-outline radio-success">
                                                <input type="radio" name="rtype" value="out"/>
                                                <span class="bg-white"></span>
                                                Out
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Tax List</label>
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-square  checkbox-outline checkbox-success">
                                                <input type="checkbox" id="ck_all" checked name="ck_tax[]" value="all"/>
                                                <span class="bg-white"></span>
                                                All
                                            </label>
                                            @foreach($tax as $item)
                                                <label class="checkbox checkbox-square  checkbox-outline checkbox-success">
                                                    <input type="checkbox" class="ck_tax" name="ck_tax[]" value="{{$item->id}}"/>
                                                    <span class="bg-white"></span>
                                                    {{$item->tax_name}}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group btn-group">
                                        <button type="button" onclick="getData()" class="btn btn-xs btn-primary"><i class="fa fa-filter"></i>Filter</button>
                                        <button type="button" onclick="resetData()" class="ml-3 btn btn-xs btn-danger"><i class="flaticon2-reload-1"></i>Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table display" data-page-length="100">
                <thead>
                <tr>
                    <th class="text-center" colspan="8"><span class="label label-inline label-lg label-primary">Total Tax :&nbsp;<span id="total-tax"></span></span></th>
                </tr>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Source Type</th>
                    <th class="text-center">Paper Type</th>
                    <th class="text-center">Paper#</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Tax Type</th>
                    <th class="text-center">Tax Value</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5')}}"></script>
    <script>

        function getData(){
            var sdate = $("#sdate").val()
            var edate = $("#edate").val()
            var type = $("input:radio:checked").val()
            var tax = $("input:checkbox:checked").toArray()
            var taxValue = []
            for (const taxKey in tax) {
                taxValue.push(tax[taxKey].value)
            }

            $.ajax({
                url: "@actionStart('tax', 'read'){{route('tax.get_data')}}@actionEnd",
                type: "post",
                dataType: "json",
                data : {
                    _token : "{{csrf_token()}}",
                    sdate : sdate,
                    edate : edate,
                    type : type,
                    tax : taxValue
                },
                cache: false,
                success: function(response){
                    $("#total-tax").text("IDR " + response.total)
                    $("table.display").DataTable().destroy()
                    $("table.display").DataTable({
                        fixedHeader: true,
                        fixedHeader: {
                            headerOffset: 90
                        },
                        ordering: false,
                        bInfo: false,
                        "processing": true,
                        ajax : {
                            url: "@actionStart('tax', 'read'){{route('tax.get_data')}}@actionEnd",
                            type: "post",
                            data : {
                                _token : "{{csrf_token()}}",
                                sdate : sdate,
                                edate : edate,
                                type : type,
                                tax : taxValue
                            },
                        },
                        columns : [
                            { "data" : "num" },
                            { "data" : "source" },
                            { "data" : "paper_type" },
                            { "data" : "paper" },
                            { "data" : "date" },
                            { "data" : "taxtype" },
                            { "data" : "taxamount" },
                            { "data" : "link" },
                        ],
                        columnDefs: [
                            { targets: '_all', className: "text-center" }
                        ]
                    })
                }
            })
        }

        function resetData(){
            $("#btn-clear-date").click()
            $("#radio-all").prop('checked', true)
            $("#ck_all").prop('checked', true)
            $(".ck_tax").prop('checked', false)
            getData()
        }

        $(document).ready(function(){

            getData()

            $("#btn-clear-date").click(function(){
                $("#sdate").val('')
                $("#edate").val('')
            })

            $("#sdate").change(function(){
                console.log($(this).val())
                $("#edate").val("{{date('Y-m-d')}}")
            })

            $("#ck_all").change(function(){
                if (this.checked == true){
                    console.log(this.checked)
                    $(".ck_tax").prop('checked', false)
                }
            })

            $("input.ck_tax").change(function(){
                if (this.checked == true){
                    $("#ck_all").prop('checked', false)
                }
            })

            $("select.select2").select2({
                width: "100%"
            })

        })
    </script>
@endsection

