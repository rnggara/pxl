@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Software</h3>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" onclick="file_allowed()" class="btn btn-primary" data-toggle="modal" data-target="#addSoftware"><i class="fa fa-plus"></i>Add Software</button>
                </div>
                {{--end button--}}
            </div>
        </div>
        <div class="card-body">
            {{--table software--}}
            <div class="row">
                <div class="col-12">
                    
                    <table class="table table-bordered table-hover display font-size-sm data-table" style="margin-top : 13px !important; width: 100%; ">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Software Name</th>
                                <th class="text-center">Publisher</th>
                                <th class="text-center">Version</th>
                                <th class="text-center">Year</th>
                                <th class="text-center">Buy Date</th>
                                <th class="text-center">License Key</th>
                                <th class="text-center">Price</th>
                                <th class="text-center"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($software as $key => $item)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td align="center">
                                    <p>{{$item->software_name}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_publisher}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_version}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_year}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_buy_date}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_license_key}}</p>
                                </td>
                                <td align="center">
                                    <p>{{$item->software_price}}</p>
                                </td>
                                <td align="center">
                                    <button type="button" onclick="file_allowed()" class="btn btn-primary" data-toggle="modal" data-target="#editSoftware{{$item->id}}"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" onclick="button_delete('{{$item->id}}')"><i class="fa fa-trash"></i></button>
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
    <div class="modal fade" id="addSoftware" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addSoftware" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Software</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('software.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Software Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Publisher</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_publisher" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Version</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_version" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Year</label>
                                    <div class="col-md-8">
                                        <select name="software_year">
                                            <option selected="selected">Software Year</option>
                                            <?php
                                            for($i=date('Y'); $i>=date('Y')-32; $i-=1){
                                            echo"<option value='$i'> $i </option>";
                                            }
                                            ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Buy Date</label>
                                    <div class="col-md-8">
                                        <input type="datetime-local" id="software_buy_date" name="software_buy_date" value="{{ date("Y-m-d") }}" cols="30" rows="10" required>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">License Key</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_license_key" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Price</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="software_price" name="software_price" required>
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

    {{--modal edit--}}
    <div class="modal fade" id="editSoftware{{$item->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Software</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('software.edit')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Software Name</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_name" value="{{$item->software_name}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Publisher</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_publisher" value="{{$item->software_publisher}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Version</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_version" value="{{$item->software_version}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Year</label>
                                    <div class="col-md-8">
                                        <select name="software_year">
                                            <option selected="selected">Software Year</option>
                                            <?php
                                            for($i=date('Y'); $i>=date('Y')-32; $i-=1){
                                            echo"<option value='$i'> $i </option>";
                                            }
                                            ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Buy Date</label>
                                    <div class="col-md-8">
                                        <input type="datetime-local" id="software_buy_date" name="software_buy_date" value="{{ date("Y-m-d") }}" cols="30" rows="10" value="{{$item->software_buy_date}}" required>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">License Key</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="software_license_key" value="{{$item->software_license_key}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-4">Price</label>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" id="software_price" name="software_price" value="{{$item->software_price}}" required>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')

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
                    url: "{{route('software.delete')}}/"+x,
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
        $('.data-table').DataTable({
            pageLength: 50
        })
        

    })
    </script>
@endsection
