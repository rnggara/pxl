@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Corporate Social Responsibility (CSR)
            </div>
            <div class="card-toolbar">
                @actionStart('csr', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add CSR</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">CSR Title</th>
                        <th nowrap="nowrap" class="text-center">Created By</th>
                        <th nowrap="nowrap" class="text-center">Division</th>
                        <th nowrap="nowrap" class="text-center">Event Schedule</th>
                        <th nowrap="nowrap" class="text-center">Status</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('csr', 'read')
                    @foreach($csr as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td class="text-center"><a href="{{route('csr.view', $value->id)}}">{{$value->title}}</a></td>
                            <td class="text-center">{{$value->author}}</td>
                            <td class="text-center">{{$value->division}}</td>
                            <td class="text-center">{{date('d F Y', strtotime($value->date))}}</td>
                            <td class="text-center">
                                @if($value->online == 1)
                                    <form action='{{route('csr.publish')}}' method='post'>
                                        @csrf
                                        <input name='id' id='id' type='hidden' value='{{$value->id}}' />
                                        <button type='submit' name='unpublish' value='Unpublish' class='btn btn-success btn-xs dttb' onclick="return confirm('Unpublish CSR?')" title='Published'><i class='fa fa-eye'></i></button>
                                    </form>
                                @else
                                    <form action='{{route('csr.publish')}}' method='post'>
                                        @csrf
                                        <input name='id' id='id' type='hidden' value='{{$value->id}}' />
                                        <button type='submit' name='publish' value='Publish' class='btn btn-danger btn-xs dttb' onclick="return confirm('publish CSR?')" title='Unpublished'><i class='fa fa-eye-slash'></i></button>
                                    </form>
                                @endif
                            </td>
                            <td width="10%">
                                &nbsp;&nbsp;&nbsp;
                                @actionStart('csr', 'update')
                                <button type="button" class="btn btn-sm btn-primary btn-icon btn-xs" data-toggle="modal" data-target="#editEmployee{{$value->id}}"><i class="fa fa-edit"></i></button>
                                @actionEnd
                                @actionStart('csr', 'delete')
                                <a href="{{route('csr.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-xs" onclick="return confirm('Delete CSR?')"><i class="fa fa-trash"></i></a>
                                @actionEnd
                                <div class="modal fade" id="editEmployee{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="editEmployee{{$value->id}}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit CSR</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('csr.store')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="edit" value="{{$value->id}}">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form col-md-12">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                <input type="text" class="form-control" name="title" value="{{$value->title}}" required/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea name="description" id="description" class="form-control description_area" rows="10" placeholder="Description here.." required>{{$value->deskripsi}}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Event Schedule</label>
                                                                <input type="date" class="form-control" name="event_schedule" value="{{date('Y-m-d', strtotime($value->date))}}" required/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Division</label>
                                                                <select name="division" id="division" class="form-control">
                                                                    <option value="" @if($value->division == "") SELECTED @endif>-Choose-</option>
                                                                    <option value="Asset" @if($value->division == "Asset") SELECTED @endif>Asset</option>
                                                                    <option value="Consultant" @if($value->division == "Consultant") SELECTED @endif>Consultant</option>
                                                                    <option value="Finance" @if($value->division == "Finance") SELECTED @endif>Finance</option>
                                                                    <option value="GA" @if($value->division == "GA") SELECTED @endif>GA</option>
                                                                    <option value="HRD" @if($value->division == "HRD") SELECTED @endif>HRD</option>
                                                                    <option value="IT" @if($value->division == "IT") SELECTED @endif>IT</option>
                                                                    <option value="Laboratory" @if($value->division == "Laboratory") SELECTED @endif>Laboratory</option>
                                                                    <option value="Maintenance" @if($value->division == "Maintenance") SELECTED @endif>Maintenance</option>
                                                                    <option value="Marketing" @if($value->division == "Marketing") SELECTED @endif>Marketing</option>
                                                                    <option value="Operation" @if($value->division == "Operation") SELECTED @endif>Operation</option>
                                                                    <option value="Procurement" @if($value->division == "Procurement") SELECTED @endif>Procurement</option>
                                                                    <option value="Production" @if($value->division == "Production") SELECTED @endif>Production</option>
                                                                    <option value="QC" @if($value->division == "QC") SELECTED @endif>QC</option
                                                                    ><option value="QHSSE" @if($value->division == "QHSSE") SELECTED @endif>QHSSE</option>
                                                                    <option value="Receiptionist" @if($value->division == "Receiptionist") SELECTED @endif>Receiptionist</option>
                                                                    <option value="Secretary" @if($value->division == "Secretary") SELECTED @endif>Secretary</option>
                                                                    <option value="Technical" @if($value->division == "Technical") SELECTED @endif>Technical</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Attach File</label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="image1" class="form-control" id="pic_eq1{{$value->id}}" onchange="editPict('prev_eq1{{$value->id}}',this)" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <img src="{{str_replace('public','public_html',asset('/media/csr_attachment/'))}}/{{$value->pict1}}" id="prev_eq1{{$value->id}}" class="img-responsive center-block">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="image2" class="form-control" id="pic_eq2{{$value->id}}" onchange="editPict('prev_eq2{{$value->id}}',this)" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <img src="{{str_replace('public','public_html',asset('/media/csr_attachment/'))}}/{{$value->pict2}}" id="prev_eq2{{$value->id}}" class="img-responsive center-block">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="image3" class="form-control" id="pic_eq3{{$value->id}}" onchange="editPict('prev_eq3{{$value->id}}',this)" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <img src="{{str_replace('public','public_html',asset('/media/csr_attachment/'))}}/{{$value->pict3}}" id="prev_eq3{{$value->id}}" class="img-responsive center-block">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="image4" class="form-control" id="pic_eq4{{$value->id}}" onchange="editPict('prev_eq4{{$value->id}}',this)" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <img src="{{str_replace('public','public_html',asset('/media/csr_attachment/'))}}/{{$value->pict4}}" id="prev_eq4{{$value->id}}" class="img-responsive center-block">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <input type="file" name="image5" class="form-control" id="pic_eq5{{$value->id}}" onchange="editPict('prev_eq5{{$value->id}}',this)" multiple accept='image/*'>
                                                                </div>
                                                            </div>
                                                            <div class="form-group prev-group">
                                                                <label class="col-sm-3 control-label"></label>
                                                                <div class="col-sm-7">
                                                                    <img src="{{str_replace('public','public_html',asset('/media/csr_attachment/'))}}/{{$value->pict5}}" id="prev_eq5{{$value->id}}" class="img-responsive center-block">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    @actionStart('csr', 'update')
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="fa fa-check"></i>
                                                        Save</button>
                                                    @actionEnd
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add CSR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('csr.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" required/>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="description" class="form-control description_area" rows="10" placeholder="Description here.." required>

                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Event Schedule</label>
                                    <input type="date" class="form-control" name="event_schedule" required/>
                                </div>
                                <div class="form-group">
                                    <label>Division</label>
                                    <select name="division" id="division" class="form-control">
                                       <option value="">-Choose-</option>
                                        <option value="Asset">Asset</option>
                                        <option value="Consultant">Consultant</option>
                                        <option value="Finance">Finance</option>
                                        <option value="GA">GA</option>
                                        <option value="HRD">HRD</option>
                                        <option value="IT">IT</option>
                                        <option value="Laboratory">Laboratory</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Operation">Operation</option>
                                        <option value="Procurement">Procurement</option>
                                        <option value="Production">Production</option>
                                        <option value="QC">QC</option
                                        ><option value="QHSSE">QHSSE</option>
                                        <option value="Receiptionist">Receiptionist</option>
                                        <option value="Secretary">Secretary</option>
                                        <option value="Technical">Technical</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Attach File</label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image1" class="form-control" onchange="editPict('prev_eq1',this)" multiple accept='image/*'>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq1" class="img-responsive center-block">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image2" class="form-control" onchange="editPict('prev_eq2',this)" id="pic_eq2" multiple accept='image/*'>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq2" class="img-responsive center-block">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image3" class="form-control" onchange="editPict('prev_eq3',this)" id="pic_eq3" multiple accept='image/*'>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq3" class="img-responsive center-block">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image4" class="form-control" id="pic_eq4" onchange="editPict('prev_eq4',this)" multiple accept='image/*'>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq4" class="img-responsive center-block">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image5" class="form-control" id="pic_eq5" onchange="editPict('prev_eq5',this)" multiple accept='image/*'>
                                    </div>
                                </div>
                                <div class="form-group prev-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-7">
                                        <img src="#" id="prev_eq5" class="img-responsive center-block">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
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
            $('table.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

            tinymce.init({
                editor_selector : ".description_area",
                selector:'textarea',
                mode : "textareas",
                menubar: true,
                toolbar: true,
            });
            $("#prev_eq1").hide();
            $("#prev_eq2").hide();
            $("#prev_eq3").hide();
            $("#prev_eq4").hide();
            $("#prev_eq5").hide();
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
