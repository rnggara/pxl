@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Purchase Request List</h3><br>

            </div>
        </div>
        <div class="card-body">
            <div class="row mb-5 mt-5">
                <div class="col-md-12">
                    <img src="{{asset('media/pr.png')}}" style="width: 35%">
                </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" onclick="reload_source('waiting')" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">PR Waiting</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="reload_source('bank')" id="profile-tab" data-toggle="tab" href="#sales" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-2"></i>
                        </span>
                        <span class="nav-text">PR Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="reload_source('reject')" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">PR Rejected</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="allt" role="tabpanel" aria-labelledby="home-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-responsive-xl table-hover display font-size-sm" id="table-list" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr id="table-head">
                                <th class="text-center">#</th>
                                <th class="text-center">FR#</th>
                                <th class="text-center">PRE#</th>
                                <th class="text-center">PRE Date</th>
                                <th class="text-center">Request by</th>
                                <th class="text-center">Division</th>
                                <th class="text-center">Project</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Item(s)</th>
                                <th class="text-center">Director Approval</th>
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
@section('custom_script')
<script>
    var type = "waiting"

        function reload_source(x){
            type = x
            var route = "{{ route('pre.list') }}/pre/"+type
            $("#table-list").DataTable().destroy()
            table_list()
        }

        function table_list(){
            var route = "{{ route('pre.list') }}/pre/"+type

            var th = $("#table-head")
            if(type == "waiting"){
                $(th).css('background-color', '#96caff')
            } else if (type == "bank"){
                $(th).css('background-color', '#88e1dd')
            } else {
                $(th).css('background-color', '#faa3ac')
            }

            table = $("#table-list").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 25,
                ajax : {
                    url : route,
                    type : "get"
                },
                columns : [
                    { "data" : "i"},
                    { "data" : "fr_num"},
                    { "data" : "paper"},
                    { "data" : "date"},
                    { "data" : "req_by"},
                    { "data" : "division"},
                    { "data" : "project"},
                    { "data" : "company"},
                    { "data" : "items"},
                    { "data" : "dir_appr"},
                    { "data" : "action"},
                ],
                columnDefs : [
                    {"targets" :"_all", "className" : "text-center"}
                ],
                initComplete: function(settings, json){
                    // var th = $("#table-head")
                    // if(json.type == "waiting"){
                    //     $(th).css('background-color', '#96caff')
                    // } else if (json.type == "bank"){
                    //     $(th).css('background-color', '#88e1dd')
                    // } else {
                    //     $(th).css('background-color', '#faa3ac')
                    // }
                }
            })
        }
    $(document).ready(function(){
        table_list()
    });
</script>
@endsection
