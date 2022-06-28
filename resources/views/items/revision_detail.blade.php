@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Items Revision</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{URL::route('items.revision')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <form method="post" action="{{URL::route('items.revision_update')}}" id="formsubmit">
                @csrf
                <h4>Basic Information</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Item Name</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->name}}
                            @if($item->name != $itemsup->name)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->name}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Item Code</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->item_code}}
                            @if($item->item_code != $itemsup->item_code)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->item_code}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Brand Name</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->item_series}}
                            @if($item->item_series != $itemsup->item_series)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->item_series}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Serial Number</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->serial_number}}
                            @if($item->serial_number != $itemsup->serial_number)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->serial_number}}
                            @endif
                        </label>
                    </div>
                </div>
                <br>
                <h4>Detail Info</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Type</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{($item->type_id == 1) ? "Consumable" : "Non Consumable"}}
                            @if($item->type_id != $itemsup->type_id)
                                <i class="fa fa-arrow-right"></i>
                                {{($itemsup->type_id == 1) ? "Consumable" : "Non Consumable"}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Minimal Stock</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->minimal_stock}}
                            @if($item->minimal_stock != $itemsup->minimal_stock)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->minimal_stock}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">UoM</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->uom}}
                            @if($item->uom != $itemsup->uom)
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->uom}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Picture</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->picture}}
                            @if($item->picture != $itemsup->picture && $itemsup->picture != "" && !empty($itemsup->picture))
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->picture}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Notes</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->notes}}
                            @if($item->notes != $itemsup->notes && $itemsup->notes != "" && !empty($itemsup->notes))
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->notes}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Specification</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$item->specification}}
                            @if($item->specification != $itemsup->specification && $itemsup->specification != "" && !empty($itemsup->specification))
                                <i class="fa fa-arrow-right"></i>
                                {{$itemsup->specification}}
                            @endif
                        </label>
                    </div>
                </div>
                <hr>
                <input type="hidden" name="item_id" value="{{base64_encode(rand(100, 999)."-".$itemsup->id)}}">
                <button type="submit" id="btn-hide" name="submit" class="btn btn-primary font-weight-bold">
                    <i class="fa fa-check"></i>
                    Update</button>
                <button type="button" id="btn-submit" name="submit" class="btn btn-primary font-weight-bold">
                    <i class="fa fa-check"></i>
                    Update</button>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("#btn-hide").hide()
            $("#btn-submit").click(function(){
                Swal.fire({
                    title: "Update data",
                    text: "Are you sure you want to update this data?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Update",
                    cancelButtonText: "Cancel",
                    reverseButtons: true,
                }).then(function(result){
                    if(result.value){
                        $("#btn-hide").click()
                    }
                })
            })
        })
    </script>
@endsection
