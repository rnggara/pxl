@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">SP # :&nbsp;<a href="{{ route('treasure.sp.view', $sp->id) }}">{{ $sp->num }}</a></h3>
            <div class="card-toolbar">
                <a href="{{ route('treasure.sp.index', $sp->bank) }}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-hover table-bordered" id="table-sp-list">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Activity</th>
                                <th class="text-center">Credit</th>
                                <th class="text-center">Debit</th>
                                <th class="text-center">
                                    <button type="button" class="btn btn-primary" id="btn-save">Save</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        var sp = []
        var spdate = []
        function addToSP(x) {
            var val = x.value.split(" ")
            if (x.checked == true){
                sp.push(parseInt(val[0]))
                spdate.push(val[1])
            } else {
                var index = sp.indexOf(parseInt(val[0]))
                var indexsp = spdate.indexOf(val[1])
                if (index >= 0){
                    sp.splice(index, 1)
                }
                if (indexsp >= 0){
                    spdate.splice(indexsp, 1)
                }
            }
        }
        function table_sp(){
            $("#table-sp-list").DataTable({
                pageLength: 100,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                ajax: {
                    url: "{{ route('treasure.sp.historyjs') }}",
                    type: "post",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        hist: "{{ $sp->bank }}",
                        sp_id: "{{ $sp->id }}"
                    }
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "date"},
                    {"data" : "activity"},
                    {"data" : "credit"},
                    {"data" : "debit"},
                    {"data" : "sp"},
                ],
                columnDefs : [
                    {targets: [1], className: "text-nowrap"},
                    {targets: [3,4], className: "text-right"},
                    {targets: [0,1,5], className: "text-center"},
                ],
                initComplete: function(settings, json){
                    // var spCheck = $(".sp-check").toArray()
                    // $(".sp-check").each(function(){
                    //     if ($(this).prop('checked') == true) {
                    //         sp.push($(this).val())
                    //     }

                    // })
                    console.log(sp)
                },
                createdRow: function( row, data, dataIndex){
                    // console.log(data)
                    if( data['checked'] ==  1){
                        $(row).addClass('bg-light-success');
                        sp.push(data['id'])
                    }
                    // console.log(sp)
                }
            })

        }
        $(document).ready(function(){
            table_sp()
            $("#btn-save").click(function(){
                if (sp.length == 0){
                    Swal.fire('No entries choosen', 'Please choose entries from the history', 'warning')
                } else {

                    $.ajax({
                        url: "{{route('treasure.sp.add.input')}}",
                        type: "post",
                        dataType: "json",
                        startTime: performance.now(),
                        cache: false,
                        data: {
                            _token: "{{csrf_token()}}",
                            sp: sp,
                            sp_id: "{{$sp->id}}",
                            bank: "{{ $sp->bank }}"
                        },
                        success: function (response) {
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
                                didOpen: () => {
                                    Swal.showLoading()
                                    timerInterval = setInterval(() => {
                                    const content = Swal.getContent()
                                    if (content) {
                                        const b = content.querySelector('b')
                                        if (b) {
                                        b.textContent = Swal.getTimerLeft()
                                        }
                                    }
                                    }, 100)
                                }
                                }).then((result) => {
                                /* Read more about handling dismissals below */
                                if (response.error == 0){
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
                                }
                            })

                        }
                    })
                }
            })
            $(".number").number(true, 2)

        })
    </script>

@endsection
