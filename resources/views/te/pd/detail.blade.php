@extends('layouts.template')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.8.0/viewer.min.css" integrity="sha512-i7JFM7eCKzhlragtp4wNwz36fgRWH/Zsm3GAIqqO2sjiSlx7nQhx9HB3nmQcxDHLrJzEBQJWZYQQ2TPfexAjgQ==" crossorigin="anonymous" />
@endsection
@section('content')
    <input type="hidden" id="json" value="{{$json_els}}">
    <input type="hidden" id="json_file" value="{{$file_}}">
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">{{$elCat->category_name}}</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLeads"><i class="fa fa-plus"></i>Add Item</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-left">Project Name</th>
                                <th nowrap="nowrap" class="text-center">Company Name</th>
                                <th nowrap="nowrap" class="text-center">Type</th>
                                <th nowrap="nowrap" class="text-center">list Items</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($els as $key => $val)
                                    <tr>
                                        <td align="center">{{$key + 1}}</td>
                                        <td>
                                            <span class="label label-inline label-info">{{$val->project_name}}</span>
                                        </td>
                                        <td align="center">
                                            <b>{{$val->company_name}}</b>
                                        </td>
                                        <td align="center">
                                            <b>{{strtoupper($val->type)}}</b>
                                        </td>
                                        <td align="center">
                                            <button class="btn btn-info btn-xs" onclick="button_list('{{$val->id}}')"><i class="fa fa-list"></i> View list</button>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-primary btn-xs btn-icon" onclick="button_edit('{{$key}}')"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-xs btn-icon" onclick="button_delete('{{$val->id}}')"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addLeads" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.pd.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Project Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="project_name" placeholder="Project Name" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Company Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="company_name" placeholder="Company Name" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="" required>
                                            <option value="">Select Type</option>
                                            <option value="ONSHORE">ONSHORE</option>
                                            <option value="OFFSHORE">OFFSHORE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Capacity</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="capacity" placeholder="Capacity" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4" id="param2-label">Diameter Separator</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="diameter_separator" placeholder="Diameter Separator"/>
                                        <span class="label label-inline label-light-danger font-size-sm">* paragon's calculation</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Oil</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_oil" placeholder="Capacity Oil" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Water</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_water" placeholder="Capacity Water" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Gas</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_gas" placeholder="Capacity Gas" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Retention Time</label>
                                    <div class="col-md-8">
                                        <input type="text" name="retention_time" placeholder="Retention Time" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Thumbnail</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Drawing</label>
                                    <div class="col-md-8">
                                        <div class="custom-file">
                                            <input type="file" name="drawing" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Description</label>
                                    <div class="col-md-8">
                                        <textarea name="desc" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" name="id_category" value="{{$elCat->id}}">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.pd.update')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Project Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="project_name" id="el-project_name" placeholder="Project Name" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Company Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="company_name" id="el-company_name" placeholder="Company Name" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="el-type" required>
                                            <option value="">Select Type</option>
                                            <option value="ONSHORE">ONSHORE</option>
                                            <option value="OFFSHORE">OFFSHORE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Capacity</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="el-capacity" placeholder="capacity" name="capacity" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Diameter Separator</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="el-diameter_separator" placeholder="Diameter Separator" name="diameter_separator" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Oil</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_oil" id="el-coil" placeholder="Capacity Oil" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Water</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_water" id="el-cwater" placeholder="Capacity Water" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Capacity Gas</label>
                                    <div class="col-md-8">
                                        <input type="text" name="capacity_gas" id="el-cgas" placeholder="Capacity Gas" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Retention Time</label>
                                    <div class="col-md-8">
                                        <input type="text" name="retention_time" id="el-rtime" placeholder="Retention Time" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Thumbnail</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-thumbnail" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-thumbnail">
                                                        <a href="#" class="navi-link" onclick="button_view('img-thumbnail')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-thumbnail">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-thumbnail" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-thumbnail">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Drawing</label>
                                    <div class="col-md-6">
                                        <div class="custom-file">
                                            <input type="file" name="drawing" class="custom-file-input"/>
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="Quick actions" data-placement="left">
                                            <a href="#" class="btn btn-icon" id="el-more-drawing" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="svg-icon svg-icon-success svg-icon-2x">
												<i class="flaticon-more-1 font-weight-bold"></i>
											</span>
                                            </a>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Naviigation-->
                                                <ul class="navi">
                                                    <li class="navi-separator mb-3 opacity-70"></li>
                                                    <li class="navi-item" id="el-view-drawing">
                                                        <a href="#" class="navi-link" onclick="button_view('img-drawing')">
														<span class="navi-icon">
															<i class="flaticon-eye"></i>
														</span>
                                                            <span class="navi-text">View</span>
                                                        </a>
                                                        <img src="" alt="" class="image-show" id="img-drawing">
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-download-drawing" target="_blank">
														<span class="navi-icon">
															<i class="navi-icon flaticon-download-1"></i>
														</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    <li class="navi-item">
                                                        <a href="#" class="navi-link" id="el-delete-drawing">
														<span class="navi-icon">
															<i class="navi-icon flaticon-delete-1"></i>
														</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!--end::Naviigation-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Description</label>
                                    <div class="col-md-8">
                                        <textarea name="desc" class="form-control" id="el-description" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_el" id="el-id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="listItems" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="listItemsContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="addItems" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Items <span id="title-items"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('te.pd.updateItems')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-10">
                                <table class="display table table-bordered table-hover table-responsive-xl table-striped" id="table-add-list" data-page-length="25">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Serial Number</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Label</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="items" id="items">
                        <input type="hidden" name="id_pd" id="pd-id">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewItems" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modalView">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.8.0/viewer.js" integrity="sha512-Yuv1HMS0DNKLgd09CcwVjsDkfEzaBv9CItoCgfTfbglWFXLenu7BoZylWuUtfvvZE55/j8fkaCVtWz1ZtxCo+Q==" crossorigin="anonymous"></script>

    <script>
        // import Viewer from 'viewerjs/dist/viewer.esm';
        function view_items(x) {
            $("#modalView").html(" ")
            $("#viewItems").modal('show')
            $.ajax({
                url: "{{route('te.el.items_detail')}}/" + x,
                type: "get",
                cache: false,
                success: function(response){
                    $("#modalView").append(response)
                }
            })
        }

        function addItems(x,y) {
            $("#pd-id").val(x)
            $("#addItems").modal('show')
            $("#title-items").html(y)
            $("#table-add-list").DataTable().destroy()
            $.ajax({
                url: "{{route('te.el.items')}}/"+x+"/pd",
                type: "GET",
                dataType: "json",
                cache: false,
                success: function(response){
                    $("#items").val(JSON.stringify(response.items))
                    $("#table-add-list").DataTable({
                        "data" : response.data,
                        "columns" : [
                            { "data" : "key" },
                            { "data" : "serial_number" },
                            { "data" : "category" },
                            { "data" : "type" },
                            { "data" : "label" },
                            { "data" : "status" },
                        ],
                        'columnDefs' : [
                            {"targets" : "_all", "className" : "dt-center"}
                        ]
                    })
                }
            })
        }

        function items_check(x) {
            var items = JSON.parse($("#items").val())
            var i = $(x).val()
            if (x.checked){
                if (items.includes(i)){
                    console.log('')
                } else {
                    items.push(i)
                }
            } else {
                var index = items.indexOf(i)
                if (index >= 0){
                    items.splice(index, 1)
                }
            }

            $("#items").val(JSON.stringify(items))
        }

        function button_list(x){
            $("#listItemsContent").html(" ")
            $("#listItems").modal('show')
            $.ajax({
                url: "{{route('te.pd.findItems')}}/"+x,
                type: "get",
                cache: "false",
                success: function(response){
                    $("#listItemsContent").append(response)
                }
            })
        }

        function button_view(x){
            var Viewer = window.Viewer
// View an image
            var viewer = new Viewer(document.getElementById(x), {
                modal: true,
                toolbar: false,
                viewed() {
                    viewer.zoom(0.1);
                },
            });

            if (viewer){
                viewer.destroy();
                var viewer = new Viewer(document.getElementById(x), {
                    modal: true,
                    toolbar: false,
                    viewed() {
                        viewer.zoom(0.1);
                    },
                });
                viewer.show()
            } else {
                viewer.show()
            }
        }

        function button_edit(x) {
            var json_cat = $("#json").val()
            var cat = JSON.parse(json_cat)
            // console.log(file_[cat[x].coi])
            $("#editCategory").modal('show')

            $("#el-project_name").val(cat[x].project_name)
            $("#el-company_name").val(cat[x].company_name)
            $("#el-type").val(cat[x].type).trigger("change")
            $("#el-capacity").val(cat[x].capacity)
            $("#el-diameter_separator").val(cat[x].diameter_separator)
            $("#el-coil").val(cat[x].capacity_oil)
            $("#el-cgas").val(cat[x].capacity_gas)
            $("#el-cwater").val(cat[x].capacity_water)
            $("#el-rtime").val(cat[x].retention_time)
            $("#el-id").val(cat[x].id)
            $("#el-description").val(cat[x].description)
            isFileExist(cat[x], "drawing")
            isFileExist(cat[x], "thumbnail")
        }

        function isFileExist(x, y){
            var json_file = $("#json_file").val()
            var file_ = JSON.parse(json_file)
            if (x[y] == null || x[y] === ""){
                $("#el-more-"+y).hide()
            } else {
                var file = "../../../../public_html/" + file_[x[y]]
                $("#img-"+y).attr("src",  "../../../../public_html/" + file_[x[y]])
                var ext = file.split(".")
                var validateExt = ['gif', 'jpeg', 'png', 'jpg', 'tiff'];
                if (!validateExt.includes(ext[ext.length-1])){
                    $("#el-view-"+y).hide()
                }
                $("#el-download-"+y).attr("href", "{{route('download')}}/"+x[y])
                $("#el-delete-"+y).on("click", function () {
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
                                url : "{{URL::route('te.pd.deleteFile')}}/" + x['id'] + "/" + y,
                                type: "get",
                                dataType: "json",
                                cache: "false",
                                success: function(response){
                                    if (response.error == 0){
                                        $("#el-more-"+y).hide()
                                    } else {
                                        Swal.fire('Error occured', 'Please contact your administrator!', 'error')
                                    }
                                }
                            })
                        }
                    })
                })
            }
        }

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
                        url : "{{URL::route('te.pd.delete')}}/" + x,
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
            $(".image-show").attr("style", "max-width: '1280px'")
            $(".image-show").hide()


            $("select.select2").select2({
                width: "100%"
            })


            $('.display').DataTable({
                responsive: true,
            });
        })

    </script>
@endsection
