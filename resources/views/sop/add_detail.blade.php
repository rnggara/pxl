@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>SOP of <span class="text-primary">{{$sop_main->topic}}</span> </h3>
            </div>

            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{($status == 0) ? route('sop.index') : route('sop.detail',['id_main' => $sop_main->id])}}" class="btn btn-success btn-sm btn-icon"><i class="fa fa-arrow-left"></i></a>
                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add SOP</button>--}}
                </div>
            </div>

        </div>
        <div class="card-body">
            <form name='form1' method='post' action='{{route('sop.storedetail')}}'>
                @csrf
                <input type="hidden" name="id_main" id="id_main" value="{{$sop_main->id}}">
                <div class="row">
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
							<textarea class="form-control" rows="5" name="topic" id="topic">

							</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><h4>ENGLISH</h4></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
							<textarea class="form-control" rows="5" name="topic_eng" id="topic_eng">

							</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <br>
                        <hr>
                        <button type="submit" name='Save' value='Save' class="btn btn-success btn-lg"><i class="fa fa-check"></i> Save SOP</button>

                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
@section('custom_script')
    <script type="text/javascript" src="https://cdn.ckeditor.com/4.5.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('topic');
        CKEDITOR.replace('topic_eng');
    </script>
@endsection
