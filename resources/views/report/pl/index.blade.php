@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">PL Report</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="mx-auto">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-3">Year : </label>
                        <div class="col-9">
                            <div class="input-group">
                                <input type="text" class="form-control number" id="year" value="{{ date("Y") }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="btn-search"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-solid separator-border-2 separator-dark"></div>
            <div class="row" id="show-project">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        var list_project = []

        function push_list_project(x){
            if($(x).prop('checked') === true){
                list_project.push($(x).val())
            } else {
                const index = list_project.indexOf($(x).val())
                if (index > -1) {
                    list_project.splice(index, 1);
                }
            }
        }

        $(document).ready(function(){
            console.log(list_project)
            $("#year").inputmask("9999", {
                "placeholder" : "YYYY",
                "autoUnmask" : true
            })

            $("#btn-search").click(function(){
                $(this).addClass('spinner spinner-right spinner-white')
                $(this).prop('disabled', true)
                $.ajax({
                    url : "{{ route('report.pl.find') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        year : $("#year").val()
                    },
                    dataType : "json",
                    startTime: performance.now(),
                    cache : false,
                    success : function(response){
                        var time = performance.now() - this.startTime;
                        Swal.fire({
                            title: "Searching Data",
                            timer: 1500 + time,
                            onOpen: function() {
                                Swal.showLoading()
                            }
                        }).then(function(result) {
                            if(result.dismiss === "timer") {
                                console.log(response)
                                if(response.search == false){
                                    $.ajax({
                                        url : "{{ route('report.pl.list.project') }}",
                                        type : "post",
                                        data : {
                                            _token : "{{ csrf_token() }}",
                                            year : $("#year").val()
                                        },
                                        cache: false,
                                        startTime : performance.now(),
                                        success : function(response){
                                            var nTime = performance.now() - this.startTime

                                            Swal.fire({
                                                "title" : "Listing Project",
                                                timer : 1500 + nTime,
                                                onOpen : function(){
                                                    Swal.showLoading()
                                                }
                                            }).then(function(result) {
                                                if(result.dismiss == "timer"){
                                                    $("#show-project").html(response)
                                                    var table = $("#show-project").find("table.display")
                                                    table.each(function(){
                                                        var tbody = $(this).find("tbody")
                                                        var tr = $(tbody).find("tr")
                                                        tr.each(function(){
                                                            $(this).click(function(){
                                                                var ck = $(this).find("input[type=checkbox]")
                                                                ck.prop("checked", !ck.prop('checked'))
                                                                push_list_project(ck)
                                                            })

                                                        })
                                                    })
                                                    table.DataTable({
                                                        lengthChange: false
                                                    })

                                                    $("#btn-submit").click(function(){
                                                        if (list_project.length > 0) {
                                                            $(this).addClass("spinner spinner-white spinner-right")
                                                            $(this).prop('disabled', true)
                                                            $.ajax({
                                                                url : "{{ route('report.pl.add') }}",
                                                                type: "POST",
                                                                dataType : "json",
                                                                startTime : performance.now(),
                                                                cache : false,
                                                                data : {
                                                                    _token : "{{ csrf_token() }}",
                                                                    projects : list_project,
                                                                    year : $("#year-post").val()
                                                                },
                                                                success : function(response){
                                                                    var mTime = performance.now() - this.startTime
                                                                    Swal.fire({
                                                                        "title" : "Proccessing",
                                                                        timer : 1500 + mTime,
                                                                        onOpen : function(){
                                                                            Swal.showLoading()
                                                                        }
                                                                    }).then(function(result){
                                                                        if(result.dismiss == "timer"){
                                                                            if (response.success == true) {
                                                                                Swal.fire({
                                                                                    title: "Success",
                                                                                    text: "Prognosis Created",
                                                                                    icon: "success",
                                                                                    showCancelButton: false,
                                                                                    confirmButtonText: "OK",
                                                                                }).then(function(result) {
                                                                                    if (result.value) {
                                                                                        window.location.href = response.link
                                                                                    }
                                                                                });
                                                                            } else {
                                                                                Swal.fire({
                                                                                    title: "Error",
                                                                                    text: "Please contact your system administrator",
                                                                                    icon: "error",
                                                                                    showCancelButton: false,
                                                                                    confirmButtonText: "OK",
                                                                                }).then(function(result) {
                                                                                    if (result.value) {
                                                                                        location.reload()
                                                                                    }
                                                                                });
                                                                            }
                                                                        }
                                                                    })
                                                                }
                                                            })
                                                        }
                                                    })
                                                }

                                                $("#btn-search").removeClass("spinner spinner-white spinner-right")
                                                $("#btn-search").prop('disabled', false)
                                            })
                                        }
                                    })
                                } else {
                                    window.location.href = response.link
                                }
                            }
                        })
                    }
                })
            })
        })
    </script>
@endsection
