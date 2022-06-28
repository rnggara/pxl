@extends('layouts.template')
@section('content')
    @if(session()->has('message_needsec_fail_empdetail'))
        <div class="alert alert-danger">
            {{ session()->get('message_needsec_fail_empdetail') }}
        </div>
    @endif
    @if(session()->has('message_needsec_success_empdetail'))
        <div class="alert alert-success">
            {{ session()->get('message_needsec_success_empdetail') }}
        </div>
    @endif
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>RESTRICTED AREA</h3><br>

            </div>
            <div class="card-toolbar">

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <h4>You need to register your security key first<br /><br />Thank you</h4>
            <hr />
            <br />

            <h4>Input Password : </h4>
            <form action='{{route('emp_detail.needsec.submit')}}' method='POST' class='col-md-4'>
                @csrf
                <input type='password' class='form-control' name='searchInput' id='search' required>
                <input type='submit' class='form-control btn btn-success' name='submit' id='submit' value='Login'>
            </form>
        </div>
    </div>
@endsection
