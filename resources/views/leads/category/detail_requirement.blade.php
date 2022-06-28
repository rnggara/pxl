@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Detail Requirement</a>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('leads.cat.get',['id_category' => $detail->id_category])}}" class="btn btn-secondary btn-xs" ><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </div>&nbsp; &nbsp;
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add"><i class="fa fa-plus"></i>Add New</button>
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
                        <th width="5%">#</th>
                        <th nowrap="nowrap" class="text-left" >Name</th>
                        <th nowrap="nowrap" class="text-center" width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        /** @var TYPE_NAME $requirements */
                        $numItems = count($requirements) - 1;
                        $i = 0;
                    @endphp
                    @foreach($requirements as $key => $value)
                        <tr>
                            @php
                                /** @var TYPE_NAME $value */
                                if ($value->name == 'ud'){
                                    $name = 'Upload Document';
                                } elseif ($value->name == 'ms'){
                                    $name = 'Meeting Schedule';
                                } elseif ($value->name == 'ol'){
                                    $name = 'Outbox List';
                                } elseif ($value->name == 'tt'){
                                    $name = 'Time Table';
                                } elseif ($value->name == 'ba'){
                                    $name = 'Berita Acara';
                                } elseif ($value->name == 'pe'){
                                    $name = 'Pernyataan Efektif dari OJK';
                                } elseif ($value->name == 'su'){
                                    $name = 'Summary';
                                } else {
                                    $name = "";
                                }
                            @endphp
                            <td>{{($value->sequence)}}</td>
                            <td>
                                &nbsp;{{$name}}
                                <br>
                                <br>
                                &nbsp;&nbsp;
                                @if(count($requirements) > 1)
                                    @if($key == 0)
                                        <a href="{{route('leads.cat.sort',['param' => 'req','arrow' => 'down','id_category'=>$value->id_detail,'id'=>$value->id,'sequence' =>$value->sequence])}}" class="btn btn-icon btn-xs btn-danger"><i class="fa fa-arrow-down"></i></a>
                                    @elseif(++$i === $numItems)
                                        <a href="{{route('leads.cat.sort',['param' => 'req','arrow' => 'up','id_category'=>$value->id_detail,'id'=>$value->id,'sequence' =>$value->sequence])}}" class="btn btn-icon btn-xs btn-success"><i class="fa fa-arrow-up"></i></a>
                                    @else
                                        <a href="{{route('leads.cat.sort',['param' => 'req','arrow' => 'down','id_category'=>$value->id_detail,'id'=>$value->id,'sequence' =>$value->sequence])}}" class="btn btn-icon btn-xs btn-danger"><i class="fa fa-arrow-down"></i></a> &nbsp;
                                        <a href="{{route('leads.cat.sort',['param' => 'req','arrow' => 'up','id_category'=>$value->id_detail,'id'=>$value->id,'sequence' =>$value->sequence])}}" class="btn btn-icon btn-xs btn-success"><i class="fa fa-arrow-up"></i></a>
                                    @endif
                                @endif

                            </td>

                            <td class="text-center"><a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Requirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('leads.requirement.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="form-leads">

                                <div class="form-group">
                                    <label>Requirement Name</label>
                                    <select class="form-control" name="name" required>
                                        <option value='ud'>Upload Document</option>
                                        <option value='ms'>Meeting Schedule</option>
                                        <option value='ol'>Outbox List</option>
                                        <option value='tt'>Time Table</option>
                                        <option value='ba'>Berita Acara</option>
                                        <option value='pe'>Pernyataan Efektif dari OJK</option>
                                        <option value='su'>Summary</option>
                                    </select>
                                    <input type="hidden" name="id_detail" value="{{$detail->id}}" id="">
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

@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
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
