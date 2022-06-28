@extends('layouts.templateauth')
@section('title','Login | Cypher')
@section('content')
    <div class="mb-20">
        <h2 class="text-primary">{{$company_holding}}</h2>
        <h3>Sign in</h3>
        <div class="text-muted font-weight-bold">Enter your details to login to your account:</div>
    </div>
    <form class="form" id="login" method="POST" action="{{route('login')}}">
        @csrf
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Username" name="username" autocomplete="off" />
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="password" />

        </div>
        <button id="kt_login_signin_submit" type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button>
    </form>
@endsection
