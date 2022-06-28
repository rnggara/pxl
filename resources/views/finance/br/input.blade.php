@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Input - {{$br->subject}}</h3><br>
            </div>
            <div class="card-toolbar">
                <a href="{{route('finance.br.index')}}" class="btn btn-sm btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label">No</label>
                                <label class="col-md-9 col-form-label">: <b>{{$br->no_br}}</b></label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">Date</label>
                                <label class="col-md-9 col-form-label">: {{date('d-m-Y', strtotime($br->input_date))}}</label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">Subject</label>
                                <label class="col-md-9 col-form-label">: {{date('d-m-Y', strtotime($br->subject))}}</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-md-3 col-form-label">Due Date</label>
                                <label class="col-md-9 col-form-label">: {{date('d-m-Y', strtotime($br->due_date))}}</label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">Currency</label>
                                <label class="col-md-9 col-form-label">: {{$br->currency}}</label>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">Division</label>
                                <label class="col-md-9 col-form-label">: {{$division->name}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr class="bg-info text-white">
                            <th class="text-center">Description</th>
                            <th class="text-center">Project</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Amount Requested</th>
                            <th class="text-center">

                            </th>
                        </tr>
                        <tr class="bg-secondary">
                            <th class="text-center" colspan="4">BR Entry (Cash In)</th>
                            <th class="text-center">
                                <button type="button" data-toggle="modal" data-target="#cashinModal" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-plus"></i></button>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sum = 0; ?>
                        @foreach($details as $i => $item)
                            @if($item->cashin > 0)
                                <tr>
                                    <td>{{strip_tags($item->description)}}</td>
                                    <td>{{(isset($prj[$item->project])) ? $prj[$item->project]->prj_name : ""}}</td>
                                    <td>{{strip_tags($item->remarks)}}</td>
                                    <td align="right">{{number_format($item->cashin, 2)}}</td>
                                    <td align="center">
                                        <button type="button" onclick="delete_entry('{{$item->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php /** @var TYPE_NAME $item */
                                $sum += $item->cashin; ?>
                            @endif
                        @endforeach
                        @if(!empty($br->released_approved_at))
                            <tr>
                                <th class="text-center bg-secondary" colspan="5">Cash Out</th>
                            </tr>
                            @foreach($wo_type as $item)
                                <tr class="bg-secondary">
                                    <th class="" colspan="4">{{$item->name}}</th>
                                    <th class="text-center">
                                        <button type="button" onclick="cashout_modal('{{$item->id}}')" data-toggle="modal" data-target="#cashoutModal" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-plus"></i></button>
                                    </th>
                                </tr>
                                @foreach($details as $i => $detail)
                                    @if($detail->cashout > 0 && $detail->category == $item->id)
                                        <tr>
                                            <td>{{strip_tags($detail->description)}}</td>
                                            <td>{{(isset($prj[$detail->project])) ? $prj[$detail->project]->prj_name : ""}}</td>
                                            <td>{{strip_tags($detail->remarks)}}</td>
                                            <td align="right">{{number_format($detail->cashout, 2)}}</td>
                                            <td align="center">
                                                <button type="button" onclick="delete_entry('{{$detail->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php /** @var TYPE_NAME $detail */
                                        $sum -= $detail->cashout; ?>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <td align="center" colspan="3"><b>Total</b></td>
                            <td align="right"><b>{{number_format($sum, 2)}}</b></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cashinModal" tabindex="-1" role="dialog" aria-labelledby="requestForm" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Entry </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('finance.br.post_entry')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Category</label>
                                    <div class="col-md-9">
                                        <select name="project" class="form-control select2" id="" style="width: 100%" required>
                                            <option value="">Select Category</option>
                                            @foreach($projects as $item)
                                                <option value="{{$item->id}}">{{$item->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Remarks</label>
                                    <div class="col-md-9">
                                        <textarea name="remarks" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Amount</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control number" name="amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="company_id" value="{{\Session::get('company_id')}}">
                        <input type="hidden" name="id_br" value="{{$br->id}}">
                        <input type="hidden" name="type" id="type" value="cashin">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cashoutModal" tabindex="-1" role="dialog" aria-labelledby="requestForm" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Entry </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('finance.br.post_entry')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Category</label>
                                    <div class="col-md-9">
                                        <select name="project" class="form-control select2" id="" style="width: 100%" required>
                                            <option value="">Select Category</option>
                                            @foreach($projects as $item)
                                                <option value="{{$item->id}}">{{$item->prj_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Remarks</label>
                                    <div class="col-md-9">
                                        <textarea name="remarks" class="form-control" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-md-3">Amount</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control number" name="amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="company_id" value="{{\Session::get('company_id')}}">
                        <input type="hidden" name="id_br" value="{{$br->id}}">
                        <input type="hidden" name="type" value="cashout">
                        <input type="hidden" name="category" id="category-hide">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('assets/jquery-number/jquery.number.js')}}"></script>
    <script>
        function delete_entry(x){
            $.ajax({
                url: "{{route('finance.br.delete_entry')}}/"+x,
                type: "get",
                dataType: "json",
                success: function (response) {
                    if (response.delete === 1){
                        location.reload()
                    } else {
                        Swal.fire('Error occured', 'Please contact your system administration', 'error')
                    }
                }
            })
        }
        function cashout_modal(x){
            $("#category-hide").val(x)
        }
        $(document).ready(function(){
            tinymce.init({
                selector: 'textarea',
                a_plugin_option: true,
                a_configuration_option: 400,
                menubar: false,
                toolbar: false
            });
            $("select.select2").select2({
                width: "100%"
            })
            $("input.number").number(true, 2)
        })
    </script>
@endsection

