@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>List Service Request</h3><br>

            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/sr.png')}}" style="width: 35%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" onclick="reload_source('waiting')" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">SR Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" onclick="reload_source('bank')" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">SR Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" onclick="reload_source('reject')" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">SR Rejected</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="allt" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover table-responsive-xl font-size-sm" id="table-list" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">SO #</th>
                                <th class="text-center">RFQ #</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">General Manager Aprvl.</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .select2-results__options {
        max-height: 500px;
    }
</style>
@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/crud/forms/widgets/typeahead.js?v=7.0.5')}}"></script>
    <link href="{{asset('theme/jquery-ui/jquery-ui.css')}}" rel="Stylesheet"></link>
    <script src="{{asset('theme/jquery-ui/jquery-ui.js')}}"></script>
    <script>
        $('#opt').hide();
        var cat;
        var srcItem = [];
        $('form').submit(function () {
            var division = $.trim($('#division').val());
            var request_time = $.trim($('#request_time').val());
            var so_type = $.trim($('#so_type').val());
            var project = $.trim($('#project').val());
            var fr_note = $.trim($('#fr_note').val());

            if (division  === '') { alert('Division is mandatory.'); return false; }
            if (so_type  === '') { alert('SO Type is mandatory.'); return false; }
            if (request_time  === '') { alert('Request Date is mandatory.'); return false; }
            if (project  === '') { alert('Project is mandatory.'); return false; }
            if (fr_note  === '') { alert('Note is mandatory.'); return false; }
        });

        $(document).ready(function(){
            $("select.form-control").select2({
                width: '100%'
            })
        });

        function deleteRow(o){
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }
        function addInput(trName) {
            var newrow = document.createElement('tr');
            newrow.innerHTML = "<td align='center'>" +
                "<input type='hidden' name='name[]' value='" + $("#item").val() + "'><b>" + $("#item").val() + "</b><br />" +
                "</td>" +
                "<td align='center'>" +
                "<input type='hidden' name='qty[]' value='" + $("#qty").val() + "'>" + $("#qty").val() +
                "</td>" +
                "<td align='center'>" +
                "<button type='submit' onClick='deleteRow(this)' class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></button>" +
                "</td>";
            document.getElementById(trName).appendChild(newrow);
            $("#item").val("");
            $("#qty").val("");
        }

        var type = "waiting"

        function reload_source(x){
            type = x
            var route = "{{ route('sre.list') }}/rfq/"+type
            $("#table-list").DataTable().destroy()
            table_list()

        }

        function table_list(){
            var route = "{{ route('sre.list') }}/rfq/"+type
            $("#table-list").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                processing: true,
                serverSide: true,
                ajax : {
                    url : route,
                    type: "get"
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "reference"},
                    {"data" : "paper"},
                    {"data" : "project"},
                    {"data" : "company"},
                    {"data" : "req_by"},
                    {"data" : "date"},
                    {"data" : "items"},
                    {"data" : "div_appr"},
                    {"data" : "action"},
                ],
                columnDefs : [
                    {"targets" : "_all", "className" : "text-center"},

                ],
                initComplete: function(settings, json){
                    var table = $("#table-list")
                    var thead = table.find('thead')
                    var tr = thead.find('tr')
                    console.log(tr)
                    if(json.type == "waiting"){
                        $(tr).css('background-color', '#96caff')
                    } else if (json.type == "bank"){
                        $(tr).css('background-color', '#88e1dd')
                    } else {
                        $(tr).css('background-color', '#faa3ac')
                    }
                }
            })
        }

        $(document).ready(function(){
            table_list()
        });

        function getURLProject(){
            var url = "{{URL::route('fr.getProject',['cat' => ':id1'])}}";
            url = url.replace(':id1', cat);
            return url;
        }

        $('#project').select2({
            ajax: {
                url: function (params) {
                    return getURLProject()
                },
                type: "GET",
                placeholder: 'Choose Project',
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

    </script>
@endsection
