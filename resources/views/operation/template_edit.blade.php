@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Template Setting</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" id="btn-save" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Save</button>
                    <a href="{{ route('general.operation.templates') }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 border mx-auto">
                     <div class="form-group">
                         <label class="col-form-label">Report Title</label>
                         <input type="text" class="form-control" value="{{ $title ?? "" }}" id="report-title" placeholder="Default : Field Report">
                     </div>
                     <div class="form-group row">
                        <label for="" class="col-form-label col-md-1 col-sm-2">
                            Logo 1
                        </label>
                        <div class="col-md-8 col-sm-8 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" {{ ($logo_1 == "0") ? "" : "checked" ?? "checked" }} class="cb" name="cb_logo_1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-1 col-sm-2">
                            Logo 2
                        </label>
                        <div class="col-md-8 col-sm-8 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" {{ ($logo_2 == "0") ? "" : "checked" ?? "checked" }} class="cb" name="cb_logo_2"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mx-auto">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mx-auto border p-5">
                    <h3>Header</h3>
                    <hr>
                    <div class="row draggable-zone-header">
                        @foreach ($_header as $item)
                            <div class="col-4 draggable-header">
                                <div class="card card-custom gutter-b shadow shadow-lg draggable-handle-header">
                                    <div class="card-body cursor-move">
                                        <h1 class="text-center form-header" data-key="{{ $item }}">{{ ucwords(str_replace("_", " ", $item)) }}</h1>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mx-auto">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mx-auto border p-5 draggable-zone">
                    <h3>Content</h3>
                    <hr>
                    @php
                        $settings = json_decode($template->settings, true);
                        $row = [];
                        if(!empty($template->layout_order)){
                            $layout_order = json_decode($template->layout_order, true);
                            foreach ($layout_order as $key => $value) {
                                if(isset($settings[strtolower($value)])){
                                    $row[strtolower($value)] = $settings[strtolower($value)];
                                }
                            }
                            $settings = $row;
                        }

                        // dd($settings, $row);
                    @endphp

                    @foreach ($settings as $key => $item)
                        @if ($item == 1)
                            <div class="card card-custom gutter-b bg-light-white draggable shadow">
                                <div class="card-header">
                                    <h3 class="card-title form-settings">{{ ucwords($key) }}</h3>
                                    <div class="card-toolbar">
                                        @if ($key == "record")
                                            <button type="button" id="btn-collapse" class="btn btn-icon btn-sm btn-hover-light-primary" data-toggle="collapse" data-target="#collapseActivity">
                                                <i class="fa fa-chevron-right"></i>
                                            </button>
                                        @endif
                                        <a href="#" class="btn bn-icon btn-sm btn-hover-light-primary draggable-handle"><i class="fa fa-arrows-alt-v"></i></a>
                                    </div>
                                </div>
                                @if ($key == "record")
                                    <div class="card-body collapse" id="collapseActivity">
                                        <div class="row">
                                            <div class="col-md-12 draggable-zone-activity">
                                                @php
                                                    $row = [];
                                                    if (!empty($template->layout_activity)) {
                                                        $layout_activity = json_decode($template->layout_activity, true);
                                                        foreach ($layout_activity as $act) {
                                                            if(isset($_category[$act])){
                                                                $row[$act] = $_category[$act];
                                                            }
                                                        }
                                                        $_category = $row;
                                                    }
                                                @endphp
                                                @foreach ($_category as $keycat => $cat)
                                                    <div class="card card-custom shadow mb-1 draggable-activity">
                                                        <div class="card-header">
                                                            <h3 class="card-title form-post-activity" data-key="{{$keycat}}">{{ $cat }}</h3>
                                                            <div class="card-toolbar">
                                                                <a href="#" class="btn bn-icon btn-sm btn-hover-light-primary draggable-handle-activity"><i class="fa fa-arrows-alt-v"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("theme/assets/plugins/custom/draggable/draggable.bundle.js") }}" type="text/javascript"></script>
    <script>

    var KTCardDraggable = function () {
        return {
            //main function to initiate the module
            init: function () {
                var containers = document.querySelectorAll('.draggable-zone');

                if (containers.length === 0) {
                    return false;
                }

                var swappable = new Sortable.default(containers, {
                    draggable: '.draggable',
                    handle: '.draggable .draggable-handle',
                    mirror: {
                        appendTo: 'body',
                        constrainDimensions: true
                    }
                });
            }
        };
    }();

    jQuery(document).ready(function () {
        KTCardDraggable.init();
    });

    function _set_draggable(section){
        var containers = document.querySelectorAll('.draggable-zone-'+section);

        if (containers.length === 0) {
            return false;
        }

        var swappable = new Sortable.default(containers, {
            draggable: '.draggable-'+section,
            handle: '.draggable-'+section+' .draggable-handle-'+section,
            mirror: {
                appendTo: 'body',
                constrainDimensions: true
            }
        });
    }


        $(document).ready(function(){
            $("#btn-save").click(function(){
                console.log("save clicked")
                var form_post = $(".form-settings")
                var row = []
                var rowact = []
                var rowheader = []

                form_post.each(function(){
                    row.push($(this).text())
                })

                var form_act = $(".form-post-activity")
                form_act.each(function(){
                    rowact.push($(this).data('key'))
                })

                var form_header = $(".form-header")
                form_header.each(function(){
                    rowheader.push($(this).data('key'))
                })


                try {
                    cb1 = $("input[name=cb_logo_1]")
                    var_logo_1 = 0;
                    if(cb1.prop('checked') == true){
                        var_logo_1 = 1;
                    }

                    cb2 = $("input[name=cb_logo_2]")
                    var_logo_2 = 0;
                    if(cb2.prop('checked') == true){
                        var_logo_2 = 1;
                    }

                    $.ajax({
                        url : "{{ route('general.operation.templates.update_layout') }}",
                        type : "POST",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            layout : row,
                            activity : rowact,
                            header : rowheader,
                            title : $("#report-title").val(),
                            logo_1 : var_logo_1,
                            logo_2 : var_logo_2,
                            id_template : '{{ $template->id }}',
                        },
                        cache : false,
                        beforeSend : function() {
                            $(this).attr("disabled", true).text("Loading...")
                        },
                        success : function(response){
                            swaltype = ""
                            swaltitle = ""
                            if(response.success){
                                swaltype = "success"
                                swaltitle = "Success"
                            } else {
                                swaltype = "error"
                                swaltitle = "Error"
                            }

                            Swal.fire(swaltitle, response.message, swaltype).then((res) => {
                                if(res.isConfirmed){
                                    location.reload()
                                }
                            })
                        },
                        error : function(xhr, status, error){
                            var err = eval("(" + xhr.responseText + ")");
                            Swal.fire("error", err.message, "error")
                        }
                    })
                } catch (error) {
                    Swal.fire("error", error.message, "error")
                }
            })

            $("#btn-collapse").click(function(){
                var card = $(this).parents("div.card")
                var body = card.find("div.card-body")
                var icon = $(this).children()
                if(!body.hasClass('show')){
                    icon.attr('style', 'transform : rotate(90deg)')
                } else {
                    icon.attr('style', 'transform : rotate(0deg)')
                }
            })

            _set_draggable('activity')
            _set_draggable('header')

            // var containers = document.querySelectorAll('.draggable-zone-activity');

            // if (containers.length === 0) {
            //     return false;
            // }

            // var swappable = new Sortable.default(containers, {
            //     draggable: '.draggable-activity',
            //     handle: '.draggable-activity .draggable-handle-activity',
            //     mirror: {
            //         appendTo: 'body',
            //         constrainDimensions: true
            //     }
            // });
        })
    </script>
@endsection
