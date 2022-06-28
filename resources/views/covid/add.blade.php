@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Covid Protocol Add</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.setting') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <form name='form1' method='post' action='{{route('general.covid.store')}}' enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                @if (\Session::get('error'))
                                    <label class="col-form-label text-danger">File type is not allowed</label>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><h4>INDONESIAN</h4></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                    <textarea class="form-control" rows="20" name="topic" id="topic">

                                    </textarea>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" accept="image/*" name="attachment">
                                    <span class="custom-file-label">Choose File</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted col-form-label">* Upload image file (.jpeg, .png, .tiff, etc)</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <button type="submit" name='Save' value='Save' class="btn btn-success btn-lg"><i class="fa fa-check"></i> Save Protocol</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
<script type="text/javascript" src="https://cdn.ckeditor.com/4.5.1/standard/ckeditor.js"></script>
<script src="{{ asset('theme/tinymce/tinymce.min.js') }}"></script>
<script>
    $(document).ready(function(){

        tinymce.init({
            selector : "#topic"
        })

        var form = $("form[name=form1]")
        var submit = form.find("button[type=submit]")
        console.log(form)
        console.log(submit)
        submit.click(function(e){
            e.preventDefault()
            var content = tinymce.get('topic').getContent()
            var file = form.find("input[type=file]")

            if(content == "" && file.val() == ""){
                Swal.fire('Warning', 'Please input at least 1 field', 'warning')
            } else {
                form.submit()
            }
        })
    })
</script>
@endsection
