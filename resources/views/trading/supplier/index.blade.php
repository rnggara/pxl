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

        .star{
            unicode-bidi: bidi-override;
            color: #ffd700;
            font-size: 25px;
            height: 25px;
            margin: 0 auto;
            position: relative;
            text-shadow: 0 1px 0 #a2a2a2;
        }
    </style>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Supplier List</h3><br>

            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>New Supplier</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            {{--            <h5><span class="span">This page contains a list of Travel Order which has been formed.</span></h5>--}}
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Product Type</th>
                    <th class="text-left">Name </th>
                    <th class="text-left">PIC </th>
                    <th class="text-center">Address</th>
                    <th class="text-left">Contact</th>
                    <th class="text-left">Rating</th>
                    <th class="text-center">Holding</th>
                    <th class="text-center">NDA</th>
                    <th class="text-center">&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @foreach($supplier as $key => $val)
                    <tr>
                        <td>{{($key+1)}}</td>
                        <td>{{$val->type}}</td>
                        <td><a href="{{route('trading.supplier.edit',['id'=>$val->id])}}" class="btn btn-link"><i class="fa fa-search"></i>{{$val->name}}</a></td>
                        <td>{{$val->pic}}</td>
                        <td>{{$val->address}}</td>
                        <td>{{$val->telephone}}</td>
                        <td>
                            @php

                                $stars = "";
                                /** @var TYPE_NAME $val */
                                for($i=0;$i<intval($val->rating);$i++){
                                    $stars .= "★";
                                }
                            @endphp
                            @if($stars > 0 || $stars != null ||$stars != '' )
                                <div class='star'>{{$stars}}</div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td align="center">{{$view_company[$val->company_id]->tag}}</td>
                        <td align="center">
                            @if($val->nda_file != null)
                                <a href="{{route('download',$val->nda_file)}}" class="btn btn-sm btn-info btn-icon btn-icon-md" title="Download NDA"><i class="fa fa-download"></i></a>&nbsp;&nbsp;
                            @endif
                            <a href="#upload{{$val->id}}" data-toggle="modal" class="btn btn-sm btn-success btn-icon btn-icon-md" title="Upload NDA"><i class="fa fa-upload"></i></a>
                            <div class="modal fade" id="upload{{$val->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Upload NDA</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('trading.supplier.uploadNDA')}}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$val->id}}">
                                            <div class="modal-body">
                                                <div class="form-group row mx-auto">
                                                    <div class="col-md-9 custom-file">
                                                        <input type="file" class="form-control custom-file-input" name="file_draft" required/>
                                                        <label class=" custom-file-label" for="customFile">Upload File NDA</label>

                                                    </div>
                                                    <div class="col-md-3 btn-group">
                                                        <input type="hidden" name="id_leads" value="{{$val->id}}">
                                                        <button type="submit" class="btn btn-xs btn-light-primary"><i class="fa fa-upload"></i>Upload</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-danger btn-xs dttb" href="{{route('trading.supplier.delete',['id'=> $val->id])}}" title="Delete" onclick="return confirm('Are you sure you want to delete?'); ">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('trading.supplier.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Supplier Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Supplier Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Product Type</label>
                            <div class="col-md-6">
                                <select name="product_type" class="form-control">
                                    @foreach($product_type as $key => $val)
                                        <option value="{{$val->id}}">{{$val->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Address</label>
                            <div class="col-md-6">
                                <textarea name="address" id="" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Telephone</label>
                            <div class="col-md-6">
                                <input type="text" name="phone" class="form-control" placeholder="Telephone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Fax</label>
                            <div class="col-md-6">
                                <input type="text" name="fax" class="form-control" placeholder="Fax">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Bank Account</label>
                            <div class="col-md-6">
                                <input type="text" name="bank_acct" class="form-control" placeholder="Bank Account">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Web URL</label>
                            <div class="col-md-6">
                                <input type="text" name="web" class="form-control" placeholder="Web URL">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">PIC Name</label>
                            <div class="col-md-6">
                                <input type="text" name="pic_name" class="form-control" placeholder="PIC Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">PIC Email</label>
                            <div class="col-md-6">
                                <input type="text" name="pic_mail" class="form-control" placeholder="PIC Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Rating</label>
                            <div class="col-md-6">
                                <div class="rating">
                                    <input type="radio" id="star10" name="ratingInput" value="5" /><label for="star10" title="Excellent">Excellent</label>
                                    <input type="radio" id="star9" name="ratingInput" value="4" /><label for="star9" title="Very Good">Very Good</label>
                                    <input type="radio" id="star8" name="ratingInput" value="3" /><label for="star8" title="Good">Good</label>
                                    <input type="radio" id="star7" name="ratingInput" value="2" /><label for="star7" title="Not Bad">Not Bad</label>
                                    <input type="radio" id="star6" name="ratingInput" value="1" /><label for="star6" title="Bad">Bad</label>
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
    <script>
        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })
        })
    </script>
@endsection
