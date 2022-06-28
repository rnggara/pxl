@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Add Report - {{ $project->prj_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.operation.setting', ['type' => "report", "id" => $project->id]) }}" class="btn btn-success btn-icon btn-sm"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('general.operation.post.report', $project->id) }}" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <h3>Detail</h3>
                        <hr>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Reported By</label>
                            <div class="col-md-3 col-sm-12">
                                <input type="text" class="form-control" readonly value="{{ Auth::user()->username }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Project</label>
                            <div class="col-md-3 col-sm-12">
                                <input type="text" class="form-control" readonly value="{{ $project->prj_name }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Report Date</label>
                            <div class="col-md-3 col-sm-12">
                                <input type="date" class="form-control required" name="report_date" value="{{ date("Y-m-d") }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Location</label>
                            <div class="col-md-3 col-sm-12">
                                <input type="text" class="form-control required" placeholder="Location" name="location">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>Equipment and Production Report</h3>
                        <hr>
                    </div>
                    @foreach ($_category as $key => $item)
                        <div class="col-12">
                            <h3>{{ $item }}</h3>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-center">Name</th>
                                        @if (in_array($key, ["tank", "safety", "pump"]))
                                            <th class="text-center">Description</th>
                                        @endif
                                        @if ($key == "truck")
                                            <th class="text-center">Truck Details</th>
                                        @else
                                            <th class="text-center">{{ ($key == 'safety') ? "Remark" : "Value" }}</th>
                                            <th class="text-center">{{ ($key == "safety") ? "UoM" : "" }}</th>
                                        @endif
                                        <th class="text-center">
                                            {{ ($key == "truck") ? "Transfer Details" : "Status" }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $val)
                                        @if ($val->category == $key)
                                            <tr>
                                                <td>{{ $val->item_name }}</td>
                                                @if (in_array($key, ["tank", "safety", "pump"]))
                                                    <td>{!! $val->description !!}</td>
                                                @endif
                                                <td>
                                                    @if ($key == "truck")
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">License Plate</label>
                                                            <input type="text" name="js[{{ $key }}][licence_place][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Capacity</label>
                                                            <input type="text" name="js[{{ $key }}][capacity][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Company</label>
                                                            <input type="text" name="js[{{ $key }}][company][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                    @else
                                                        <input type="text" name="js[{{ $key }}][{{($key == 'safety') ? 'remark' : "value" }}][{{ $val->id }}]" class="form-control required">
                                                    @endif
                                                </td>
                                                @if ($key != "truck")
                                                <td>{{ $val->uom }}</td>
                                                @endif
                                                <td>
                                                    @if ($key == "truck")
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Start ({{ $val->uom }})</label>
                                                            <input type="text" name="js[{{ $key }}][start][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Stop ({{ $val->uom }})</label>
                                                            <input type="text" name="js[{{ $key }}][stop][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Total ({{ $val->uom }})</label>
                                                            <input type="text" name="js[{{ $key }}][total][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                    @else
                                                        <select name="js[{{ $key }}][status][{{ $val->id }}]" class="form-control select2 required" id="">
                                                            <option value="good">Good</option>
                                                            <option value="bad">Bad</option>
                                                        </select>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                    <div class="col-12 mt-5">
                        <h3>Activity</h3>
                        <hr>
                    </div>
                    <div class="col-12" id="desc-div">
                        <div class="form-group row form-desc">
                            <div class="col-md-3 col-sm-12">
                                <label for="" class="col-form-label">From</label>
                                <input type="time" name="activity_from[]" class="form-control desc-from required">
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="" class="col-form-label">To</label>
                                <input type="time" name="activity_to[]" class="form-control desc-to required">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="" class="col-form-label">Description</label>
                                <textarea name="description[]" id="" class="form-control tmce" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" id="btn-remove" class="btn btn-danger">Remove</button>
                        <button type="button" id="btn-add-more" class="btn btn-primary">Add More</button>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <h3>Inventory Record</h3>
                    </div>
                    <div class="col-12">
                        <h4>Please save the report first to access inventory records.</h4>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="attach-div">
                            <div class="form-group row">
                                <label for="" class="col-form-label col-md-3 col-sm-12">Attach File <span for="" class="font-size-xs font-italic text-muted">*Max image size is 2mb</span></label>
                                <div class="col-md-6 col-sm-12">
                                    <div class="custom-file">
                                        <input type="file" name="attachment_file[]" class="custom-file-input" accept=".jpg, .jpeg, .png, .gif">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                </div>
                                <label for="" class="col-form-label font-italic col-md-3 col-sm-12">*Only JPG, JPEG, PNG & GIF</label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary" id="btn-add-attachment">Add More Attachment</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-right">
                        @csrf
                        <button type="submit" id="btn-post-form" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("theme/tinymce/tinymce.min.js") }}"></script>
    <script>
        function show_btn_remove(){
            var div = $(".form-desc").toArray()
            if(div.length > 1){
                $("#btn-remove").show()
            } else {
                $("#btn-remove").hide()
            }
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width: "100%"
            })

            $("#btn-remove").hide()

            tinymce.init({
                selector : ".tmce",
                menubar : false
            })

            $("#btn-remove").click(function(){
                var div = $(".form-desc").toArray()
                div[div.length - 1].remove()
                show_btn_remove()
            })

            $("#btn-add-more").click(function(){
                var desc_from = $(".desc-from").toArray()
                var desc_to = $(".desc-to").toArray()

                var cur_from = desc_from[desc_from.length - 1]
                if(cur_from.value == ""){
                    Swal.fire('Warning', "Field Activity From is required", 'warning')
                }
                var cur_to = desc_to[desc_to.length - 1]
                if(cur_to.value == ""){
                    Swal.fire('Warning', "Field Activity To is required", 'warning')
                }

                if(cur_from.value != "" && cur_to.value != ""){
                    $.ajax({
                        url : "{{ route('general.operation.add_form') }}/?t="+cur_to.value,
                        type : "get",
                        success : function(response){
                            $("#desc-div").append(response)
                            tinymce.init({
                                selector : ".tmce",
                                menubar : false
                            })

                            show_btn_remove()
                        }
                    })
                }
            })

            $("#btn-add-attachment").click(function(){
                $.ajax({
                    url : "{{ route('general.operation.add_form_attachment') }}",
                    type : "get",
                    success : function(response){
                        $(".attach-div").append(response)
                    }
                })
            })

            $("#btn-post-form").click(function(e){
                e.preventDefault()
                var isempty = []
                var form = $(this).parents('form')
                var req = form.find(".required")
                req.each(function(){
                    console.log($(this).val())
                    if ($(this).val() == "") {
                        $(this).addClass('is-invalid')
                        isempty.push('1')
                    }
                })

                if(isempty.length > 0){
                    Swal.fire('Required', 'Please fill the required field', 'warning')
                } else {
                    form.submit()
                }
            })

            var req = $(".required")
            req.each(function(){
                console.log($(this).val())
                $(this).change(function(){
                    if ($(this).val() != "") {
                        $(this).addClass('is-valid')
                        $(this).removeClass("is-invalid")
                    } else {
                        $(this).addClass('is-invalid')
                        $(this).removeClass("is-valid")
                    }
                })
            })
        })
    </script>
@endsection
