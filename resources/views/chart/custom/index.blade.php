@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Custom Chart</a>
            </div>
            @actionStart('custom_charts', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addChart"><i class="fa fa-plus"></i>Add Category</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th nowrap="nowrap" class="text-left">Name</th>
                                <th nowrap="nowrap" class="text-center">Project</th>
                                <th nowrap="nowrap" class="text-center">From</th>
                                <th nowrap="nowrap" class="text-center">To</th>
                                <th nowrap="nowrap" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('custom_charts', 'read')
                            @foreach($charts as $i => $chart)
                                <tr>
                                    <td align="center">{{$i + 1}}</td>
                                    <td align="center">
                                        <a href="{{route('chart.custom.view', $chart->id)}}" class="label label-inline label-md label-success">{{$chart->name}}</a>
                                    </td>
                                    <td align="center">
                                        @if($chart->project == 0)
                                            <span class="label label-inline label-md label-light-primary">ALL</span>
                                        @else
                                            <span class="label label-inline label-md label-light-primary">{{$data_project[$chart->project]->prj_name}}</span>
                                        @endif
                                    </td>
                                    <td align="center">
                                        {{date("d F Y", strtotime($chart->date_from))}}
                                    </td>
                                    <td align="center">
                                        {{date("d F Y", strtotime($chart->date_to))}}
                                    </td>
                                    <td align="center">
                                        <button type="button" onclick="editChart('{{$chart->id}}')" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                                        <button type="button" onclick="deleteChart('{{$chart->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addChart" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('chart.custom.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="date" class="form-control" name="date_from" required="">
                                </div>
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="date" class="form-control" name="date_to" required="">
                                </div>
                                <div class="form-group">
                                    <label>Project</label>
                                    <select class="form-control" name="project" required="">
                                        <option value="0">All</option>
                                        @foreach($project as $item)
                                            <option value="{{$item->id}}">{{$item->prj_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <?php for ($i=0; $i < 5; $i++) { ?>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Line <?= $i+1 ?></label>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Stack</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control" name="type[<?= $i+1 ?>]" <?= ($i == 0) ? "required" : "" ?>>
                                                <option value="">-CHOOSE-</option>
                                                @foreach($arr as $item)
                                                    <option value="{{$item}}">{{ucwords(str_replace("_", " ", $item))}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="control-label" style="margin-top: 10px">
                                                <input type="checkbox" name="stack[<?= $i+1 ?>]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
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
    <div class="modal fade" id="editChart" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="edit-chart">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        function editChart(x){
            $("#editChart").modal('show')
            $.ajax({
                url: "{{route('chart.custom.find')}}/"+x,
                type: "GET",
                cache: false,
                success: function(response){
                    $("#edit-chart").append(response)
                }
            })
        }

        function deleteChart(x){
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
                    location.href = "{{route('chart.custom.delete')}}/" + x
                }
            })
        }

        $(document).ready(function () {
            $("#editChart").on('hidden.bs.modal', function () {
                $("#edit-chart").html("")
            })
            $("select.select2").select2({
                width: "100%"
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
