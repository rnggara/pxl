@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>Add SOP Category</h3>
            </div>

            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">

                    <a href="{{route('sop.index')}}" class="btn btn-success btn-icon"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable1" style="margin-top: 13px !important">

                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Name Category</th>
                    <th class="text-center"></th>
                </tr>

                <tr>
                    <form method="post" action="{{route('sop.saveCategory')}}">
                        @csrf
                    <th class="text-center"></th>
                    <th class="text-left">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Add Category">
                    </th>
                    <th class="text-center">
                        <button type="submit" name="New" id="New" value="Save" class="btn btn-success btn-block" title="Add Category"><i class="fa fa-plus"></i></button>
                    </th>
                    </form>
                </tr>

                </thead>
                <tbody>
                @foreach($categories as $key => $value)
                    <tr>
                        <td class="text-center">{{($key+1)}}</td>
                        <td class="text-left">{{($value->name_category != null)?$value->name_category:'-'}}</td>
                        <td class="text-center">
                            <a href="{{route('sop.category_del',['id'=>$value->id])}}" class="btn btn-secondary btn-sm btn-icon" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('custom_script')

    <script>
        $(document).ready(function (){
            $('#kt_datatable1').DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
