@extends('layouts.templateauth2')
@section('title','PXL | AoR Mini Game')

@section('login_logo', str_replace("public", "public_html", asset('images/'.$parent_comp->p_logo)))
@section('p_title', (empty($parent_comp->p_title)) ? "" : $parent_comp->p_title)
@section('p_subtitle')
    <div class="font-weight-bolder font-size-h2-md font-size-lg opacity-70" style="color: #ffffff;">
        {!! (empty($parent_comp->p_subtitle)) ? "" : $parent_comp->p_subtitle !!}
    </div>
@endsection
@section('p_bg', (empty($parent_comp->p_bg)) ? asset('theme/assets/media/bg/bg_login.jpg') : str_replace('public', 'public_html', asset("images/".$parent_comp->p_bg)))
@section('p_bg_width', (empty($parent_comp->p_bg_width)) ? '100' : $parent_comp->p_bg_width)

@section('content')
<form class="form" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{route('login')}}">
    <!--begin::Title-->
    @csrf
    <noscript>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                   Your browser does not support JavaScript!
                </div>
                <div class="modal fade show" style="display: block; padding: 17px; background-color: rgb(0, 0, 0, .5)" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                            Please turn on you JavaScript to access Cypher
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <ul>
                                            <li>
                                                Chrome :
                                                <ul>
                                                    <li>Settings > Site Settings > JavaScript : turn on allowed</li>
                                                </ul>
                                            </li>
                                            <li>
                                                Microsoft Edge :
                                                <ul>
                                                    <li>Settings > Cookies & Site Permissions > JavaScript : turn on allowed</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </noscript>
    <div class="text-center pb-8">
        <h2 class="font-weight-bolder text-white font-size-h2 font-size-h1-lg">Sign In</h2>
        {{-- @if(!empty($who))
        <span class="font-weight-bold font-size-h4" style="color: {{$who->bgcolor}}">
            {{$who->company_name}}
        </span>
        @else
        <span class="font-weight-bold font-size-h4" id="company_name">

        </span>
        @endif --}}
    </div>
    {{-- <div class="d-flex align-items-center mb-6 mx-auto">
        <div class="mx-auto text-center">
            @foreach($companies as $key => $value)
                <div class="symbol symbol-40 mt-5 symbol-light-primary mr-5">
                    <span class="symbol-label">
                        <a href="javascript:;" onclick="getIdCompany({{$value->id}})">
                              <span class="svg-icon svg-icon-lg svg-icon-primary">
                                  <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                  <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' style="align-content: center;" max-width='95%' @if($value->tag == 'RCN') height='15px' @else height='30px' @endif  alt="{{$value->company_name}}"/> &nbsp;&nbsp;
                                  <!--end::Svg Icon-->
                              </span>
                        </a>

                    </span>
                </div>
            @endforeach
        </div>

    </div> --}}
    <!--end::Title-->
    <!--begin::Form group-->
    <div class="form-group">
        <a href="{{ route("discord.login") }}" class="btn btn-dark px-8 py-7 btn-block">
            <i class="fab fa-discord text-primary"></i>
            Login with Discord
        </a>
        <div class="row">
            <div class="col-12 text-center">
                <span class="text-danger">
                    {{ \Session::get("error") ?? "" }}
                </span>
            </div>
        </div>
    </div>
    <div class="text-center pb-8">
        <h3 class="font-weight-bolder text-white font-size-h3">Or</h3>
        {{-- @if(!empty($who))
        <span class="font-weight-bold font-size-h4" style="color: {{$who->bgcolor}}">
            {{$who->company_name}}
        </span>
        @else
        <span class="font-weight-bold font-size-h4" id="company_name">

        </span>
        @endif --}}
    </div>
    <div class="form-group">
        <label class="font-size-h6 font-weight-bolder text-white">Username</label>
        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" id="txt-user" name="username" autocomplete="off" />
         @if(!empty($who))
        <input type="hidden" name="company_id" value="{{$who->id}}">
        <input type="hidden" name="tag" value="{{ $who->tag }}">
        @else
        <input type="hidden" name="id_company" id="id_company">
        @endif
    </div>
    <!--end::Form group-->
    <!--begin::Form group-->
    <div class="form-group">
        <div class="d-flex justify-content-between mt-n5">
            <label class="font-size-h6 font-weight-bolder text-white pt-5">Password</label>
        </div>
        <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" id="pw" type="password" name="password" autocomplete="off" />
    </div>
    <!--end::Form group-->
    <!--begin::Action-->
    <div class="text-center pt-2">
        <button type="submit" id="btn-sign-in" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</button>
    </div>
    <!--end::Action-->
</form>
@endsection
@section('custom_script')
    <script type="text/javascript">
        function getIdCompany(x){
            if(confirm('Switch Company. Are you sure?')){
                $('#id_company').val(x)
                company_name(x)
            }
            // console.log($('#id_company').val())
        }

        function company_name(x) {
            $.ajax({
                url: "{{route('home.get_company')}}/"+x,
                type: "get",
                dataType: "json",
                cache: false,
                success: function(response){
                    $("#company_name").text(response.company_name)
                    $("#company_name").css('color', response.bgcolor)
                }
            })
        }
        $(document).ready(function(){
            company_name(1)
            //$("#txt-user").focus()


            @if (!empty($user))
                $("#txt-user").val("{{ $user }}")
                $("#pw").val("{{ $pass }}")
                $("#btn-sign-in").click()
            @endif
        })

    </script>
@endsection
