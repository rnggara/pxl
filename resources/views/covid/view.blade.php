@extends('layouts.template')

@section('css')
    <style>
        .b-right {
            border-right: 1px solid rgb(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Covid Protocol - {{ $protocol->protocol_num }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('general.covid.setting') }}" class="btn btn-icon btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8 mx-auto">
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
                    <div class="row mt-10">
                        <div class="col-12">
                            <table style="table-layout: fixed" class="mb-10 table table-bordered">
                                <tr>
                                    <th class="text-center b-right" style="border-bottom: 1px solid rgb(0,0,0,0.1)">
                                        <span class="font-weight-bold font-size-h3">COVID PROTOCOL</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="px-10 pt-5 b-right">
                                        {!! $protocol->content !!}
                                        @if (!empty($protocol->content_eng))
                                            @php
                                                $attach = str_replace("public", "public_html", asset($protocol->content_eng));
                                            @endphp
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <img src="{{ $attach }}" style="width: 100%">
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <br><br><br><br><br><br>
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
                                {{ $protocol->created_by }}
                            </td>
                            <td class="text-center">
                                @if (empty($protocol->acknowledge_by))
                                    <form action="{{ route('general.covid.update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $protocol->id }}">
                                        <button type="submit" name="submit" value="ack" onclick="return confirm('Acknowledge this protocol?')" class="btn btn-secondary"><span class="font-weight-bold">Acknowledge here</span></button>
                                    </form>
                                @else
                                    {{ $protocol->acknowledge_by }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if (!empty($protocol->acknowledge_by))
                                    @if (empty($protocol->approved_by))
                                        <form action="{{ route('general.covid.update') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $protocol->id }}">
                                            <button type="submit" name="submit" value="app" onclick="return confirm('Approve this protocol?')" class="btn btn-secondary"><span class="font-weight-bold">Approve here</span></button>
                                        </form>
                                    @else
                                        {{ $protocol->approved_by }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
