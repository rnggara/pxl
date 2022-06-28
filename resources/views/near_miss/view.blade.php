@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">

                <h3>Preview</h3><br>
            </div>
            <div class="card-toolbar">
                <a href="{{route('nearmiss.index')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>

            </div>
        </div>
        <div id='center' align='center'>

            <table border='1' width='800px'>
                <tr>
                    <td colspan='3' align='center'>
                        <img src='{{str_replace("public", "public_html", asset('images/'.\Session::get('company_app_logo')))}}' width='200px' style='margin:20px; padding-right: -20px  '/>
                        <br />
                    <td>
                </tr>
                <tr>
                    <td colspan='3' align='center'>
                        <br /><br />
                        NEAR MISS
                        <br />
                        {{$nearmiss->id.'/'.strtoupper(Session::get('company_tag').'-NM').'/'.date('m').'/'.date('y')}}
                        <br /><br />
                        {{$nearmiss->title}}
                        <hr />
                        <br /><br />
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>
                        <div class="m-5">
                            <h4>Near Miss Report :</h4>
                            <p align='center'>
                                {!! $nearmiss->deskripsi !!}
                            </p>
                            <hr />
                            <h4>Near Miss Follow-Up :</h4>
                            <p>
                                {!! $nearmiss->follow_up_task !!}
                                @if (!empty($nearmiss->pict_follow))
                                <img src='{{str_replace('public','public_html',asset('/media/nearmiss_attachment/'))}}/{{$nearmiss->pict_follow}}' alt='picture_ori' style='max-width:400px' />
                                @endif
                            </p>
                            <br />
                            <hr />
                            <br /><br />
                        </div>
                    </td>
                </tr>
                <table width='800px'>
                    <tr align='center'>
                        <td align='center' width='33%'>
                            prepared by, <br />
                            <br />
                            <br />
                            ( {{$nearmiss->pelapor_name}} )
                        </td>

                        <td align='center' width='33%'>
                            followed-up by, <br />
                            <br />
                            <br />
                            ( {{$nearmiss->pelapor_follow_up}} )
                        </td>

                        <td align='center' width='33%'>
                            approved by, <br />
                            <br />
                            <br />
                            ( {{$nearmiss->approved}} )
                        </td>
                    </tr>
                </table>
            </table>
        </div>
        <br /><br /><br /><br /><br />
    </div>

@endsection
