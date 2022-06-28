@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Leads List</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addCategory"><i class="fa fa-cogs"></i>Category</button>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeads"><i class="fa fa-plus"></i>Add Leads</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th nowrap="nowrap" class="text-left" rowspan="2">Company Name</th>
                        <th nowrap="nowrap" class="text-center" rowspan="2">Leads Name</th>
                        <th nowrap="nowrap" class="text-center" rowspan="2">Category</th>
                        <th nowrap="nowrap" class="text-center" colspan="2">Referral</th>
                        <th nowrap="nowrap" class="text-center" colspan="2">Managed By</th>
                        <th nowrap="nowrap" class="text-center" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Number</th>
                        <th class="text-center">Partner</th>
                        <th class="text-center">Associates</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 0;  ?>
                        @foreach($leads as $key => $lead)
                            @if(\Illuminate\Support\Facades\Auth::user()->username == $lead->created_by || \Illuminate\Support\Facades\Auth::user()->username == "admin" || isset($data_associates[$lead->id]) && in_array(\Illuminate\Support\Facades\Auth::id(), $data_associates[$lead->id]))
                            <tr>
                                <td align="center">{{$num + 1}}<?php $num++ ?></td>
                                <td align="left">{{$data_client['client_name'][$lead->id_client]}}</td>
                                <td class="text-center"><a href="{{route('leads.view', $lead->id)}}" class="text-hover-danger">{{$lead->leads_name}}</a></td>
                                <td class="text-center">{{(isset($data_category[$lead->id_category]))? $data_category[$lead->id_category]['category_name'] : ""}}</td>
                                <td class="text-center">{{$data_client['pic'][$lead->id_client]}}</td>
                                <td class="text-center">{{$data_client['pic_number'][$lead->id_client]}}</td>
                                <td class="text-center">
                                    @if($lead->partner != null)
                                        {{$data_user['username'][$lead->partner]}}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($lead->contributors != null)
                                        @php
                                            /** @var TYPE_NAME $lead */
                                            $contributors = json_decode($lead->contributors);
                                            $contributorecho = '';
                                            foreach ($contributors as $val){
                                                /** @var TYPE_NAME $data_user */
                                                $contributorecho .= $data_user['username'][$val]."\n\r";
                                            }
                                        @endphp
                                        {{$contributorecho}}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td align="center">
                                    <button class="btn btn-dark btn-xs btn-icon" onclick="edit_show({{$lead->id_client}})" data-toggle="modal" data-target="#editLeads{{$lead->id}}"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-xs btn-icon" onclick="button_delete({{$lead->id}})"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <div class="modal fade" id="editLeads{{$lead->id}}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Leads</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{URL::route('leads.edit')}}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <br>
                                                        <h4>Leads Info</h4><hr>
                                                        <div class="form-group">
                                                            <label>Leads Name</label>
                                                            <input type="text" class="form-control" name="leads_name" placeholder="Leads Name" value="{{$lead->leads_name}}" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Leads Description</label>
                                                            <textarea name="description" class="form-control" cols="30" rows="10">{{strip_tags($lead->description)}}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Project Client</label>
{{--                                                            <input type="hidden" id="id_client" value="{{$lead->id_client}}">--}}
                                                            <select class="form-control select2 clients" name="client" required>

                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Project Category</label>
                                                            <select class="form-control select2 categories" name="category" required>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <input type="hidden" name="id_leads" value="{{$lead->id}}">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLeads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Leads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('leads.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6" id="form-leads">
                                <br>
                                <h4>Leads Info</h4><hr>
                                <div class="form-group">
                                    <label>Leads Name</label>
                                    <input type="text" class="form-control" name="leads_name" placeholder="Leads Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Partner</label>
                                    <select name="partner" class="form-control select2" id="">
                                        <option value="">Select Partner</option>
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}">{{$item->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category" class="form-control select2" id="">
                                        <option value="">Select Category</option>
                                        @foreach($leads_category as $item)
                                            <option value="{{$item->id}}">{{$item->category_name}}[{{$item->category_type}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Leads Description</label>
                                    <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Project Client</label>
                                    <select class="form-control select2" id="client" name="client" required>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="form-client">
                                <br>
                                <h4>Add Client</h4><hr>
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" id="company_name" placeholder="Company Name" />
                                </div>
                                <div class="form-group">
                                    <label>Company Address</label>
                                    <textarea id="address" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" id="phone_1" placeholder="Phone Number" />
                                </div>
                                <div class="form-group">
                                    <label>PIC Name</label>
                                    <input type="text" class="form-control" id="pic_name" placeholder="PIC Name" />
                                </div>
                                <div class="form-group">
                                    <label>PIC Phone Number</label>
                                    <input type="text" class="form-control" id="pic_phone" placeholder="PIC Phone Number" />
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4"></label>
                                    <div class="col-md-8">
                                        <button class="btn btn-secondary btn-xs" type="button" id="btn-cancel-client">Cancel</button>
                                        <button class="btn btn-primary btn-xs" type="button" id="btn-save-client"><i class="fa fa-plus"></i> Add Client</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addCategory" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Leads Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('leads.cat.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="form-leads">

                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" class="form-control" name="category_name" placeholder="Category Name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Category Type</label>
                                    <select name="category_type" class="form-control select2" id="">
                                        <option value="">--Select Type--</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Financial">Financial</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads-category" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
                <br><br><br>
                <div class="modal-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leads_category as $key=> $val)
                            <tr>
                                <td>{{$val->category_name}}</td>
                                <td>{{$val->category_type}}</td>
                            </tr>
                            @endforeach
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

        function button_delete(x){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{URL::route('leads.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: "false",
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(function () {
            getClients()
            $("#form-client").hide()
            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-save-leads").click(function(){
                console.log('submit cok')
            })

            $("#btn-save-client").click(function(){
                $("#btn-save-client").addClass("spinner spinner-white spinner-right")
                if ($("#company_name").val() == "" || $("#address").val() == "" || $("#phone_1").val() == "" || $("#pic_name").val() == "" || $("#pic_phone").val() == ""){
                    Swal.fire('Blank field', 'Please fill out the form!', 'warning')
                    $("#btn-save-client").removeClass("spinner spinner-white spinner-right")
                }
                $.ajax({
                    url: "{{route("marketing.client.add.js")}}",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {
                        '_token' : "{{csrf_token()}}",
                        'company_name' : $("#company_name").val(),
                        'address' : $("#address").val(),
                        'phone_1' : $("#phone_1").val(),
                        'pic' : $("#pic_name").val(),
                        'pic_number' : $("#pic_phone").val()
                    },
                    success: function(response){
                        if(response.error == 0){
                            $("#form-client").hide()
                            $("#form-leads *").attr('disabled', false)
                            $("#btn-save-leads").attr('disabled', false)
                            $("#client").val("").trigger("change")
                            $("#btn-save-client").removeClass("spinner spinner-white spinner-right")
                            getClients()
                            resetForm()
                        } else {
                            Swal.fire('Error occured', 'Please contact your administrator', 'error')
                            $("#btn-save-client").removeClass("spinner spinner-white spinner-right")
                        }
                    }
                })
            })

            $("#client").change(function(){
                var selected = $("#client option:selected").val()
                if (selected == "new"){
                    $("#form-client").show()
                    $("#form-client *").attr('disabled', false)
                    $("#form-leads *").attr('disabled', true).off('click')
                    $("#btn-save-leads").attr('disabled', true)
                }
            })

            $("#addLeads").on('hidden.bs.modal', function(){
                $("#form-client").hide()
                $("#form-leads *").attr('disabled', false)
                $("#btn-save-leads").attr('disabled', false)
                $("#client").val("").trigger("change")
                $("#btn-save-client").removeClass("spinner spinner-white spinner-right")
                resetForm()
            })

            $("#btn-cancel-client").click(function(){
                $("#form-client").hide()
                $("#form-leads *").attr('disabled', false)
                $("#btn-save-leads").attr('disabled', false)
                $("#client").val("").trigger("change")
                $("#btn-save-client").removeClass("spinner spinner-white spinner-right")
                resetForm()
            })

            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

        function getClients() {
            $("#client").val(null).trigger("change");
            $("#client").empty()
            $("#client").append("<option value=''>Select Client</option>")
            $.ajax({
                url: "{{URL::route('marketing.client.get.js')}}",
                type: "GET",
                dataType: "json",
                success: function(response){
                    $("#client").select2({
                        data: response.results,
                        width: "100%"
                    })
                    $("#client").append("<option value='new'>Add New Client</option>")
                }
            })

            $(".clients").val(null).trigger("change");
            $(".clients").empty()
            $(".clients").append("<option value=''>Select Client</option>")
            $.ajax({
                url: "{{URL::route('marketing.client.get.js')}}",
                type: "GET",
                dataType: "json",
                success: function(response){
                    $(".clients").select2({
                        data: response.results,
                        width: "100%"
                    })
                }
            })

            $(".categories").val(null).trigger("change");
            $(".categories").empty()
            $(".categories").append("<option value=''>Select Category</option>")
            $.ajax({
                url: "{{URL::route('leads.get_categories.js')}}",
                type: "GET",
                dataType: "json",
                success: function(response){
                    $(".categories").select2({
                        data: response.results,
                        width: "100%"
                    })
                }
            })
        }

        function edit_show(x){
            $(".clients").val(x).trigger('change')
            $(".categories").val(x).trigger('change')

        }

        function resetForm(){
            $("#form-client input").val("")
            $("#form-client textarea").val("")
        }
    </script>
@endsection
