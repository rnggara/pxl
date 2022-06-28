@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Instrumentation Item Details Revision</h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('te.instrument.revision')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-circle-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('te.revision_approve')}}" id="formsubmit">
                @csrf
                <h4>Basic Information</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Item Code</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            <b>{{$old->item_code}}</b>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Item Name</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$old->name}}
                            @if($new->name != $old->name)
                                <i class="fa fa-arrow-right"></i>
                                {{$new->name}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Item Series</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$old->item_series}}
                            @if($new->item_series != $old->item_series)
                                <i class="fa fa-arrow-right"></i>
                                {{$new->item_series}}
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Serial Number</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$old->serial_number}}
                            @if($new->serial_number != $old->serial_number)
                                <i class="fa fa-arrow-right"></i>
                                {{$new->serial_number}}
                            @endif
                        </label>
                    </div>
                </div>

                <br>
                <h4>Detail Info</h4>
                <hr>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">UoM</label>
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <label for="" class="col-form-label">
                                :
                                {{$old->uom}}
                                @if($new->uom != $old->uom)
                                    <i class="fa fa-arrow-right"></i>
                                    {{$new->uom}}
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Picture</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            @if($old->picture == null || $old->picture = '')
                                No Picture
                            @else
                                <img src="{{str_replace('public','public_html',asset('/media/te_instrument/')).'/'.$old->picture}}" class="img-responsive center-block" height="15%">
                            @endif

                            @if($new->picture != $old->picture)
                                <i class="fa fa-arrow-right"></i>
                                <img src="{{str_replace('public','public_html',asset('/media/te_instrument_update/')).'/'.$new->picture}}" class="img-responsive center-block" height="15%">
                            @endif
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Notes</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$old->notes}}
                            @if($new->notes != $old->notes)
                                <i class="fa fa-arrow-right"></i>
                                {{$new->notes}}
                            @endif
                        </label>
                    </div>
                </div>
                <input type="hidden" name="id_main" value="{{$old->id}}">
                <input type="hidden" name="id_update" value="{{$new->id}}">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Specification</label>
                    <div class="col-md-6">
                        <label for="" class="col-form-label">
                            :
                            {{$old->specification}}
                            @if($new->specification != $old->specification)
                                <i class="fa fa-arrow-right"></i>
                                {{$new->specification}}
                            @endif
                        </label>
                    </div>
                </div>
                <button type="submit" id="btn-submit" name="approve" value="1" class="btn btn-primary font-weight-bold" onclick="return confirm('Approve Revision?')">
                    <i class="fa fa-check"></i>
                    Update</button>
                <button type="submit" id="btn-submit" name="reject" value="1" class="btn btn-danger font-weight-bold" onclick="return confirm('Reject Revision?')">
                    <i class="fa fa-window-close"></i>
                    Reject</button>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
@endsection
