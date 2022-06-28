<div class="row">
    <div class="col-md-12">
        <div class="card card-custom gutter-b bg-light-secondary">
            <div class="card-header">
                <h3 class="card-title">Upload Document</h3>
                <div class="card-toolbar">
                    <form action="{{route('marketing.projects.files.upload')}}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            @csrf
                            <div class="col-md-9">
                                <div class="form-group custom-file">
                                    <input type="file" class="custom-file-input" name="file">
                                    <span class="custom-file-label">Choose File</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="id_project" value="{{$prj->id}}">
                                <input type="hidden" name="id_step" value="{{$item->id}}">
                                <input type="hidden" name="type" value="{{$formType}}">
                                <button type="submit" class="btn btn-sm btn-icon btn-success"><i class="fa fa-upload"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($files as $file)
                        @if($file->id_step == $item->id && $file->type == $formType)
                            <div class="col-md-2">
                            <div class="card card-custom gutter-b card-stretch">
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                    <div class="card-toolbar">
                                        <div class="dropdown dropdown-inline">
                                            <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ki ki-bold-more-hor"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                <!--begin::Navigation-->
                                                <ul class="navi navi-hover py-5">
                                                    <li class="navi-item">
                                                        <a href="{{route('download', $file->file_code)}}" class="navi-link">
																					<span class="navi-icon">
																						<i class="fas fa-file-download"></i>
																					</span>
                                                            <span class="navi-text">Download</span>
                                                        </a>
                                                    </li>
                                                    {{--                                            <li class="navi-item">--}}
                                                    {{--                                                <a href="#" onclick="share_button('{{$file->file_code}}')" data-toggle="modal" data-target="#shareFileModal" class="navi-link">--}}
                                                    {{--																					<span class="navi-icon">--}}
                                                    {{--																						<i class="fas fa-share"></i>--}}
                                                    {{--																					</span>--}}
                                                    {{--                                                    <span class="navi-text">Share</span>--}}
                                                    {{--                                                </a>--}}
                                                    {{--                                            </li>--}}
                                                    <li class="navi-item">
                                                        <a href="#" onclick="delete_file('{{$file->id}}')"  class="navi-link">
																					<span class="navi-icon">
																						<i class="fas fa-trash"></i>
																					</span>
                                                            <span class="navi-text">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{asset('theme/assets/media/svg/'.$data_file[$file->file_code]['src'])}}" class="h-85px" alt="">
                                        <label for="" style="width: 100%" class="text-center mt-15 text-wrap font-weight-bold">{{$data_file[$file->file_code]['file_name']}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

