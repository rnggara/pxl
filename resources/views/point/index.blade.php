@extends('layouts.template')
@section('content')
    <style type="text/css">
        [class^='select2'] {
            border: 0px;
            border-bottom: 1px;
            border-bottom-color: #0c0e1a;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Point Report</a>
            </div>
            <div class="card-toolbar">
                @actionStart('point', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPoint"><i class="fa fa-plus"></i>Add Point Report</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="alert alert-primary">
                        Report point results based on data in the report that is on the menu Miss & Near Miss.
                    </div>
                </div>
            </div>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">Informer</th>
                        <th nowrap="nowrap" class="text-center">Point Plus</th>
                        <th nowrap="nowrap" class="text-center">Defendant</th>
                        <th nowrap="nowrap" class="text-center">Point Minus</th>
                        <th nowrap="nowrap" class="text-center">Date of Case</th>
                        <th nowrap="nowrap" class="text-center">Explaination</th>
{{--                        <th nowrap="nowrap" class="text-center">HRD Approval</th>--}}
                        <th nowrap="nowrap" class="text-center">BoD Approval</th>
                        <th nowrap="nowrap" class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('bonus', 'read')
                        @foreach($points as $key => $value)
                            <tr>
                                <td align="center">{{$key+1}}</td>
                                <td align="center">{{(isset($emp_data[$value->id_p]) ? $emp_data[$value->id_p]->emp_name : "")}}</td>
                                <td align="center">{{$value->gp}}</td>
                                <td align="center">{{(isset($emp_data[$value->id_t]) ? $emp_data[$value->id_t]->emp_name : "")}}</td>
                                <td align="center">-{{$value->bp}}</td>
                                <td align="center">{{date('d F Y', strtotime($value->date_of_case))}}</td>
                                <td>{{$value->keterangan}}</td>
                                <td align="center">
                                    @if($value->bod_approved_at == null)
                                        <button class="btn btn-xs btn-primary" onclick="approve('{{$key}}', 'bod')"><i class="fa fa-edit"></i> Approve</button>
                                    @else
                                        <span class="label label-success label-inline "><i class="fa fa-check text-white font-size-sm"></i>&nbsp; Approved</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @actionStart('bonus', 'delete')
                                    <button type="button" class="btn btn-xs btn-icon btn-danger" onclick="button_delete('{{$value->id}}')"><i class="fa fa-trash"></i></button>
                                    @actionEnd
                                </td>
                            </tr>
                        @endforeach
                        @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addPoint" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Point Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('point.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6" id="form-leads">
                                <h4>Informer</h4><hr>
                                <div class="form-group">
                                    <label>Informer Name</label>
                                    <select name="informer" class="form-control select2" id="">
                                        <option value="">EMPTY</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->emp_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Point Plus</label>
                                    <input type="number" class="form-control" min="0" name="gp">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Defendant</h4><hr>
                                <div class="form-group">
                                    <label>Defendant Name</label>
                                    <select name="defendant" class="form-control select2" id="">
                                        <option value="">EMPTY</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->emp_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Point Minus</label>
                                    <input type="number" class="form-control" min="0" name="bp">
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Date of Case</label>
                                    <input type="date" class="form-control" name="dateofcase">
                                </div>
                                <div class="form-group">
                                    <label>Explaination</label>
                                    <textarea name="explain" class="form-control" id="" cols="30" rows="10"></textarea>
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
    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('point.approve')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="form-leads">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">Informer</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="informer" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-right text-success col-md-6">+ Point</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="gp" min="0" id="gp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">Defendant</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="defendant" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label text-right text-danger col-md-6">- Point</label>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="bp" min="0" id="bp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">Notes</label>
                                    <div class="col-md-9">
                                        <textarea name="notes" id="notes" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="id_point" name="id_point">
                        <input type="hidden" id="type" name="type_appr">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="json_emp" value="{{json_encode($emp_data)}}">
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
                        url : "{{URL::route('point.delete')}}/" + x,
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

        function approve(x, y){
            var json_emp = $("#json_emp").val()
            var emp = JSON.parse(json_emp)
            var json_point = '{{json_encode($points)}}'.replaceAll('&quot;', "\"")
            var point = JSON.parse(json_point)
            console.log(point[x])
            $("#modalApprove").modal('show')
            if (emp[point[x]['id_p']] != undefined){
                $("#informer").val(emp[point[x]['id_p']]['emp_name'])
                $("#gp").val(point[x]['gp'])
                $("#gp").attr('readonly', false)
            } else {
                $("#informer").val('')
                $("#gp").val('')
                $("#gp").attr('readonly', true)
            }

            if (emp[point[x]['id_t']] != undefined){
                $("#defendant").val(emp[point[x]['id_t']]['emp_name'])
                $("#bp").attr('readonly', false)
                $("#bp").val(point[x]['bp'])
            } else {
                $("#bp").attr('readonly', true)
                $("#defendant").val('')
                $("#bp").val('')
                console.log('here')
            }
            $("#notes").val(point[x]['keterangan'])
            $("#id_point").val(point[x]['id'])
            $("#type").val(y)
        }

        $(document).ready(function () {
            $("select.select2").select2({
                width: "100%",
            })

            $("#modalApprove").on('hidden.bs.modal', function () {
                console.log('modal closed')
                $("#informer").val('')
                $("#gp").val('')
                $("#defendant").val('')
                $("#bp").val('')
                $("#id_point").val('')
                $("#notes").val('')
                $("#type").val('')
            })

            $('.display').DataTable({
                responsive: {
                    details: false
                },
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        })

    </script>
@endsection
