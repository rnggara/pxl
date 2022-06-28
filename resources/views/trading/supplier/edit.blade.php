@extends('layouts.template')
@section('content')
    <style type="text/css">
        .rating {
            float:left;
        }

        /* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
          follow these rules. Every browser that supports :checked also supports :not(), so
          it doesn’t make the test unnecessarily selective */
        .rating:not(:checked) > input {
            position:absolute;
            top:-9999px;
            clip:rect(0,0,0,0);
        }

        .rating:not(:checked) > label {
            float:right;
            width:1em;
            /* padding:0 .1em; */
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:300%;
            /* line-height:1.2; */
            color:#ddd;
        }

        .rating:not(:checked) > label:before {
            content: '★ ';
        }

        .rating > input:checked ~ label {
            color: dodgerblue;

        }

        .rating:not(:checked) > label:hover,
        .rating:not(:checked) > label:hover ~ label {
            color: dodgerblue;

        }

        .rating > input:checked + label:hover,
        .rating > input:checked + label:hover ~ label,
        .rating > input:checked ~ label:hover,
        .rating > input:checked ~ label:hover ~ label,
        .rating > label:hover ~ input:checked ~ label {
            color: dodgerblue;

        }

        .rating > label:active {
            position:relative;
            top:2px;
            left:2px;
        }
    </style>

    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Edit Supplier Information</h3><br>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('trading.supplier.update')}}" >
                @csrf
                <input type="hidden" name="id" id="" value="{{$supplier->id}}">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Supplier Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" placeholder="Supplier Name" value="{{$supplier->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Product Type</label>
                    <div class="col-md-6">
                        <select name="product_type" class="form-control">
                            @foreach($product_type as $key => $val)
                                <option value="{{$val->id}}" @if($supplier->id_product_type == $val->id) selected @endif>{{$val->type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Address</label>
                    <div class="col-md-6">
                        <textarea name="address" id="" class="form-control" cols="30" rows="10">{{$supplier->address}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Telephone</label>
                    <div class="col-md-6">
                        <input type="text" name="phone" class="form-control" placeholder="Telephone" value="{{$supplier->telephone}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Fax</label>
                    <div class="col-md-6">
                        <input type="text" name="fax" class="form-control" placeholder="Fax" value="{{$supplier->fax}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Bank Account</label>
                    <div class="col-md-6">
                        <input type="text" name="bank_acct" class="form-control" placeholder="Bank Account" value="{{$supplier->bank_acct}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Web URL</label>
                    <div class="col-md-6">
                        <input type="text" name="web" class="form-control" placeholder="Web URL" value="{{$supplier->web}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">PIC Name</label>
                    <div class="col-md-6">
                        <input type="text" name="pic_name" class="form-control" placeholder="PIC Name" value="{{$supplier->pic}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">PIC Email</label>
                    <div class="col-md-6">
                        <input type="text" name="pic_mail" class="form-control" placeholder="PIC Email" value="{{$supplier->pic_email}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Rating</label>
                    <div class="col-md-6">
                        <div class="rating">
                            <input type="radio" id="star10" name="ratingInput" value="5" @if($supplier->rating == 5) checked @endif /><label for="star10" title="Excellent">Excellent</label>
                            <input type="radio" id="star9" name="ratingInput" value="4" @if($supplier->rating == 4) checked @endif /><label for="star9" title="Very Good">Very Good</label>
                            <input type="radio" id="star8" name="ratingInput" value="3" @if($supplier->rating == 3) checked @endif /><label for="star8" title="Good">Good</label>
                            <input type="radio" id="star7" name="ratingInput" value="2" @if($supplier->rating == 2) checked @endif /><label for="star7" title="Not Bad">Not Bad</label>
                            <input type="radio" id="star6" name="ratingInput" value="1" @if($supplier->rating == 1) checked @endif /><label for="star6" title="Bad">Bad</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold pull-right">
                            <i class="fa fa-check"></i>
                            Update</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
