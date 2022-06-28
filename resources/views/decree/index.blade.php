@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Official Letter
            </div>
            <div class="card-toolbar">
                @actionStart('official_letter', 'create')
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add Official Letter</button>
                </div>
                @actionEnd
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-left">Upload Date</th>
                        <th nowrap="nowrap" class="text-left">Title</th>
                        <th nowrap="nowrap" class="text-left">Author</th>
                        <th nowrap="nowrap" class="text-left">Classification</th>
                        <th nowrap="nowrap">Short Description</th>
                        <th nowrap="nowrap" data-priority=1></th>
                    </tr>
                    </thead>
                    <tbody>
                        @actionStart('official_letter', 'read')
                    @foreach($decrees as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td nowrap="nowrap" class="text-left">{{date('d F Y',strtotime($value->created_at))}}</td>
                            <td nowrap="nowrap" class="text-left">{{$value->title}}</td>
                            <td nowrap="nowrap" class="text-left">{{$value->author}}</td>
                            <td nowrap="nowrap" class="text-left">{{$value->class}}</td>
                            <td nowrap="nowrap">{{$value->deskripsi}}</td>
                            <td nowrap="nowrap" data-priority=1 class="text-center">
                                <a href="{{URL::route('download', $value->file_form)}}" target="_blank" class="btn btn-light-success btn-xs btn-icon" title="Download">
                                    <i class="fa fa-download"></i> &nbsp;
                                </a>
                                &nbsp;&nbsp;
                                @actionStart('official_letter', 'delete')
                                <a href="{{route('decree.delete',['id' => $value->id])}}" onclick="return confirm('Delete this decree letter?');" class="btn btn-light-danger btn-xs btn-icon" title="Delete">
                                    <i class="fa fa-trash"></i> &nbsp;
                                </a>
                                @actionEnd
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Official Letter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('decree.store')}}" enctype="multipart/form-data" class="form" id="kt_form_12">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Title" />
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description" placeholder="Description" />
                                </div>
                                <div class="form-group">
                                    <label>Classification</label>
                                    <input type="text" class="form-control" name="classification" placeholder="Classification"/>
                                </div>
                                <div class="form-group">
                                    <label>Upload File</label>
                                    <img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                    <input type="file" class="form-control" name="file_form" id="picture1" accept='image/*' placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary font-weight-bold"><i class="fa fa-check"></i>
                            Save</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {
             $('table.display').DataTable({
                responsive: true,
                "searching": false,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
            initValidation();

            $("#prev_eq1").hide();

            $("#picture1").change(function(){
                console.log($(this).val());
                if ($(this).val()) {
                    readURL(this, 1);
                    $("#prev_eq1").show();
                } else {
                    $("#prev_eq1").hide();
                }
            });

            function readURL(input, sec) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev_eq' + sec).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

        });

        function initValidation(){
            FormValidation.formValidation(
                document.getElementById('kt_form_12'),
                {
                    fields: {
                        title: {
                            validators: {
                                notEmpty: {
                                    message: 'Title is required'
                                },
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: 'Description is required'
                                },
                            }
                        },
                        classification: {
                            validators: {
                                notEmpty: {
                                    message: 'Classification is required'
                                },
                            }
                        },
                        file_form: {
                            validators: {
                                notEmpty: {
                                    message: 'The File is required'
                                },
                            }
                        },
                    },

                    plugins: { //Learn more: https://formvalidation.io/guide/plugins
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap(),
                        // Validate fields when clicking the Submit button
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        // Submit the form when all fields are valid
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    }
                }
            );
        }
        function initValidation2(){
            FormValidation.formValidation(
                document.getElementById('kt_form_13'),
                {
                    fields: {
                        title: {
                            validators: {
                                notEmpty: {
                                    message: 'Title is required'
                                },
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: 'Description is required'
                                },
                            }
                        },
                        classification: {
                            validators: {
                                notEmpty: {
                                    message: 'Classification is required'
                                },
                            }
                        },
                        file_form: {
                            validators: {
                                notEmpty: {
                                    message: 'This field is required'
                                },
                            }
                        },
                    },

                    plugins: { //Learn more: https://formvalidation.io/guide/plugins
                        trigger: new FormValidation.plugins.Trigger(),
                        // Bootstrap Framework Integration
                        bootstrap: new FormValidation.plugins.Bootstrap(),
                        // Validate fields when clicking the Submit button
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        // Submit the form when all fields are valid
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    }
                }
            );
        }
    </script>
@endsection
