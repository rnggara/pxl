@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Price List</h3><br>

            </div>

        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#all">
                        <span class="nav-icon">
                            <i class="flaticon-folder-1"></i>
                        </span>
                        <span class="nav-text">Item PO</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#cost" aria-controls="profile">
                        <span class="nav-icon">
                            <i class="flaticon-folder-3"></i>
                        </span>
                        <span class="nav-text">Job Desc WO</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">

                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Item Code</th>
                                <th class="text-left">Item Name</th>
                                <th class="text-left">Category</th>
                                <th class="text-right">UoM</th>
                            </tr>
                            </thead>
                            <tbody>
                            @actionStart('price_list','read')
                            @foreach($pricelists as $key =>$val)
                                <div class="modal fade" id="view{{$key}}" tabindex="-1" role="dialog" aria-labelledby="view{{$key}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel{{$key}}">{{$val->item_id}} - {{$val->itemName}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="{{route('policy.store')}}" >
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label>PO# : </label>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Quantity : </label>
                                                        </div>
                                                        <div class="form col-md-4">
                                                            @if(isset($list_po[$val->item_id]))
                                                                @for($i=0;$i<count($list_po[$val->item_id]); $i++)
                                                                    <div class="form-group">
                                                                        <label></label>
                                                                        <input type="text" class="form-control" readonly name="topic" value="{{$list_po[$val->item_id][$i]}}"/>
                                                                    </div>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                        <div class="form col-md-4">
                                                            @if(isset($list_po[$val->item_id]))
                                                                @for($i=0;$i<count($list_po[$val->item_id]); $i++)
                                                                    <div class="form-group">
                                                                        <label></label>
                                                                        <input type="text" class="form-control" readonly name="topic" value="{{$list_qty[$val->item_id][$i]}}"/>
                                                                    </div>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                        <div class="form col-md-4">
                                                            @if(isset($list_po[$val->item_id]))
                                                                @for($i=0;$i<count($price[$val->item_id]); $i++)
                                                                    <div class="form-group">
                                                                        <label></label>
                                                                        <input type="text" class="form-control" readonly name="topic" value="{{number_format($price[$val->item_id][$i], 2)}}"/>
                                                                    </div>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <td class="text-center">{{($key+1)}}</td>
                                    <td class="text-left"><button type="button" class="btn btn-xs btn-link" data-toggle="modal" data-target="#view{{$key}}"><i class="fa fa-search"></i>{{$val->item_id}}</button> </td>
                                    <td class="text-left">{{$val->itemName}}</td>
                                    <td class="text-left">{{$val->catName}}</td>
                                    <td class="text-center">{{$val->itemUom}}</td>
                                </tr>
                            @endforeach
                            @actionEnd
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="cost" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Paper Number#</th>
                                <th class="text-left">Job Description</th>
                                <th class="text-left">Supplier</th>
                                <th class="text-left">Project</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Total Price</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
