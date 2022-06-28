@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Notification Rules</h3>
            </div>
            <div class="card-toolbar">
                <button type="button" data-toggle="modal" data-target="#addRules" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Add Rules</button>
            </div>
        </div>
        <hr>
        <div class="card-body">
            <table class="table table-bordered table-hover display font-size-sm">
                <thead>
                <tr>
                    <th nowrap="nowrap" class="text-center" width=10%">#</th>
                    <th nowrap="nowrap" class="text-center" width="50%">Notification Code</th>
                    <th nowrap="nowrap" class="text-left" width="20%">Description</th>
                    <th nowrap="nowrap" class="text-center" width="20%"></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $key => $item)
                        <tr>
                            <td align="center">{{$key + 1}}</td>
                            <td align="center">{{$item->notification_code}}</td>
                            <td>{{strip_tags($item->description)}}</td>
                            <td align="center">
                                <button class="btn btn-xs btn-icon btn-primary" data-toggle="modal" data-target="#editRules{{$item->id}}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-icon btn-danger" onclick="button_delete({{$item->id}})"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <div class="modal fade" id="editRules{{$item->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Rule</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{URL::route('other.notif.update')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h3>Basic Info</h3>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Code</label>
                                                        <div class="col-md-9" >
                                                            <input type="text" class="form-control bg-success text-white" readonly value="{{$item->notification_code}}" name="code" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Description</label>
                                                        <div class="col-md-9">
                                                            <textarea name="description" class="form-control" id="" cols="30" rows="10">{{strip_tags($item->description)}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label text-right">Receiver</label>
                                                        <div class="col-md-9">
                                                            <?php $receiver = json_decode($item->receivers) ?>
                                                            <select name="receiver[]" class="form-control select2" multiple id="" required>
                                                                <option value="">Select Notification Receivers</option>
                                                                @foreach($positions as $position)
                                                                    <option value="{{$position->id}}" {{(in_array($position->id, $receiver) ? "SELECTED" : "")}}>{{$position->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="id_notif" value="{{$item->id}}">
                                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                            <button type="submit" name="submit" id="btn-submit" class="btn btn-primary font-weight-bold">
                                                <i class="fa fa-check"></i>
                                                Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="modal fade" id="addRules" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Rule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form method="post" action="{{URL::route('other.notif.add')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Basic Info</h3>
                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right">Code</label>
                                        <div class="col-md-9" id="code-div">
                                            <input type="text" class="form-control" placeholder="Code" name="code" id="code-text" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right">Description</label>
                                        <div class="col-md-9">
                                            <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right">Receiver</label>
                                        <div class="col-md-9">
                                            <select name="receiver[]" class="form-control select2" multiple id="" required>
                                                <option value="">Select Notification Receivers</option>
                                                @foreach($positions as $position)
                                                    <option value="{{$position->id}}">{{$position->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" id="btn-submit" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function button_delete(x){
            Swal.fire({
                title: "Reject",
                text: "Are you sure you want to delete?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{URL::route('other.notif.delete')}}/" + x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0) {
                                location.reload()
                            } else {
                                Swal.fire({
                                    title: "Error Occured",
                                    text: "Please contact your administrator",
                                    icon: "error"
                                })
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(function () {

            $("#code-text").change(function(){
                if (this.value != ""){
                    $("#code-div").addClass("spinner spinner-success spinner-right")
                    $("#code-text").removeClass("bg-success bg-danger text-white placeholder-white")
                    $.ajax({
                        url: "{{route('other.notif.check_code')}}",
                        type: "post",
                        dataType: "json",
                        cache: false,
                        data: {
                            "_token" : "{{csrf_token()}}",
                            'code' : this.value,
                        },
                        success: function(response){
                            console.log(response.count)
                            $("#code-div").removeClass("spinner spinner-success spinner-right")
                            if (response.count == 0){
                                $("#code-text").addClass("bg-success text-white placeholder-white")
                                $("#btn-submit").attr("disabled", false)
                            } else {
                                $("#code-text").addClass("bg-danger text-white placeholder-white")
                                $("#btn-submit").attr("disabled", true)
                            }
                        }
                    })
                } else {
                    $("#code-div").removeClass("spinner spinner-success spinner-right")
                    $("#code-text").removeClass("bg-success bg-danger text-white placeholder-white")
                    $("#btn-submit").attr("disabled", false)
                }

            })

            $("select.select2").select2({
                width: "100%"
            })
            $('.display').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection
