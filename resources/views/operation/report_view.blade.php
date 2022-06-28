@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Field Report - {{ $project->prj_name }} - {{ $reports->report_no }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.operation.setting', ['type' => "report", "id" => $project->id]) }}" class="btn btn-success btn-icon btn-sm"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('general.operation.update.report', $reports->id) }}" method="post" enctype="multipart/form-data">
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
                                <input type="date" class="form-control required" name="report_date" value="{{ date("Y-m-d") }}" value="{{ $reports->report_date }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-12">Location</label>
                            <div class="col-md-3 col-sm-12">
                                <input type="text" class="form-control required" placeholder="Location" name="location" value="{{ $reports->location }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>Equipment and Production Report</h3>
                        <hr>
                    </div>
                    @php
                        $js = (!empty($reports->do_in)) ? json_decode($reports->do_in, true) : [];
                    @endphp
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
                                            @php
                                                $vv = [];
                                                if(isset($js[$key])){
                                                    $vv = $js[$key];
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $val->item_name }}</td>
                                                @if (in_array($key, ["tank", "safety", "pump"]))
                                                    <td>{!! $val->description !!}</td>
                                                @endif
                                                <td>
                                                    @if ($key == "truck")
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">License Plate</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['license_plate'])) ? ((isset($vv['license_plate'][$val->id])) ? $vv['license_plate'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][license_plate][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Capacity</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['capacity'])) ? ((isset($vv['capacity'][$val->id])) ? $vv['capacity'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][capacity][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Company</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['company'])) ? ((isset($vv['company'][$val->id])) ? $vv['company'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][company][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                    @else
                                                        @php
                                                            $ikey = ($key == 'safety') ? 'remark' : "value"
                                                        @endphp
                                                        <input type="text"
                                                            value="{{ (isset($vv[$ikey])) ? ((isset($vv[$ikey][$val->id])) ? $vv[$ikey][$val->id] : "" ) : "" }}"
                                                            name="js[{{ $key }}][{{($key == 'safety') ? 'remark' : "value" }}][{{ $val->id }}]" class="form-control required">
                                                    @endif
                                                </td>
                                                @if ($key != "truck")
                                                <td>{{ $val->uom }}</td>
                                                @endif
                                                <td>
                                                    @if ($key == "truck")
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Start ({{ $val->uom }})</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['start'])) ? ((isset($vv['start'][$val->id])) ? $vv['start'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][start][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Stop ({{ $val->uom }})</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['stop'])) ? ((isset($vv['stop'][$val->id])) ? $vv['stop'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][stop][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Total ({{ $val->uom }})</label>
                                                            <input type="text"
                                                                value="{{ (isset($vv['total'])) ? ((isset($vv['total'][$val->id])) ? $vv['total'][$val->id] : "" ) : "" }}"
                                                                name="js[{{ $key }}][total][{{ $val->id }}]" class="form-control required">
                                                        </div>
                                                    @else
                                                        @php
                                                            $selval = (isset($vv['status'])) ? ((isset($vv['status'][$val->id])) ? $vv['status'][$val->id] : "" ) : ""
                                                        @endphp
                                                        <select name="js[{{ $key }}][status][{{ $val->id }}]" class="form-control select2 required" id="">
                                                            <option value="good" {{ ($selval == "good") ? "SELECTED" : "" }}>Good</option>
                                                            <option value="bad" {{ ($selval == "bad") ? "SELECTED" : "" }}>Bad</option>
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
                        @foreach ($activity as $act)
                            <div class="form-group row form-desc">
                                <div class="col-md-3 col-sm-12">
                                    <label for="" class="col-form-label">From</label>
                                    <input type="time" value="{{$act->_from}}" name="activity_from[]" class="form-control desc-from required">
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <label for="" class="col-form-label">To</label>
                                    <input type="time" value="{{$act->_to}}" name="activity_to[]" class="form-control desc-to required">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="" class="col-form-label">Description</label>
                                    <textarea name="description[]" id="" class="form-control tmce" cols="30" rows="10">{!! $act->description !!}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-12 text-right">
                        @if ($type != "appr" && empty($reports->approved_at))
                            <button type="button" id="btn-remove" class="btn btn-danger">Remove</button>
                            <button type="button" id="btn-add-more" class="btn btn-primary">Add More </button>
                        @endif
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <h3>Inventory Record</h3>
                    </div>
                    <div class="col-12">
                        @php
                            $locked_item = 0;
                        @endphp
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-secondary">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Start</th>
                                    <th class="text-center">In</th>
                                    <th class="text-center">Out</th>
                                    <th class="text-center">Balance</th>
                                </tr>
                                <tr id="form-add-item">
                                    <td align="center">New</td>
                                    <td align="center">
                                        <input type="text" id="item_name" class="form-control" placeholder="insert item name">
                                    </td>
                                    <td align="center">
                                        <input type="text" id="item_qty" class="form-control" placeholder="insert quantity">
                                    </td>
                                    <td align="center"></td>
                                    <td align="center"></td>
                                    <td align="center">
                                        <button type="button" id="btn-add-item" class="btn btn-xs btn-primary">Add</button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $opitem)
                                    <tr>
                                        <td align="center">{{ $i+1 }}</td>
                                        <td align="center">{{ $opitem->item_name }}</td>
                                        <td align="center">{{ $opitem->qty }}</td>
                                        <td align="center">
                                            <input type="text" {{ ($opitem->lock == 2 || !empty($reports->approved_at)) ? 'readonly' : "" }} class="form-control qty-in" data-item-id="{{ $opitem->id }}" value="{{ (empty($opitem->in)) ? 0 : $opitem->in }}">
                                        </td>
                                        <td align="center">
                                            <input type="text" {{ ($opitem->lock == 2 || !empty($reports->approved_at)) ? 'readonly' : "" }} class="form-control qty-out" data-item-id="{{ $opitem->id }}" value="{{ (empty($opitem->out)) ? 0 : $opitem->out }}">
                                        </td>
                                        <td align="center">
                                            {{ $opitem->qty + $opitem->in - $opitem->out }}
                                        </td>
                                    </tr>
                                    @php
                                        $locked_item += $opitem->lock;
                                    @endphp
                                @endforeach
                                @php
                                    if($locked_item == 0){
                                        $lock = 1;
                                    } elseif($locked_item == count($items)){
                                        $lock = 2;
                                    } elseif($locked_item > count($items)){
                                        $lock = 3;
                                    }
                                @endphp
                            </tbody>
                            @if ($lock < 3 && empty($reports->approved_at))
                            <tfoot>
                                <tr>
                                    <td align="center" colspan="5">
                                        <button type="button" id="btn-calculate" class="btn btn-primary">Calculate & Save</button>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-{{ ($lock == 1) ? "warning" : "success" }}" id="btn-lock">{{ ($lock == 1) ? "Lock" : "Confirm lock" }}</button>
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <h3>Attachments</h3>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 mb-5">
                                <table class="table table-bordered display">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">File Name</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attach as $i => $file)
                                            @php
                                                $filename = "File";
                                                if(isset($file_management[$file->file_hash])){
                                                    $address = explode("/", $file_management[$file->file_hash]);
                                                    $filename = end($address);
                                                }
                                            @endphp
                                            <tr>
                                                <td align="center">{{ $i+1 }}</td>
                                                <td align="center">{{ $filename }}</td>
                                                <td align="center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('download', $file->file_hash) }}" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-download"></i></a>
                                                        @if ($type != "appr" && empty($reports->approved_at))
                                                            <a href="{{ route('general.operation.attach.delete', $file->id) }}" onclick="return confirm('delete?')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($type != "appr" && empty($reports->approved_at))
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
                        @endif
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    @if ($type == "appr" && empty($reports->approved_at))
                        <div class="col-md-6 col-sm-12"></div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label text-left">Notes </label>
                                <textarea name="appr_notes" id="" class="form-control tmce" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 text-right">
                        @csrf
                        @if (empty($reports->approved_at))
                            <button type="submit" id="btn-post-form" onclick="return confirm('{{ ($type == "appr") ? "Approve" : "Update" }}')" class="btn btn-success">{{ ($type == "appr") ? "Approve" : "Update" }}</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("theme/tinymce/tinymce.min.js") }}"></script>
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
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

            $("table.display").DataTable()

            $("#item_qty").number(true, 0, ',', '')

            $("#btn-add-item").click(function(){
                var name = $("#item_name").val()
                var qty = $("#item_qty").val()
                console.log(name)
                if(name == ""){
                    Swal.fire("Required", "Field Name is required", "warning").then(function(result){
                        if(result.value){
                            $("#item_name").focus()
                        }
                    })
                } else {
                    if(qty == ""){
                        Swal.fire("Required", "Field Quantity is required", "warning").then(function(){
                            if(result.value){
                                $("#item_qty").focus()
                            }
                        })
                    }
                }

                if(name != "" && qty != ""){
                    _post()
                    $.ajax({
                        url : "{{ route('general.operation.item.add') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            item_name : name,
                            item_qty : qty,
                            report_id : '{{ $reports->id }}'
                        },
                        cache : false,
                        success : function(response){
                            swal.close()
                            if(response.success){
                                Swal.fire('Success', response.messages, 'success').then(function(result){
                                    if(result.value){
                                        location.reload()
                                    }
                                })
                            } else {
                                Swal.fire('Error', response.messages, 'error').then(function(result){
                                    if(result.value){
                                        location.reload()
                                    }
                                })
                            }
                        }
                    })
                }
            })

            $("#btn-calculate").click(function(){
                var qty_in = $(".qty-in")
                var qty_out = $(".qty-out")
                var data_qty_in = []
                var data_qty_out = []

                console.log(qty_in)
                qty_in.each(function(){
                    var id = $(this).data('item-id')
                    data_qty_in[id] = $(this).val()
                })

                qty_out.each(function(){
                    var id = $(this).data('item-id')
                    data_qty_out[id] = $(this).val()
                })


                _post()
                $.ajax({
                    url : "{{ route('general.operation.item.calculate') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        qty_in : data_qty_in,
                        qty_out : data_qty_out
                    },
                    cache : false,
                    success : function(response){
                        swal.close()
                            if(response.success){
                                Swal.fire('Success', response.messages, 'success').then(function(result){
                                    if(result.value){
                                        location.reload()
                                    }
                                })
                            } else {
                                Swal.fire('Error', response.messages, 'error').then(function(result){
                                    if(result.value){
                                        location.reload()
                                    }
                                })
                            }
                    }
                })
            })

            @if ($lock > 1)
                $("#form-add-item").hide()
            @endif

            $("#btn-lock").click(function(){
                Swal.fire({
                    title: "Are you sure?",
                    text: "{!! ($lock == 1) ? '' : 'You won\'t able to revert this!' !!}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes"
                }).then(function(result) {
                    if (result.value) {
                        _post()
                        $.ajax({
                            url : "{{ route('general.operation.item.lock', $reports->id) }}",
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                lock : {{ $lock }}
                            },
                            success : function(response){
                                if(response.success){
                                    Swal.fire('Success', response.messages, 'success').then(function(result){
                                        if(result.value){
                                            location.reload()
                                        }
                                    })
                                } else {
                                    Swal.fire('Error', response.messages, 'error').then(function(result){
                                        if(result.value){
                                            location.reload()
                                        }
                                    })
                                }
                            }
                        })
                    }
                });
            })
        })
    </script>
@endsection
