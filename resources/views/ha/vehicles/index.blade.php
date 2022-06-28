@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header card-header-tabs-line">
            <h3 class="card-title">Asset Vehicles</h3>
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link {!! (\Session::has('msg')) ? ((\Session::get('tab') == "vehicle") ? "active" : "") : "active" !!}" data-toggle="tab" href="#tabvehicles">Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {!! (\Session::has('msg')) ? ((\Session::get('tab') == "paper") ? "active" : "") : "" !!}" data-toggle="tab" href="#tabpapers">Papers</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade {!! (\Session::has('msg')) ? ((\Session::get('tab') == "vehicle") ? "show active" : "") : "show active" !!}" id="tabvehicles" role="tabpanel" aria-labelledby="kt_tab_pane_2">
            @include('ha.vehicles._vehicles')
        </div>
        <div class="tab-pane fade {!! (\Session::has('msg')) ? ((\Session::get('tab') == "paper") ? "show active" : "") : "" !!}" id="tabpapers" role="tabpanel" aria-labelledby="kt_tab_pane_2">
            @include('ha.vehicles._papers')
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/assets/js/pages/features/miscellaneous/toastr.js?v=7.0.5')}}"></script>
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function add_paper(){
            $("#add-paper").show()
            $("#add-vehicle :input").attr('disabled', true)
            tinymce.get('ve-spec').getBody().setAttribute('contenteditable', 'false');
        }

        function cancel_add_paper(){
            $("#add-paper").hide()
            $("#add-vehicle :input").attr('disabled', false)
            tinymce.get('ve-spec').getBody().setAttribute('contenteditable', 'true');
            $("#stnk").val("").trigger("change")
        }

        function show_toast(type, msg) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            toastr.success(msg, type);
        }

        function post_paper(){
            console.log(tinymce.get('paper-spec').getContent())
            $.ajax({
                url: "{{route('ha.ve.add.paper')}}",
                type: "post",
                dataType: "json",
                data : {
                    _token : "{{csrf_token()}}",
                    _action: "ajax",
                    paper_number : $("#paper-number").val(),
                    paper_name : $("#paper-name").val(),
                    paper_date : $("#paper-date").val(),
                    paper_value : $("#paper-value").val(),
                    paper_owner : $("#paper-owner").val(),
                    paper_holder : $("#paper-holder").val(),
                    paper_spec : tinymce.get('paper-spec').getContent(),
                    stnk_y_c : $("#stnk-y-c").val(),
                },
                success: function (response) {
                    if (response.success === false){
                        var errors = response.errors
                        for (const i in errors) {
                            $("#"+i.replaceAll("_","-")).addClass('is-invalid')
                        }
                    } else {
                        show_toast('Success', response.messages)
                        cancel_add_paper()
                    }
                }
            })
        }

        function delete_vehicles(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('ha.ve.delete.vehicle')}}/"+x
                }
            })
        }

        function delete_paper(x) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = "{{route('ha.ve.delete.paper')}}/"+x
                }
            })
        }

        function table_vehicles(cate){
            $("#table-vehicle").DataTable().destroy()
            $("#table-vehicle").DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                ajax : {
                    url: "{{route('ha.ve.vehicle.js')}}?_c="+cate,
                    type: "get"
                },
                columns : [
                    {'data': 'i'},
                    {'data': 'name'},
                    {'data': 'paper'},
                    {'data': 'paper_no'},
                    {'data': 'paper_holder'},
                    {'data': 'bpkb_no'},
                    {'data': 'used_by'},
                    {'data': 'location'},
                    {'data': 'description'},
                    {'data': 'vendor'},
                    {'data': 'phone'},
                    {'data': 'exp_date'},
                    {'data': 'action'},
                ],
                columnDefs: [
                    { targets: [1], className: "text-left" },
                    { targets: [12], className: "text-nowrap" },
                    { targets: "_all", className: "text-center" }
                ]
            })
        }

        function edit_vehicles(x){
            $.ajax({
                url: "{{route('ha.ve.find.vehicle')}}/"+x,
                type: "get",
                success: function (response) {
                    $("#edit-vehicle").html(response)
                    $("#edit-vehicle select").select2({
                        width: "100%"
                    })
                    console.log('here it is')
                    tinymce.init({
                        selector: "#edit-vehicle textarea",
                        toolbar: false
                    })
                }
            })
        }

        function modal_picture(x){
            $("#id_paper").val(x)
        }

        function edit_paper(x){
            $("#paper-edit").html("")
            $.ajax({
                url: "{{route('ha.ve.find.paper')}}/"+x,
                type: "get",
                success: function (response) {
                    $("#paper-edit").html(response)
                    $("#paper-edit select").select2({
                        width: "100%"
                    })
                    tinymce.init({
                        selector: "textarea",
                        toolbar: false
                    })
                    $(".number").number(true, 2)
                }
            })
        }

        $(document).ready(function () {
            $(".required").each(function () {
                $(this).change(function () {
                    if ($(this).val() != "" || $(this).val() != undefined || $(this).val() != null){
                        $(this).addClass('is-valid')
                    }
                })
            })

            $("#add-paper").hide()

            $("#stnk-y-c").inputmask({
                mask: "9999/9{1,20} CC/A{1,20}[ A{2,20}][ A{1,20}]",
            })
            $("#stnk-y-c-paper").inputmask({
                mask: "9999/9{1,20} CC/A{1,20}[ A{2,20}][ A{1,20}]",
            })

            $(".number").number(true, 2)


            var ve_spec = tinymce.init({
                selector : "#ve-spec",
                menubar: false
            })

            $("select.select2").select2({
                width: "100%"
            })

            $("#view-cat").select2({
                width: "100%",
                ajax: {
                    url: "{{route('division.js')}}",
                    dataType: "json"
                }
            })

            $("#stnk").select2({
                width: "100%",
                ajax : {
                    url: "{{route('ha.ve.paper.js')}}?_action=add",
                    dataType: "json"
                }
            })

            $("#stnk").change(function () {
                var sel = $(this).val()
                if (sel === "new"){
                    add_paper()
                }
            })

            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100
            });

            table_vehicles('all')

            @if(\Session::has('msg'))
            show_toast('Success', '{{Session::get('msg')}}')
            @endif

        })

    </script>
@endsection
