@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3>SOP of <span class="text-primary">{{$sop_main->topic}}</span> </h3>
            </div>

            <div class="card-toolbar">
                <!--end::Button-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>Add SOP</button>--}}
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-offset-1 col-md-10">
                    <div class="">
                        <br>
                        <table width="100%">
                            <tr>
                                <td width="50%">
                                    <img src="{{str_replace("public", "public_html", asset('images/'.\Session::get('company_app_logo')))}}" width="150px">
                                </td>
                                <td  class="text-right">
                                    <h4>
                                        <?= Session::get('company_name_parent') ?>
                                    </h4>

                                </td>
                            </tr>
                        </table>
                        <h2 class="text-center">
                            <?= $sop_main->topic ?>
                        </h2>
                        <h4 class="text-center">
                            <?= $sop_detail->id."/".strtoupper(Session::get('company_tag'))."-SOP/".date("m/y",strtotime($sop_detail->date_detail)) ?>
                        </h4>
                        <br>
                        <hr>
                        <table class="table">
                            <thead>
                            <tr>
                                <th><h4>INDONESIAN</h4></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <p><?= $sop_detail->content ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <table class="table">
                            <thead>
                            <tr>
                                <th><h4>ENGLISH</h4></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <p><?= $sop_detail->content_eng ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <br>
                        <table width="100%">
                            <tr>
                                <td width="33%" class="text-center">
                                    Prepared By
                                </td>
                                <td  width="33%" class="text-center">
                                    Acknowledged By
                                </td>
                                <td  width="33%" class="text-center">
                                    Approved By
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">

                                </td>
                                <td class="text-center">

                                </td>
                                <td class="text-center">

                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <?php if($sop_detail->created_by != null) : ?>
                                    <b><?= $sop_detail->created_by ?></b>
                                    <?php else : ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($sop_detail->acknowledge_by != null) : ?>
                                    <b><?= $sop_detail->acknowledge_by ?></b>
                                    <?php elseif(isset($act) && $act == 'ack') : ?>
                                    <a href="{{route('sop.approval_detail',['id_detail' => $sop_detail->id,'id_main' => $sop_main->id,'act'=> $act])}}" onclick="return confirm('Acknowledge this data?')" class="btn btn-secondary btn-lg"> Acknowledge Here</a>
                                    <?php else : ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($sop_detail->approved_by != null) : ?>
                                    <b><?= $sop_detail->approved_by ?></b>
                                    <?php elseif(isset($act) && $act == 'app') : ?>
                                    <a href="{{route('sop.approval_detail',['id_detail' => $sop_detail->id,'id_main' => $sop_main->id,'act'=> $act])}}" onclick="return confirm('Approve this data?')" class="btn btn-secondary btn-lg"> Approve Here</a>
                                    <?php else : ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <br>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <br>
                    <br>
                    <hr>
                    <a href="{{route('sop.detail',['id_main' => $sop_main->id])}}" class="btn btn-secondary btn-lg"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('custom_script')

@endsection
