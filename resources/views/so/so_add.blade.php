@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Service Order Form
            </div>
        </div>

        <form class="form">
            <div class="card-body">

                <div class="col-md-6">
                    <h6><small><i>Request By</i></small></h6>
                    <hr>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">SO Type</label>
                        <div class="col-lg-6">
                            <select class="form-control" id="emp_type" name="emp_type">
                                <option value="">- Type -</option>
                                <option value="">1</option>
                                <option value="">2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Request Date</label>
                        <div class="col-lg-6">
                            <input type="date" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Payment Method</label>
                        <div class="col-lg-6">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="checkbox"/>
                                    <span></span>
                                    BACK DATE
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Division</label>
                        <div class="col-lg-6">
                            <select class="form-control" id="emp_type" name="emp_type">
                                <option value="">- Division -</option>
                                <option value="">Finance</option>
                                <option value="">HR</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Reference</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" placeholder=""/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Notes</label>
                        <div class="col-lg-6">
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Project</label>
                        <div class="col-lg-6">
                            <select class="form-control" id="emp_type" name="emp_type">
                                <option value="">- PRoject -</option>
                                <option value="">Dm1</option>
                                <option value="">Dm2</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Deliver to</label>
                        <div class="col-lg-6">
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Deliver Time</label>
                        <div class="col-lg-6">
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-11"></div>
                    <div class="col-lg-1">
                        <a href="{{route('general.so.add.job')}}" class="btn btn-success mr-2">Next</a>
                    </div>
                </div>
            </div>

        </form>

    </div>
@endsection
