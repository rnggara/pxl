@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="d-flex row">
                        <div class="row col-md-12">
                            <div class="flex-shrink-0 mx-auto">
                                <div class="symbol symbol-circle symbol-50 symbol-lg-100 symbol-info">
                                    <span class="symbol-label"><i class="fa fa-blog text-white font-size-h1-xl"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12 mt-8">
                            <div class="flex-shrink-0 mx-auto col-md-8">
                                <h3 class="card-label text-center">{{$topic->nama_topik}}</h3>
                                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <input type="hidden" class="form-control" id="input-progress" />
                                            <div id="progress" class="nouislider nouislider-handle-secondary nouislider-connect-primary"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                    <div class="row">
                        <label class="col-md-3 card-label font-weight-bold">Topic Description</label>
                        <div class="col-md-9">
                            {{strip_tags($topic->desc_topik)}}
                        </div>
                    </div>
                    <div class="separator separator-solid separator-border-2 separator-info mt-5 mb-5"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{route('forum.topic',['id' => $topic->id_forum])}}" class="btn btn-secondary"><i class="fa fa-backspace"></i> Back</a>
                        </div>
                        <div class="col-md-9"></div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">

            <div class="tab-content" id="pageTab">
                <div class="tab-pane fade show active" id="meetings-tab" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="nav-icon">
                                    <i class="fas fa-blog"></i>
                                </span>&nbsp;&nbsp;
                                <span class="nav-text">Posts</span></div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#addMeeting"><i class="fa fa-plus"></i>Create Post</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="addMeeting" tabindex="-1" role="dialog" aria-labelledby="addMeeting" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{route('forum.storepost')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="id_topik" value="{{$topic->id_topik}}">
                                        <input type="hidden" name="id_forum" value="{{$topic->id_forum}}">
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Picture</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="file" name="image1" class="form-control" placeholder="Picture" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Picture (additional)</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="file" name="image2" class="form-control" placeholder="Picture" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Video</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <input type="file" name="video" class="form-control" placeholder="Video" accept="video/mp4">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-3 col-sm-12">Comment</label>
                                            <div class="col-lg-6 col-md-9 col-sm-12">
                                                <textarea name="isi_comment" id="" class="form-control" cols="30" rows="10"></textarea>
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
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            @foreach($comments as $key => $comment)
                                <div class="card card-custom mb-5">
                                    <div class="card-body">
                                        <div class="timeline-item">
                                            <div class="timeline-media">
                                                {{date('d M',strtotime($comment->date_comment))}}
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="mr-2">
                                                        <a href="#" class="text-dark-75 text-secondary font-weight-bold"><i class="fa fa-blog"></i>&nbsp;Posts</a>
                                                        @php
                                                            $dateNow = strtotime(date('Y-m-d'));
                                                            /** @var TYPE_NAME $comment */
                                                            $dateCreated = strtotime(date('Y-m-d',strtotime($comment->date_comment)));
                                                            $days = ($dateNow - $dateCreated) / 86400
                                                        @endphp
                                                        <span class="text-muted ml-2">created by &nbsp;<b>{{$comment->created_by}} </b></span>
                                                        <span class="label label-light-info font-weight-bolder label-inline ml-2">{{($days > 0) ? $days.' day(s) ago': 'Today'}}</span>
                                                    </div>
                                                    <div class="dropdown ml-2" data-toggle="tooltip" title="" data-placement="left">

                                                        <a href="{{route('forum.deletepost',['id' => $comment->id_comment,'id_topik' => $comment->id_topik])}}" onclick="return confirm('Delete this post?');" class="btn btn-hover-light-danger btn-sm btn-icon" >
                                                            <i class="fa fa-trash icon-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <p class="text-dark-75 text-secondary font-weight-bold">By: {{$comment->created_by}}</p>

                                                <div class="separator separator-dashed my-10"></div>
                                                <div class="col-md-12 text-center">
                                                    @if($comment->image1!=null)
                                                        <img src="{{str_replace('public','public_html',asset('/media/forum_attachment/'))}}/{{$comment->image1}}" class="img-responsive center-block" width="25%">
                                                    @endif
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    @if($comment->image2!=null)
                                                        <img src="{{str_replace('public','public_html',asset('/media/forum_attachment/'))}}/{{$comment->image2}}" class="img-responsive center-block" width="25%">
                                                    @endif
                                                </div>
                                                <br><br><br>
                                                <div class="col-md-12 text-center">
                                                    @if($comment->video!=null)
                                                        <video width="50%" height="240" controls>
                                                            <source src="{{str_replace('public','public_html',asset('/media/forum_attachment/'))}}/{{$comment->video}}" type="video/mp4">
                                                        </video>
                                                        @endif
                                                </div>
                                                <br><br><br><br>
                                                <div class="col-md-12 text-center">
                                                    <textarea name="topic" id="topic" class="form-control revise_area" rows="10" readonly>{{$comment->isi_comment}}</textarea>
                                                </div>

                                                <div class="separator separator-dashed my-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('custom_script')

@endsection
