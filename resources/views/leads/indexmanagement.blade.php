@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <a href="#" class="text-black-50">Leads Management</a>
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
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">Leads Name</th>
                        <th nowrap="nowrap" class="text-center">Client</th>
                        <th nowrap="nowrap" class="text-center">PIC</th>
                        <th nowrap="nowrap" class="text-center">Progress</th>
                        @foreach($progress as $key => $val)
                            <th nowrap="nowrap" class="text-center">{{$val['title']}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <?php $num = 0;  ?>
                    @foreach($leads as $key => $lead)
                        @if(\Illuminate\Support\Facades\Auth::user()->username == $lead->created_by || \Illuminate\Support\Facades\Auth::user()->username == "admin")
                            <tr>
                                <td align="center">{{$num + 1}}<?php $num++ ?></td>
                                <td><a href="{{route('leads.view', $lead->id)}}" class="text-hover-danger">{{$lead->leads_name}}</a></td>
                                <td align="center">{{$data_client['client_name'][$lead->id_client]}}</td>
                                <td align="center">
                                    {{$lead->created_by}}
                                </td>
                                <td align="center">
                                    <div>
                                        <span class="label label-inline label-secondary">{{number_format($lead->progress)."%"}}</span>
                                    </div>
                                    @php
                                        $notNull = 0;
                                    @endphp
                                    @foreach($progress as $keyProgress => $valProgress)
                                        @if($lead[$keyProgress] == null)
                                            <div class="mt-2">
                                                <span class="label label-inline label-info">{{$valProgress['message']}}</span>
                                            </div>
                                            @break
                                        @else
                                            @php
                                                $notNull += 1;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if($notNull == count($progress))
                                        <div class="mt-2">
                                            <span class="label label-inline label-success">Leads completed</span>
                                        </div>
                                    @endif
                                </td>
                                @foreach($progress as $keyProgress => $valProgress)
                                    <td align="center">
                                        @if($lead[$keyProgress] == null)
                                            <span class="label label-inline label-danger">N/A</span>
                                        @else
                                            <a href="{{route('download', $lead[$keyProgress])}}" class="btn btn-xs btn-dark btn-icon"><i class="fa fa-download"></i></a>
                                        @endif
                                    </td>
                                @endforeach
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
@endsection
@section('custom_script')
    <script>


        $(document).ready(function () {
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
