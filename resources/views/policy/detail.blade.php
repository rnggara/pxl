@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Policy of &nbsp; <b class="text-primary">{{$main->topic}}</b>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('policy.index')}}" title="Policy Main" class="btn btn-secondary"><i class="fa fa-backspace"></i> Back</a>
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-pencil-ruler"></i>New Revise</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Create Date</th>
                        <th class="text-center">Content</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Approved By</th>
                        <th class="text-center">Revision #</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($details as $key => $value)
                        <tr>
                            <td class="text-center">{{($key+1)}}</td>
                            <td class="text-center">{{date('d F Y', strtotime($value->date_detail))}} | {{date('H:i', strtotime($value->date_detail))}}</td>
                            <td class="text-center">
                                @if($value->approved_by == null && $value->approved_time == null)
                                    <a href="{{route('policy.detail.viewappr',['id' => $value->id_detail,'type' => 'appr'])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>View & Approve</a>
                                @else
                                    <a href="{{route('policy.detail.viewappr',['id' => $value->id_detail])}}" class="btn btn-xs btn-link"><i class="fa fa-search"></i>View</a>
                                @endif
                            </td>
                            <td class="text-center">{{$value->created_by}}</td>

                            <td class="text-center">
                                @if($value->approved_by != null && $value->approved_time != null)
                                    {{$value->approved_by}} <br>
                                    {{date('d F Y', strtotime($value->approved_time))}}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{($value->revision == null) ? '-' : $value->revision}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary btn-icon btn-xs" data-toggle="modal" data-target="#editEmployee{{$value->id_detail}}"><i class="fa fa-edit"></i></button>
                                <div class="modal fade" id="editEmployee{{$value->id_detail}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Revise of Policy</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('policy.storeDetail')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="edit" value="{{$value->id_detail}}">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="hidden" name="id_main" value="{{$main->id_main}}">
                                                            <input type="hidden" name="edit" value="{{$value->id_detail}}">
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Indonesian</label>
                                                                <div class="col-sm-7">
                                                                    <textarea name="ed_topic" id="ed_topic" class="form-control" rows="10" placeholder="Indonesian version write here.." required>{{$value->content}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">English</label>
                                                                <div class="col-sm-7">
                                                                    <textarea name="ed_topic_eng" id="ed_topic_eng" class="form-control" rows="10" placeholder="English version write here.." required>{{$value->content_eng}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Attachment</label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="attachment_policy" class="form-control" id="ed_pic_eq" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group ed-prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <div id="ed_exist">
                                                                        <img src='{{str_replace('public','public_html',asset('/media/policy_attachment/'))}}/{{$value->attachment}}' id='ed_prev_eq' class='img-responsive center-block'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit" value="1" class="btn btn-primary font-weight-bold">
                                                        <i class="fa fa-check"></i>
                                                        Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{route('policy.detail.delete',['id'=>$value->id_detail,'id_main' => $value->id_policy_main])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-xs" onclick="return confirm('Delete detail policy?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Revise of Policy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('policy.storeDetail')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id_main" value="{{$main->id_main}}">
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Indonesian</label>
                                    <div class="col-sm-7">
                                        <textarea name="topic" id="topic" class="form-control revise_area" rows="10" placeholder="Indonesian version write here.." required>

                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">English</label>
                                    <div class="col-sm-7">
                                        <textarea name="topic_eng" id="topic_eng" class="form-control revise_area" rows="10" placeholder="English version write here.." required>

                                        </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Attachment</label>
                                    <div class="col-sm-7">
                                        <input type="file" name="attachment_policy" class="form-control" id="pic_eq" multiple accept='image/*' required>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq" alt="" class="img-responsive center-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" value="1" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                editor_selector : ".revise_area",
                selector:'textarea',
                mode : "textareas",
                menubar: true,
                toolbar: true,
            });

            $("#pic_eq").change(function(){
                if($(this).val()) {
                    readURL(this, "prev_eq");
                    $(".prev-group").show();
                } else {
                    $(".prev-group").hide();
                }
            });
            $("#ed_pic_eq").change(function(){
                if($(this).val()) {
                    readURL(this, "ed_prev_eq");
                    $(".ed-prev-group").show();
                } else {
                    $(".ed-prev-group").hide();
                }
            });

        })

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
