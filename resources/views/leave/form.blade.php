@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        @actionStart('leave_request', 'read')
        <div class="card-header">
            <div class="card-title">
                Leave Request Form
            </div>

        </div>
        <div class="card-body">
            <form action="{{URL::route('leave.submit')}}" id="form-submit" method="POST">
                @csrf
                <div class="col-xl-8">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Name</label>
                        <div class="col-md-4">
                            <select name="emp_id" id="emp_id" class="form-control select2" readonly="">
                                @foreach($employee as $value)
                                    <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2"></label>
                        <div class="col-md-4">
                            <!--<input type="radio" name="cuticheck" id="mass" onclick="checkRadio()" value="{{$absen_bobot->absen_alasan_id}}-mass"> Mass Leave -->
                            &nbsp;
                            <input type="checkbox" name="cuticheck" id="meternity" onclick="checkRadio()" value="{{$absen_bobot->absen_alasan_id}}-meternity" required> Maternity Leave
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Start Leave</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">End Leave</label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Reason</label>
                        <div class="col-md-4">
                            <textarea name="reason" id="" cols="50" rows="10"></textarea>
                        </div>
                    </div>
                    @actionStart('leave_request', 'create')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right"></label>
                        <div class="col-md-4">
                            <button type="button" id="btnsubmit" class="btn btn-primary"> Submit</button>
                        </div>
                    </div>
                    @actionEnd
                </div>
            </form>
        </div>
        @actionEnd
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('assets/js/pages/features/miscellaneous/sweetalert2.js?v=7.0.5')}}"></script>
    <script>
        function checkRadio(){
            if ($("#mass").is(':checked')){
                $("#emp_id").prop('disabled', true)
            } else {
                $("#emp_id").prop('disabled', false)
            }
        }
        $(document).ready(function(){

            $("select.select2").select2({
                width: "100%"
            })

            function parseDate(str) {
                var mdy = str.split('-');
                return new Date(mdy[0], mdy[1]-1, mdy[2]);
            }

            function datediff(first, second) {
                // Take the difference between the dates and divide by milliseconds per day.
                // Round to nearest whole number to deal with DST.
                return Math.round((second-first)/(1000*60*60*24));
            }
            $("#btnsubmit").click(function(){
                var id = $("#emp_id option:selected").val();
                console.log(id)
                if ($("#mass").is(':checked')){
                    $("#form-submit").submit()
                } else {
                    $.ajax({
                        url : '{{URL::route('leave.checkcuti')}}',
                        data : {
                            _token: "{{csrf_token()}}",
                            id: id
                        },
                        type: "POST",
                        dataType: 'json',
                        cache: false,
                        success: function(response){
                            var cuti = response.jumlah_cuti
                            var from_date = $("#start_date").val()
                            var to_date   = $("#end_date").val()
                            var num = datediff(parseDate(from_date), parseDate(to_date))
                            console.log($("#start_date").val())
                            console.log($("#end_date").val())
                            console.log(num)
                            if (cuti == 0){
                                var message = "Your quota for leave is " + cuti + " day(s)"
                                Swal.fire("Sorry", message, "error")
                            } else {
                                if (num > cuti) {
                                    var message = "Your total leave days is exceed the limit of your quota leave. <br> \n Your quota leave is " + cuti + " day(s)"
                                    Swal.fire("Sorry", message, "warning")
                                } else {
                                    $("#form-submit").submit()
                                }
                            }

                            console.log(response)
                        }
                    })
                }
            })
        })
    </script>
@endsection
