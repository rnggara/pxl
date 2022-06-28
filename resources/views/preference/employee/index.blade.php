@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Employee Variables</h3>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addParameter"><i class="fa fa-plus"></i>Add New Parameter</button>
                </div>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover display font-size-sm data-table" style="margin-top : 13px !important; width: 100%; ">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Parameter Name</th>
                                <th class="text-center">Parameter Type</th>
                                <th class="text-center">Parameter Length</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employeevar as $key => $item)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">
                                    <p>{{$item->parameter_name}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->parameter_type}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->parameter_length}}</p>
                                </td>
                                <td align="center">
                                    <a href="{{ route('employeevar.edit') }}" class="btn btn-primary btn-xs btn-icon fa fa-edit" onclick="file_allowed()" data-toggle="modal" data-target="#editParameter"></a>
                                    <button class="btn btn-xs btn-icon btn-danger" onclick="button_delete('{{$item->id}}')"><i class="fa fa-trash"></i></button>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal add --}}
    <div class="modal fade" id="addParameter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addParameter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <form method="post" action="{{route('employeevar.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" required name="parameter_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Type</label>
                                    <div class="col-md-8">
                                        <select class="form-control h-auto" align="right" type="text" name="parameter_type" required>
                                            <option value="" text-faded>Please Select</option>
                                            <option value="String">String</option>
                                            <option value="Integer">Integer</option>
                                            <option value="Decimal">Decimal</option>
                                            <option value="Date">Date</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Length</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="parameter_length" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editParameter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editParameter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Parameter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <form method="post" action="{{route('employeevar.edit')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="parameter_name" name="parameter_name" value="{{ old('parameter_name') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Type</label>
                                    <div class="col-md-8">
                                        <select class="form-control h-auto form-control-solid py-4 px-8" align="right" style="width:300px;" type="text" name="parameter_type" value="{{ old('parameter_type') }}" required>
                                            <option value="" text-faded>Please Select</option>
                                            <option value="String">String</option>
                                            <option value="Integer">Integer</option>
                                            <option value="Decimal">Decimal</option>
                                            <option value="Date">Date</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Parameter Length</label>
                                    <div class="col-md-8">
                                        <input type="text" id="parameter_length" name="parameter_length" value="{{ old('parameter_length')  }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script type="text/javascript">

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
                        url: "{{route('employeevar.delete')}}/"+x,
                        type: "get",
                        dataType: "json",
                        success: function (response) {
                            if (response.delete === 1){
                                location.reload()
                            } else {
                                Swal.fire('Error occured', "Please contact your system administration", 'error')
                            }
                        }
                    })
                }
            })
        }


        $(document).ready(function(){
            $('.data-table').DataTable()

            var param_length = $("#addParameter input[name=parameter_length]")

            $("#addParameter select[name=parameter_type]").change(function(){
                var val = $(this).val()
                param_length.val('')
                if(val != 'Date'){
                    param_length.prop('readonly', false)
                    param_length.prop('required', true)
                    if(val == "Decimal"){
                        param_length.attr('type', 'text')
                        param_length.number(true, 2, ',', '')
                    } else {
                        param_length.attr('type', 'number')
                    }
                } else {
                    param_length.prop('readonly', true)
                    param_length.prop('required', false)
                }
            })

            // event read only input text
            // $('#name').attr('readOnly', 'readOnly');

        })
    </script>
@endsection
