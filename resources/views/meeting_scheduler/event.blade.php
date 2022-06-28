@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Meeting Detail
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('ms.index')}}" class="btn btn-secondary"><i class="fa fa-backspace"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('ms.addEvent')}}" >
                @csrf
                <div class="row">
                    <div class='col-md-6'>
                    <h6>Meeting Component</h6>
                        <hr>
                        <input type="hidden" name="book_id" value="{{$id_book}}">
                        <input type="hidden" name="tgl" value="{{$date}}">
                        <input type="hidden" name="id_room" value="{{$room}}">
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Tanggal</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="date" name="tgl" class="form-control" value="{{date('Y-m-d',strtotime($date))}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Topic Meeting</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="text" name="topic" class="form-control" placeholder="Topic Meeting">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Project</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <select class="form-control" name="project">
                                    @foreach($projects as $key => $value)
                                        <option value="{{$value->id}}">{{$value->prj_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <h6>Meeting Participants</h6>
                        <hr>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Meeting Leader</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <select class="form-control" name="leader" onchange="checkLeader(this)">
                                    <option></option>
                                    <option value="new">New</option>
                                    @foreach($employees as $key => $value)
                                        <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="text" name="meeting_leader" id="meeting_leader" class="form-control" placeholder="Name" style="display: none">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Notulen</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <select class="form-control" name="notulen" onchange="checkNotulen(this)">
                                    <option></option>
                                    <option value="new">New</option>
                                    @foreach($employees as $key => $value)
                                        <option value="{{$value->id}}">{{$value->emp_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input type="text" name="notula" id="notula" class="form-control" placeholder="Name" style="display: none">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Attendees</label>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                <input id="kt_tagify_1" class="tag_input form-control tagify" name='attendees' placeholder='type attendees and press enter' />
                                <div class="mt-3">
                                    <a href="javascript:;" id="kt_tagify_1_remove" class="tag_remove btn btn-sm btn-light-primary font-weight-bold">Remove Attendees</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-10'>
                    </div>
                    <div class="col-md-2">
                        @actionStart('meeting_scheduler', 'create')
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                        @actionEnd
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        function checkLeader(that) {
            if (that.value === "new") {
                // alert("check");
                document.getElementById("meeting_leader").style.display = "block";
            } else {
                document.getElementById("meeting_leader").style.display = "none";
            }
        }
        function checkNotulen(that) {
            if (that.value === "new") {
                // alert("check");
                document.getElementById("notula").style.display = "block";
            } else {
                document.getElementById("notula").style.display = "none";
            }
        }
        $(document).ready(function () {
            demo1()
        });
        function demo1(){
            var input = document.getElementById('kt_tagify_1'),
                // init Tagify script on the above inputs
                tagify = new Tagify(input, {
                    whitelist: [],
                    blacklist: [], // <-- passed as an attribute in this demo
                })


            // "remove all tags" button event listener
            document.getElementById('kt_tagify_1_remove').addEventListener('click', tagify.removeAllTags.bind(tagify));

            // Chainable event listeners
            tagify.on('add', onAddTag)
                .on('remove', onRemoveTag)
                .on('input', onInput)
                .on('edit', onTagEdit)
                .on('invalid', onInvalidTag)
                .on('click', onTagClick)
                .on('dropdown:show', onDropdownShow)
                .on('dropdown:hide', onDropdownHide)

            // tag added callback
            function onAddTag(e) {
                console.log("onAddTag: ", e.detail);
                console.log("original input value: ", input.value)
                tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
            }

            // tag remvoed callback
            function onRemoveTag(e) {
                console.log(e.detail);
                console.log("tagify instance value:", tagify.value)
            }

            // on character(s) added/removed (user is typing/deleting)
            function onInput(e) {
                console.log(e.detail);
                console.log("onInput: ", e.detail);
            }

            function onTagEdit(e) {
                console.log("onTagEdit: ", e.detail);
            }

            // invalid tag added callback
            function onInvalidTag(e) {
                console.log("onInvalidTag: ", e.detail);
            }

            // invalid tag added callback
            function onTagClick(e) {
                console.log(e.detail);
                console.log("onTagClick: ", e.detail);
            }

            function onDropdownShow(e) {
                console.log("onDropdownShow: ", e.detail)
            }

            function onDropdownHide(e) {
                console.log("onDropdownHide: ", e.detail)
            }
        }
    </script>
@endsection
