<div class="card card-custom gutter-b">
    <div class="card-header card-header-tabs-line">
        <div class="card-title">
            List Papers
        </div>
        <div class="card-toolbar btn-group">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaper"><i class="fa fa-plus"></i> Add Paper</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <table class="table table-bordered table-hover display">
                    <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th style="width:200%" class="text-center">Vehicle</th>
                        <th class="text-center">Police Number</th>
                        <th class="text-center">STNK Number</th>
                        <th class="text-center">STNK Holder</th>
                        <th class="text-center">STNK Value</th>
                        <th class="text-center">Expired Date</th>
                        <th class="text-center">STNK Specs</th>
                        <th class="text-center">STNK Detail</th>
                        <th class="text-center">File</th>
                        <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($papers as $i => $item)
                        <tr>
                            <td align="center">{{$i+1}}</td>
                            <td align="left">{{$item->veName}}</td>
                            <td align="center">{{$item->name}}</td>
                            <td align="center">{{$item->certificate_no}}</td>
                            <td align="center">{{$item->certificate_holder}}</td>
                            <td align="right">{{number_format($item->certificate_value, 2)}}</td>
                            <td align="center">{{$item->exp_date}}</td>
                            <td align="center">{{strip_tags($item->description)}}</td>
                            <td align="center">{{$item->others}}</td>
                            <td align="center">
                                {{--image--}}
                                @if(!empty($item->picture))
                                    <a href="{{route('download', $item->picture)}}" class="btn btn-xs btn-icon btn-light-success"><i class="fa fa-download"></i></a>
                                @endif
                                <button type="button" onclick="modal_picture({{$item->id}})" data-toggle="modal" data-target="#addPicture" class="btn btn-xs btn-icon btn-light-dark"><i class="fa fa-upload"></i></button>
                            </td>
                            <td align="center" nowrap="">
                                <button type="button" onclick="edit_paper({{$item->id}})" data-toggle="modal" data-target="#editPaperModal" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></button>
                                <button type="button" onclick="delete_paper({{$item->id}})" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addPaper" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Papers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.add.paper')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Police Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" name="paper_name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" name="paper_number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Date</label>
                                <div class="col-md-8">
                                    <input type="date" class="form-control required" name="paper_date">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Value</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control number required" name="paper_value">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Owner</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" name="paper_owner">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Holder</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" name="paper_holder">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Specifications</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="paper_spec" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">STNK Year/Color</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control required" name="stnk_y_c" id="stnk-y-c-paper">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="_action" value="post">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addPicture" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form method="POST" action="{{route('ha.ve.upload.paper')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-4">Upload File</label>
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" class="form-control custom-file-input required" name="picture">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_paper" id="id_paper">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editPaperModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="paper-edit">

        </div>
    </div>
</div>
