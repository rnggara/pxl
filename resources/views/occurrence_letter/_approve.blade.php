<div class="modal-header">
    <h1 class="modal-title">Approve Occurrence Letter</h1>
    <button type="button" class="btn print-hide" data-dismiss="modal"><i class="fa fa-times print-hide"></i></button>
</div>
<form action="{{ route('oletter.approve') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group row">
                    <label for="" class="col-form-label col-4">Date</label>
                    <div class="col-8">
                        <input type="date" class="form-control" name="_date" readonly value="{{ $ol->ba_date }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-4">BA Number</label>
                    <div class="col-8">
                        <input type="text" class="form-control" name="_num" value="{{ $ol->ba_num }}" placeholder="BA Number" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-4">Title</label>
                    <div class="col-8">
                        <input type="text" class="form-control" name="_title" value="{{ $ol->title }}" placeholder="Title" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-4">Reported by</label>
                    <div class="col-8">
                        <input type="text" class="form-control" name="_ba_by" value="{{ $ol->ba_by }}" placeholder="Reported By" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-4">Description</label>
                    <div class="col-8">
                        <textarea name="_description" class="form-control tmce" id="" cols="30" rows="10">{!! $ol->description !!}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="" class="col-form-label col-12 text-center font-weight-bold">Problems</label>
                </div>
                @if (!empty($details))
                @foreach ($details as $item)
                    @if (isset($file_address[$item['problems_attachment']]))
                        <div class="row mb-2">
                            <div class="col-8 mx-auto text-center">
                                <div class="d-flex">
                                    <div class="symbol symbol-150 mr-3">
                                        <img alt="Pic" src="{{ str_replace("public", "public_html", asset($file_address[$item['problems_attachment']])) }}"/>
                                    </div>
                                    <div class="d-flex flex-column text-left">
                                        <span>{{ $item['problems'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @endif
                @if (!empty($ol->approved_at))
                <hr>
                <div class="form-group row">
                    <label for="" class="col-form-label col-12 text-center font-weight-bold">Signature</label>
                </div>
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="symbol symbol-150 mr-3">
                                @if(!empty($sign_created))
                                <img alt="Created By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_created)) }}"/>
                                @else
                                <br>
                                <br>
                                <br>
                                <br>
                                .......................
                                @endif
                            </div>
                            <br>
                            <span>{{ $ol->created_by }}</span>
                        </div>
                        <div class="col-6 text-center">
                            <div class="symbol symbol-150 mr-3">
                                @if (!empty(((((($sign_approved)))))))
                                <img alt="Approved By Signature" src="{{ str_replace("public", "public_html", asset("media/user/signature/".$sign_approved)) }}"/>
                                @else
                                <br>
                                <br>
                                <br>
                                <br>
                                .......................
                                @endif
                            </div>
                            <br>
                            <span>{{ $ol->approved_by }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary print-hide" data-dismiss="modal">Close</button>
        @if($type == "approve" && empty($ol->approved_at))
        <input type="hidden" name="id" value="{{ $ol->id }}">
        {{-- <button type="submit" name="submit" value="reject" class="btn btn-danger" onclick="_post()">Reject</button> --}}
        <button type="submit" name="submit" value="approve" class="btn btn-primary" onclick="_post()">Submit</button>
        @else
        <button type="button" onclick="print()" class="btn btn-primary print-hide">Print</button>
        @endif
    </div>
</form>
