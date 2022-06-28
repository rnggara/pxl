@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/jstree/jstree.bundle.css') }}">
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('coa.source.index') }}" class="btn btn-info"><i class="fa fa-database"></i>Source</a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee"><i class="fa fa-plus"></i>Add {{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }}</button>
                </div>
                <!--end::Button-->
            </div>
        </div>
        {{-- <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th nowrap="nowrap">Code</th>
                        <th nowrap="nowrap" class="text-left">Name</th>
                        <th nowrap="nowrap" class="text-center">Source</th>
                        <th nowrap="nowrap" class="text-center">Parent</th>
                        <th nowrap="nowrap" class="text-center">Status</th>
                        <th data-priority=1 class="text-center">#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coa as $key => $value)
                        <tr>
                            <div class="modal fade" id="edit{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit {{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <form method="post" action="{{route('coa.store')}}" >
                                            @csrf
                                            <input type="hidden" name="edit" value="1">
                                            <input type="hidden" name="id" value="{{$value->id}}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="form col-md-12">
                                                        <div class="form-group">
                                                            <label>{{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }} Name</label>
                                                            <input type="text" class="form-control" name="name" id="name{{$value->id}}" value="{{$value->name}}"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Source </label>
                                                            <select class="form-control select2" name="source[]" id="source{{ $value->id }}" multiple>
                                                                <option value="">--Choose Source--</option>
                                                                @if (!empty($value->source))
                                                                    @foreach (json_decode($value->source, true) as $iSrc)
                                                                        @if (isset($srcAll[$iSrc]))
                                                                            <option value="{{ $iSrc }}" selected>{{ $srcAll[$iSrc] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                                @foreach($source as $sKey => $val)
                                                                    <option value="{{$sKey}}" {{ (!empty($value->source) && in_array(1, json_decode($value->source, true))) ? "SELECTED" : "" }}>{{ $val }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Parent </label>
                                                            <select class="form-control select2" name="id_parent" onchange="idParent(this, {{$value->id}})" id="id_parent{{$value->id}}">
                                                                <option value="">--Choose Parent--</option>
                                                                <option value="new">New</option>
                                                                @foreach($coa as $key => $val)
                                                                    <option value="{{$val->code}}" @if($val->code == $value->parent_id) selected @endif>{{$val->code}}-{{$val->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-6">
                                                                <input type="number" class="form-control" placeholder="parent code" name="code_parent" id="code_parent{{$value->id}}" readonly/>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="number" class="form-control" name="code_child" placeholder="code" onchange="idParent2(this,{{$value->id}})" id="code_child{{$value->id}}" />
                                                                <input type="hidden" name="parentcode" id="parentcode{{$value->id}}"/>
                                                                <input type="hidden" name="newcode" id="newcode{{$value->id}}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold" id="btnSubmit{{$value->id}}">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-left">
                                <a href="{{route('coa.view', $value->code)}}" class="text-hover-danger">{{$value->code}}</a>
                            </td>
                            <td class="text-left">{{$value->name}}</td>
                            <td class="text-center">
                                @if (!empty($value->source))
                                    @foreach (json_decode($value->source) as $src)
                                        @if (isset($srcAll[$src]))
                                            <p><span class="label label-inline label-primary">{{ $srcAll[$src] }}</span></p>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                {{ (isset($id_parents[$value->code])) ? $id_parents[$value->code] : "" }}
                            </td>
                            <td class="text-center">
                                @if($value->status == 0)
                                    <button type="button" onclick="update_status({{$value->id}}, 'inactive')" class="btn btn-xs btn-danger">inactive</button>
                                @else
                                    <button type="button" onclick="update_status({{$value->id}}, 'active')" class="btn btn-xs btn-success">active</button>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="#edit{{$value->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="{{route('coa.delete',['id' => $value->id])}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" onclick="return confirm('Delete Category?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
    </div>
    <div class="row">
        @foreach ($coa_show as $item)
        <div class="col-6">
            <div class="card card-custom gutter-b card-stretch">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-header">[{{ $item->code }}] {{ $item->name }} <button type="button" class="btn btn-icon btn-secondary btn-xs refresh-btn"><i class="flaticon2-refresh-button"></i></button></h3>
                        </div>
                        <div class="col-12">
                            <div id="div_{{ $item->id }}" data-id="{{ $item->id }}" class="tree-list">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add {{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{route('coa.store')}}" >
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form col-md-12">
                                <div class="form-group">
                                    <label>{{ !empty(\Session::get('company_tc_initial')) ? strtoupper(\Session::get('company_tc_initial')) : "TC" }} Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required/>
                                </div>
                                <div class="form-group">
                                    <label>Source </label>
                                    <select class="form-control select2" name="source[]" id="source" multiple>
                                        <option value="">--Choose Source--</option>
                                        @foreach($source as $key => $val)
                                            <option value="{{$key}}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Parent </label>
                                    <select class="form-control select2" name="id_parent" id="id_parent" required>
                                        <option value="">--Choose Parent--</option>
                                        <option value="new">New</option>
                                        @foreach($coa as $key => $val)
                                            <option value="{{$val->id}}">{{$val->code}}-{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6"><input type="number" class="form-control" placeholder="parent code" name="code_parent" id="code_parent" readonly/></div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" name="code_child" placeholder="code" id="code_child"/>
                                        <input type="hidden" name="parentcode" id="parentcode"/>
                                        <input type="hidden" name="newcode" id="newcode"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold" id="btnSubmit" disabled>
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="edit-coa">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('theme/assets/plugins/custom/jstree/jstree.bundle.js') }}"></script>
    {{-- <script src="{{ asset('theme/assets/js/pages/features/miscellaneous/treeview.js') }}"></script> --}}
    <script>
        var parent,newchild;
        var arrCode = <?php echo '["' . implode('", "', $code) . '"]' ?>;

        function idParent2(input, x){
            var newcode2 = "#newcode"+x
            var parent2 = "#parentcode"+x
            var child2 = "#code_child"+x
            var btnSubmit2 = "#btnSubmit"+x
            var stat2 = true

            var parentval = $(parent2).val()
            for (let i = parentval.length -1; i >= 0 ; i--) {
                if (parentval[i] != '0'){
                    num = i
                    break
                } else {
                    notnum = i
                }
            }

            var newparentval = parentval.substring(0,num+1)
            var newcodeval = newparentval+$(child2).val()
            $(newcode2).val(newcodeval)
            var checker = $(newcode2).val()
            for (let i = 0; i < arrCode.length; i++) {
                if (arrCode[i] == checker){
                    stat = false
                } else {
                    stat = true
                }
            }
            if (stat == true){
                $(btnSubmit2).attr("disabled",false)
            } else {
                $(btnSubmit2).attr("disabled",true)
            }

        }
        function idParent(select,  x){
            var id = "#id_parent" + x
            var parentcode = "#code_parent"+x
            var parenthidden = "#parentcode"+x
            var childcode = "#code_child"+x
            var newcode = "#newcode"+x
            var btnSubmit = "#btnSubmit"+x
            var stat = true;
            dataParent = select.options[select.selectedIndex].text

            var strArray = dataParent.split("-")
            if (strArray[0] ==='New'){
                $(parentcode).show();
                $(parentcode).attr("readonly", false)
                $(parentcode).attr("disabled", false)
                $(childcode).attr("disabled", false)
                $(childcode).hide()
                $(parenthidden).attr("disabled", true)
                $(btnSubmit).attr('disabled', true)
                $(parentcode).change(function () {
                    var checker = $(parentcode).val()
                    for (let i = 0; i < arrCode.length; i++) {
                        if (arrCode[i] == checker){
                            stat = false
                        } else {
                            stat = true
                        }
                    }
                    if (stat == true){
                        $(btnSubmit).attr("disabled",false)
                        $(newcode).val(checker)
                    } else {
                        $(btnSubmit).attr("disabled",true)
                    }
                })
            } else if(strArray[0] === '') {
                $(parentcode).hide()
                $(childcode).hide()
                $(btnSubmit).attr('disabled', false)
            } else {
                $(btnSubmit).attr('disabled', true)
                $(parenthidden).attr("readonly", true)
                var code = strArray[0].toString()
                var num = ""
                var notnum = ""


                for (let i = code.length -1; i >= 0 ; i--) {
                    if (code[i] != '0'){
                        num = i
                        break
                    } else {
                        notnum = i
                    }
                }

                $(parenthidden).val(code)

                $(parentcode).show();
                $(parentcode).val(code.substring(0,num+1))
                $(childcode).show();
                $(childcode).val(code.substring(notnum,code.length))
                $(newcode).val(code.substring(0,num+1)+code.substring(notnum,code.length))
                parent = code
                newchild = code.substring(0,num+1)+code.substring(notnum,code.length)
            }
        }

        function update_status(x, y){
            $.ajax({
                url: "{{route('coa.update')}}",
                type: "post",
                dataType: "json",
                data: {
                    "_token": "{{csrf_token()}}",
                    'id' : x,
                    'act': y,
                },
                cache: false,
                success: function(response){
                    console.log(response)
                    if (response.error == 0){
                        location.reload()
                    } else {
                        Swal.fire('Error occured', 'Please contact your administrator', 'error')
                    }
                }
            })
        }

        function _edit(id){
            $("#modalEdit").modal('show')
            $("#edit-coa").html('')
            $.ajax({
                url : "{{ route('coa.edit') }}/" + id,
                type : "get",
                cache : "false",
                success : function(response){
                    $("#edit-coa").html(response)
                    $("#edit-coa select").select2({
                        width : "100%"
                    })

                    $("#edit-coa input[name=code_parent]").hide()
                    $("#edit-coa input[name=code_child]").hide()
                }
            })
        }

        function _delete(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('coa.delete') }}/"+id
                }
            });
        }

        function _show_hide(id, status){
            var label = "Show"
            if(status == 1 || status == null){
                label = "Hide"
            }
            Swal.fire({
                title: "Are you sure?",
                text: label + " this TC",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('coa.update') }}/"+id
                }
            });
        }


        var dataParent, dataNewCode;
        var stat = true;

        function _tree(div){
            console.log(div)
            $(div).jstree({
                "core": {
                    "themes": {
                        "responsive": false
                    },
                    // so that create works
                    "check_callback": true,
                    'data': {
                        'url': function(node) {
                            return node.id === "#" ? "{{ route('coa.list') }}" : "{{ route('coa.list_child') }}"
                        },
                        'data': function(node) {
                            return {
                                'parent': node.id
                            };
                        }
                    }
                },
                "types": {
                    "default": {
                        "icon": "fa fa-folder text-primary"
                    },
                    "file": {
                        "icon": "fa fa-file  text-primary"
                    }
                },
                "state": {
                    "key": "demo3"
                },
                "plugins": ["dnd", "state", "types"]
            });
        }

        $(document).ready(function () {
            $("input[name=code_parent]").hide()
            $("input[name=code_child]").hide()
            $('.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

            $("select.select2").select2({
                width: "100%"
            })
            var cek_count = 0;

            $('#id_parent').change(function () {
                dataParent = $("#id_parent option:selected").text();
                var strArray = dataParent.split("-")
                if (strArray[0] ==='New'){
                    $('#code_parent').show();
                    document.getElementById("code_parent").readOnly = false;
                    document.getElementById("code_child").disabled = true;
                    document.getElementById('parentcode').disabled = true;
                } else {
                    document.getElementById('parentcode').disabled = false;

                    var code = strArray[0].toString()
                    var num = ""
                    var notnum = ""


                    for (let i = code.length -1; i >= 0 ; i--) {
                        if (code[i] != '0'){
                            num = i
                            break
                        } else {
                            notnum = i
                        }
                    }
                    document.getElementById('parentcode').value = code;
                    $('#code_parent').show();
                    document.getElementById('code_parent').value = code.substring(0,num+1);
                    $('#code_child').show();
                    document.getElementById('code_child').value = code.substring(notnum,code.length);
                }
            })

            $('#code_parent').change(function () {
                var checker = $('#code_parent').val();
                for (let i = 0; i < arrCode.length; i++) {
                    if (arrCode[i] == checker){
                        stat = false
                    } else {
                        stat = true
                    }
                }
                if (stat == true){
                    document.getElementById("btnSubmit").disabled = false;
                    document.getElementById('newcode').value = checker;
                }
            })


            $('#code_child').change(function () {
                var checker = $('#code_parent').val()+$('#code_child').val()
                for (let i = 0; i < arrCode.length; i++) {
                    if (arrCode[i] == checker){
                        stat = false
                    } else {
                        stat = true
                    }
                }
                if (stat == true){
                    document.getElementById("btnSubmit").disabled = false;
                    document.getElementById('newcode').value = checker;
                }
            })

            $(".tree-list").each(function(){
                var id = $(this).attr("data-id")

                var btn = $(this).find(".refresh-btn")
                console.log(btn)

                $(this).jstree({
                    "core": {
                        "themes": {
                            "responsive": false
                        },
                        // so that create works
                        "check_callback": true,
                        'data': {
                            'url': function(node) {
                                return node.id === "#" ? "{{ route('coa.list') }}/"+id : "{{ route('coa.list_child') }}"
                            },
                            'data': function(node) {
                                return {
                                    'parent': node.id
                                };
                            }
                        }
                    },
                    "types": {
                        "root": {
                            "icon": "fa fa-folder text-primary"
                        },
                        "file": {
                            "icon": "fa fa-file  text-primary"
                        }
                    },
                    "state": {
                        "key": "demo3"
                    },
                    "plugins": ['contextmenu'],
                    "contextmenu" : {
                        "items" : function(node){
                            var origin = node.original
                            var _active = "Show"
                            var _active_icon = "fa-eye"
                            if (origin.status == 1 || origin.status == null) {
                                _active = "Hide"
                                _active_icon = "fa-eye-slash"
                            }

                            return {
                                "Edit" : {
                                    "separator_before": true,
                                    "separator_after": false,
                                    "label": "Edit",
                                    "icon" : "fa fa-edit text-primary",
                                    "action": function (obj) {
                                        _edit(node.id)
                                    }
                                },
                                "Active" : {
                                    "separator_before": false,
                                    "separator_after": true,
                                    "label": _active,
                                    "icon" : "far "+_active_icon+" text-success",
                                    "action": function (obj) {
                                        _show_hide(node.id, origin.status)
                                    }
                                },
                                "Delete" : {
                                    "separator_before": true,
                                    "separator_after": true,
                                    "label": "Delete",
                                    "icon" : "fa fa-trash text-danger",
                                    "action": function (obj) {
                                        _delete(node.id)
                                    }
                                }
                            }
                        }
                    }
                });

                btn.click(function(){
                    $(this).jstree(true).refresh();
                })
            })
        });
    </script>
@endsection
