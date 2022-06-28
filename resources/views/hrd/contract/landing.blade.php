@extends('layouts.templateContract')
@section('content')
    @if ($type == "success")
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-4 mx-auto">
                    <div class="alert alert-custom alert-outline-success fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="fa fa-check-circle"></i></div>
                        <div class="alert-text">Sign Contract Success</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($type == "delete")
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-4 mx-auto">
                    <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="fa fa-info-circle"></i></div>
                        <div class="alert-text">Contract Rejected</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="row">
                <div class="col-4 mx-auto">
                    <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="fa fa-info-circle"></i></div>
                        <div class="alert-text">Link not available</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection
