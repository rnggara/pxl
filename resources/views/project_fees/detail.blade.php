@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>{{$project->prj_name}} - Fees</h3>
            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-left" colspan="4">Project Value : {{strtoupper($project->currency)}} {{number_format($project->value, 2)}}</th>
                    </tr>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" class="text-left">Name</th>
                        <th nowrap="nowrap" class="text-right">Type</th>
                        <th nowrap="nowrap" class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total_fee = 0; ?>
                        @foreach($associates as $key => $value)
                            <tr>
                                <td align="center">{{$key + 1}}</td>
                                <td>{{(isset($user[$value->id_user])) ? $user[$value->id_user]['name'] : "N/A"}}</td>
                                <td align="center">{{($value->fee_type == 1) ? "Fixed amount" : "% contract value"}}</td>
                                <td align="right">{{$project->currency}} {{number_format($value->fee_amount)}}</td>
                            </tr>
                            <?php
                            /** @var TYPE_NAME $value */
                            $total_fee += $value->fee_amount;
                            ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right">{{strtoupper($project->currency)}} {{number_format($total_fee, 2)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if(empty($project->fee_approve_at))
                        <h3>Approve?</h3>
                        <form action="{{route('hrd.project_fees.approve')}}" id="form-approve" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <textarea name="notes" class="form-control" cols="30" rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-9">
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <input type="hidden" name="id_project" value="{{$project->id}}">
                                            <button type="button" id="btn-approve" class="btn btn-xs btn-primary">Approve</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{route('hrd.project_fees.pay')}}" id="form-approve" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-md-3">Bank</label>
                                        <div class="col-md-9">
                                            <select name="source" class="form-control select2" id="source" required>
                                                <option value="">Select Source</option>
                                                @foreach($treasury as $item)
                                                    <option value="{{$item->id}}">{{$item->currency}} - {{$item->source}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <input type="hidden" name="id_project" value="{{$project->id}}">
                                            <button type="button" id="btn-approve" class="btn btn-xs btn-primary">Pay</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>

        $(document).ready(function () {
            $("#btn-approve").click(function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @if(empty($project->fee_approve_at))
                        $("#form-approve").submit()
                        @else
                        if ($("#source").val() === "" || $("#source").val() === undefined || $("#source").val() === null){
                            Swal.fire('Empty Source', 'Please select source', 'error')
                        } else {
                            $("#form-approve").submit()
                        }
                        @endif
                    }
                })
            })

            $('.display').DataTable({
                responsive: true,
                ordering: false,
                paging: false,
                bInfo: false,
                searching: false,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
            $("select.select2").select2({
                width: "100%"
            })

            tinymce.init({
                editor_selector : ".form-control",
                selector:'textarea',
                mode : "textareas",
                menubar: false,
                // toolbar: false
            });

        });



    </script>
@endsection
