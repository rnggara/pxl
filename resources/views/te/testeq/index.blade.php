@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Test Equipment Item Details</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('te.testeq.revision')}}" class="btn btn-warning mr-2"><span class="label label-light-danger mr-2">{{$revisions}}</span> Revision</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-check"></i>Add Item</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm te_instrument" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Item Name</th>
                        <th class="text-center">Picture</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">UoM</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>

                </table>
            </div>
        </div>
        <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <form method="post" action="{{route('te.testeq.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <h4>Basic Information</h4>
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Item Code</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Item Code" name="item_code" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Item Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Item name" name="item_name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Item Series</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Item Series" name="item_series" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Serial Number</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Serial Number" name="serial_number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Category</label>
                                <div class="col-md-6">
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">- Select Category -</option>
                                        <option value="1">Main Equipment</option>
                                        <option value="2">Sparepart</option>

                                    </select>
                                </div>
                            </div>

                            <br>
                            <h4>Detail Info</h4>
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">UoM</label>
                                <div class="col-md-6">
                                    <select name="uom" id="uom" class="form-control" required>
                                        <option value="">- Select UOM -</option>
                                        @foreach($uom as $v)
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Picture</label>
                                <div class="col-md-6">
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="printed_logo">
                                            <div class="image-input-wrapper"></div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="picture" id="p_logo_add" accept=".png, .jpg, .jpeg" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Data Sheet</label>
                                <div class="col-md-6">
                                    <input type="file" name="data_sheet" class="custom-file" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Notes</label>
                                <div class="col-md-6">
                                    <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label text-right">Specification</label>
                                <div class="col-md-6">
                                    <textarea name="specification" class="form-control" id="" cols="30" rows="10"></textarea>
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
    </div>
@endsection
@section('custom_script')
<script type="text/javascript">
    $(document).ready(function(){
        $('table.te_instrument').DataTable({
            fixedHeader: true,
            fixedHeader: {
                headerOffset: 90
            },
            'ajax': '{{route('te.testeq.getInstrumentList')}}',
            'type': 'GET',
            dataSrc: 'responseData',
            'columns' :[
                { "data": "no" },
                { "data": "item_name" },
                { "data": "picture" },
                { "data": "category" },
                { "data": "code" },
                { "data": "uom" },
                { "data": "action" },
            ],
            'columnDefs': [
                {
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "className": "text-center",
                },
                {
                    "targets": 4,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-center",
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
            ],
        })
    });
</script>
@endsection
