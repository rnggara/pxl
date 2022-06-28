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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            Logo
                        </div>
                        <div class="col-md-6 text-right">
                            <h3><b>{{\Session::get('company_name_parent')}}</b></h3>
                        </div>
                    </div>
                    <div class="row mx-auto">
                        <div class="col-md-12 text-center">
                            <h2><b>Budget Cost Requisition</b></h2>
                            <h4><b>{{$br->no_br}}</b></h4>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td>Date</td>
                                            <td>: {{date('d-m-Y', strtotime($br->input_date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Subject</td>
                                            <td>: {{$br->subject}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td align="right">
                                    <table>
                                        <tr>
                                            <td>Due Date</td>
                                            <td>: {{date('d-m-Y', strtotime($br->due_date))}}</td>
                                        </tr>
                                        <tr>
                                            <td>Currency</td>
                                            <td>: {{$br->currency}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Project</th>
                                        <th class="text-center">Remark</th>
                                        <th class="text-center">Budget Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7"><b>Cash In</b></td>
                                    </tr>
                                    <?php $amount = 0; ?>
                                    @foreach($details as $item)
                                        @if($item->cashin > 0)
                                            <tr>
                                                <td align="center">*</td>
                                                <td align="center">{{strip_tags($item->description)}}</td>
                                                <td align="center">{{(isset($prj[$item->project])) ? $prj[$item->project]->prj_name : ""}}</td>
                                                <td align="right">{{strip_tags($item->remarks)}}</td>
                                                <td align="right">{{number_format($item->cashin, 2)}}</td>
                                            </tr>
                                            <?php /** @var TYPE_NAME $item */
                                            $amount += $item->cashin; ?>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="7"><b>Cash Out</b></td>
                                    </tr>
                                    @foreach($details as $item)
                                        @if($item->cashout > 0)
                                            <tr>
                                                <td align="center">*</td>
                                                <td align="center">{{strip_tags($item->description)}}</td>
                                                <td align="center">{{(isset($prj[$item->project])) ? $prj[$item->project]->prj_name : ""}}</td>
                                                <td align="right">{{strip_tags($item->remarks)}}</td>
                                                <td align="right">{{number_format($item->cashout, 2)}}</td>
                                            </tr>
                                            <?php /** @var TYPE_NAME $item */
                                            $amount -= $item->cashout; ?>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <td align="center" colspan="4"><b>Totals</b></td>
                                        <td align="right"><b>{{number_format($amount, 2)}}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @if($approve == 0)
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Approval</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" action="{{route('finance.br.approve')}}">
                                    @csrf
                                    @if(!in_array($action, ['budget', 'balance_recv']))
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-md-3">Notes</label>
                                            <div class="col-md-9">
                                                <textarea name="notes" class="form-control" id="" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-md-3"></label>
                                        <div class="col-md-9">
                                            <input type="hidden" name="action" value="{{$action}}">
                                            <input type="hidden" name="id_br" value="{{$br->id}}">
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
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

