@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Test Equipment Item Details Revision</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('te.testeq.index')}}" class="btn btn-success mr-2"><i class="fa fa-arrow-circle-left"></i> </a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm te_instrument_update" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Item Name</th>
                        <th class="text-center">Picture</th>
                        <th class="text-center">Update Request</th>
                        <th class="text-center">Item Series</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">UoM</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($revisions as $key => $value)
                        <tr>
                            <td class="text-center">{{($key+1)}}</td>
                            <td><a href="{{route('te.testeq.revision_detail',['id' => $value->id])}}" class="btn btn-link btn-xs"><i class="fa fa-search"></i>{{$value->name}}</a></td>
                            <td class="text-center">
                                @if($value->picture == ''|| $value->picture == null)
                                    No Picture
                                @else
                                    <img src="{{str_replace('public','public_html',asset('/media/te_testeq_update/')).'/'.$value->picture}}" class="img-responsive center-block" height="15%">
                                @endif
                            </td>
                            <td class="text-center">{{$value->created_by}}</td>
                            <td class="text-center">{{$value->item_series}}</td>
                            <td class="text-center">{{$value->item_code}}</td>
                            <td class="text-center">{{$value->uom}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('table.te_instrument_update').DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },

            })
        });
    </script>
@endsection
