@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                @php
                if (!isset($id) && !isset($status)){
                 $stat = "new";
                    $title = "Add New ";
                    $btn = "Add";
                }
                if (isset($status)){
                $stat = $status;
                    if ($status == 'edit'){
                        $title = "Edit ";
                        $btn = "Update";
                    } elseif ($status == 'follow'){
                        $title = "Follow Up ";
                        $btn = "Follow Up";

                    } else {
                     $title = "Approval ";
                     $btn = "Approve";
                    }
                }
                @endphp
                <h3>{{$title}} Near Miss</h3><br>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('nearmiss.store')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{(isset($nearmiss))?$nearmiss->id:0}}">
                <input type="hidden" name="status" value="{{$stat}}">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Date</label>
                    <div class="col-md-6">
                        <input type="date" class="form-control" name="date" {{($status == 'follow')?'readonly':''}} value="{{(isset($nearmiss))?date('Y-m-d',strtotime($nearmiss->date)):''}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Project</label>
                    <div class="col-md-6">
                        <select name="prj" class="form-control" {{($status == 'follow')?'disabled':''}}>
                            @foreach($prj as $key => $val)
                                <option value="{{$val->id}}" @if(isset($nearmiss)) @if($nearmiss->prj == $val->id) selected @endif @endif>{{$val->prj_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">{{($stat == 'new')?'Title':'Case Of'}}</label>
                    <div class="col-md-6">
                        <input type="text" name="title" class="form-control" {{($status == 'follow')?'readonly':''}} placeholder="Title" value="{{(isset($nearmiss))? $nearmiss->title : ''}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">{{($status == 'follow')? 'Follow Up': 'Description'}}</label>
                    <div class="col-md-6">
                        <textarea name="desc" class="form-control description_area" cols="30" rows="10">{{(isset($nearmiss))? ($status == 'follow')? '': $nearmiss->deskripsi : ''}}</textarea>
                    </div>
                </div>
                @if($stat == 'new' || $status == 'follow')
                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label text-right">{{($status == 'follow')?'Photo':'Attach File'}}</label>
                    <div class="col-sm-7">
                        <input type="file" name="image1" class="form-control" onchange="editPict('prev_eq1',this)" multiple accept='image/*'>
                    </div>
                </div>
                <div class="form-group row prev-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-7">
                        <img src="#" id="prev_eq1" width="300" height="200" class="img-responsive center-block">
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <button type="submit" name="submit" value="{{$status}}" class="btn btn-primary font-weight-bold pull-right">
                            <i class="fa fa-check"></i>
                            {{$btn}}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>
    $(document).ready(function() {

        tinymce.init({
            editor_selector : ".description_area",
            selector:'textarea',
            mode : "textareas",
            menubar: true,
            toolbar: true,
        });

        $("#prev_eq1").hide();

    })
    function editPict(target,img){
        console.log($(img).val())
        if($(img).val()) {
            readURL(img, target);
            $("#"+target).show();
        } else {
            $("#"+target).hide();
        }
    }

    function readURL(input, idn) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#' + idn).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
