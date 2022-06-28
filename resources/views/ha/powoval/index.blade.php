@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-line mb-5">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#powocode" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="nav-icon"><i class="flaticon2-group"></i></span>
                        <span class="nav-text">PO & WO Code</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#usedcode" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="nav-icon"><i class="flaticon2-group"></i></span>
                        <span class="nav-text">Used Code</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="powocode" role="tabpanel" aria-labelledby="po-type">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">Available PO & WO Validation Code</a>
                            </div>
                            @actionStart('powo_validation', 'create')
                            <div class="card-toolbar">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-primary" onclick="button_add_type()"><i class="fa fa-plus"></i>Add Validation Code</button>
                                </div>
                                <!--end::Button-->
                            </div>
                            @actionEnd
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Validation Code</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Author</th>
                                                <th class="text-center">Purpose</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_validation', 'read')
                                            @php $num = 1; @endphp
                                            @foreach($papers as $key => $item)
                                                @if($item->issued_date == null)
                                                    <tr>
                                                        <td align="center">{{$num++}}</td>
                                                        <td align="center">
                                                            <button data-clipboard-text="{{$item->kode}}" data-toggle="tooltip" data-trigger="focus" data-delay='{"show":"10", "hide":"100"}' title="coppied" class="btn-copy btn btn-success label label-inline label-success">{{$item->kode}}</button>
                                                        </td>
                                                        <td align="center">
                                                            <b>{{strtoupper($item->nama_paper)}}</b>
                                                        </td>
                                                        <td align="center">
                                                            <b>{{$item->author}}</b>
                                                        </td>
                                                        <td>{{strip_tags($item->purpose)}}</td>
                                                        <td align="center">
                                                            @actionStart('powo_validation', 'delete')
                                                            <button class="btn btn-xs btn-danger btn-icon" onclick="button_delete_type('{{$item->id}}')"><i class="fa fa-trash"></i></button>
                                                            @actionEnd
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="usedcode" role="tabpanel" aria-labelledby="wo-type">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <a href="#" class="text-black-50">Used Validation Code</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <table class="table table-responsive-xl table-striped display">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Validation Code</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Paper Published</th>
                                            <th class="text-center">Time Published</th>
                                            <th class="text-center">Published By</th>
                                            <th class="text-center">Issued By</th>
                                            <th class="text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @actionStart('powo_validation', 'read')
                                        @php $num = 1; @endphp
                                        @foreach($papers as $key => $item)
                                            @if($item->issued_date != null)
                                                <tr>
                                                    <td align="center">{{$num++}}</td>
                                                    <td align="center">
                                                        <button data-clipboard-text="{{$item->kode}}" data-toggle="tooltip" data-trigger="focus" data-delay='{"show":"10", "hide":"100"}' title="coppied" class="btn-copy btn btn-success label label-inline label-success">{{$item->kode}}</button>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{strtoupper($item->nama_paper)}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{strtoupper($item->paper_num)}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{date('d F Y', strtotime($item->issued_date))}}</b>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{$item->issued_by}}</b>
                                                    </td>
                                                    <td align="center">{{strip_tags($item->author)}}</td>
                                                    <td align="center">
                                                        <button class="btn btn-xs btn-danger btn-icon" onclick="button_delete_type('{{$item->id}}')"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @actionEnd
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addType" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validation Code <span id="modal-label"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.powoval.addCode')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type" class="form-control select2" id="" required>
                                            <option value="">Select Type</option>
                                            <option value="po">PO (Purchase Order)</option>
                                            <option value="wo">WO (Work Order)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Purpose</label>
                                    <div class="col-md-8">
                                        <textarea name="purpose" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Quantity</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="qty" value="1" min="1" required>
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
    <div class="modal fade" id="editCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.powotypes.updateType')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="type_name" id="type-name" placeholder="Type Name" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_type" id="id-type">
                        <input type="hidden" name="type" id="type">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        @actionStart('powo_validation', 'update')
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                        @actionEnd
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateType" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{route('ha.powotypes.changeType')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4">Type</label>
                                    <div class="col-md-8">
                                        <select name="type_" id="type-update" class="form-control select2" required></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_data" id="id-data">
                        <input type="hidden" name="type_data" id="type-data">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        @actionStart('powo_validation', 'update')
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                        @actionEnd
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
    <script>

        function button_add_type(){
            $("#addType").modal('show')
        }

        function button_delete_type(x){
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
                        url : "{{URL::route('ha.powoval.delete')}}/" + x,
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
            $("select.select2").select2({
                width: "100%"
            })
            new ClipboardJS('.btn-copy');

            $("#btn-copy").click(function(){
                var txt = document.getElementById('shareFile')
                txt.select()
                txt.setSelectionRange(0, 99999)
                document.execCommand('copy')

                var content = {}

                content.message = "copied"
                var notify = $.notify(content, {
                    type: "success",
                    allow_dismiss: true,
                    newest_on_top: false,
                    mouse_over:  false,
                    showProgressbar:  false,
                    spacing: 10,
                    timer: 500,
                    placement: {
                        from: "bottom",
                        align: "center"
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 500,
                    z_index: 10000,
                    animate: {
                        enter: 'animate__animated animate__bounce',
                        exit: 'animate__animated animate__bounce'
                    }

                })
            })


            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
