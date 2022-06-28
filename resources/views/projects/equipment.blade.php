@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Project Equipment List</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('marketing.project') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                    <button class="btn btn-primary" data-toggle="modal" id="btn-show-list" data-target="#showListPD"><i class="fa fa-plus"></i> Add List</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h3 class="card-title text-muted">{{ $project->prj_name }}</h3>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pd as $i => $item)
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>
                                        <span class="label label-inline label-primary">{{ $item->project_name }}</span>
                                    </td>
                                    <td align="center">
                                        {{ (isset($category[$item->id])) ? $category[$item->id] : "N/A" }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showListPD" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">List Project Design</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="show-pd">
                            <table class="table table-bordered table-hover" id="table-pd">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Project name</th>
                                        <th class="text-center">Category</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-add-list">Add</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        @if (empty($project->list))
            var list = []
        @else
            var js = "{{ $project->list }}"
            var list = JSON.parse(js.replaceAll("&quot;", "\""))
        @endif

        function add_to_list(x){
            if(x.checked == true){
                list.push(x.value)
            } else {
                const index = list.indexOf(x.value)
                if (index > -1) {
                    list.splice(index, 1);
                }
            }

            console.log(list)
        }

        function post_list(){
            $.ajax({
                url: "{{ route('marketing.project.save_equipments') }}",
                type: "post",
                dataType: "json",
                startTime: performance.now(),
                data : {
                    _token : "{{ csrf_token() }}",
                    list : list,
                    prj : "{{ $project->id }}"
                },
                cache: false,
                success : function(response){
                    //Calculate the difference in milliseconds.
                    var time = performance.now() - this.startTime;

                    //Convert milliseconds to seconds.
                    var seconds = time / 1000;

                    //Round to 3 decimal places.
                    seconds = seconds.toFixed(3);

                    //Write the result to the HTML document.
                    var result = 'AJAX request took ' + seconds + ' seconds to complete.';
                    // document.body.innerHTML = result;
                    //Or log it to the console.
                    var nTimer = 2000 + time
                    Swal.fire({
                        title: 'Proccessing',
                        timer: 1500 + time,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        onOpen: function() {
                            Swal.showLoading()
                        }
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (response.success == true){
                            Swal.fire({
                                title: 'Success',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload()
                                }
                            })
                        } else {
                            Swal.fire('Error occured', 'Please contact your administrator', 'error')
                            $("#btn-add-list").prop("disabled", false)
                            $("#btn-add-list").removeClass('spinner spinner-white spinner-right')
                        }
                    })
                }
            })
        }

        $(document).ready(function(){

            console.log(list)
            $("table.display").DataTable()

            $("#btn-add-list").click(function(event){
                event.preventDefault()
                $(this).addClass('spinner spinner-white spinner-right')
                $(this).prop('disabled', true)
                if(list.length > 0){
                    post_list()
                } else {
                    @if (!empty($project->list))
                        Swal.fire({
                            title: "Are you sure?",
                            text: "There is no items you have choose",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                post_list()
                            }
                        });
                    @else
                        Swal.fire('No data', "At least 1 data is choosen", "warning")
                    @endif
                    $(this).prop('disabled', false)
                }
            })

            $("#table-pd").DataTable({
                ajax : {
                    url: "{{ route('marketing.project.get_equipments', $project->id) }}",
                    type: "GET"
                },
                columns : [
                    {"data" : "ck"},
                    {"data" : "name"},
                    {"data" : "category"},
                ],
                columnDefs : [
                    {targets: [0], className : "text-center ck_td"}
                ]
            })
        })
    </script>
@endsection
