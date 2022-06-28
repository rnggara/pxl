@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{$user->username}} - Privilege</h3>
            </div>
            <div class="card-toolbar">

                <a href="{{route('company.detail', $companyId)}}" class="btn btn-secondary font-weight-bolder">
				<span class="svg-icon svg-icon-md">
					<i class="la la-angle-double-right"></i>
				</span>Company
                </a>&nbsp;
                <a href="{{ route('user.inherit', $user->id) }}" onclick="return confirm(are you sure?)" class="btn btn-warning font-weight-bolder">
				<span class="svg-icon svg-icon-md">
				</span>Inherit From Position
                </a>&nbsp;
                <button class="btn btn-primary font-weight-bolder" id="selectButton">
				<span class="svg-icon svg-icon-md">
				</span>Select All / Deselect All
                </button>&nbsp;
                <button class="btn btn-info font-weight-bolder" id="saveUserPrivelege">
                    <i class="fa fa-check"></i>Save
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="userPrivelegeUpdate" action="{{route('user.uprivilege', $user->id)}}" method="post">
                @csrf
                <table class="table table-bordered table-hover">
                    <thead>
                    <th></th>
                    @foreach($actionList as $key => $action)
                        <th style="text-align: center; max-width: 30px;">
							<span>
								{{$action}}
							</span>
                        </th>
                    @endforeach

                    </thead>
                    <tbody>
                    @foreach($moduleList as $moduleKey => $module)
                        <tr>
                            <td style="text-align: right; max-width: 100px;">
								<a href="#" onclick="get_module({{ $moduleKey }})" data-container="body" data-toggle="kt-tooltip" data-placement="left">
									{{ucwords($module)}}
                                </a>
                            </td>
                            @foreach($actionList as $actionKey => $action)
                                <td align="center" class="text-center" onclick="td_click(this)">
                                    <div class="checkbox-inline justify-content-center">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" name="privilege[{{$moduleKey}}][{{$actionKey}}]" id="privilege_{{$moduleKey}}_{{$actionKey}}" value="1">
                                            <span></span>
                                        </label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="modal fade" id="moduleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="module-content">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    @if(isset($user))
        <script>
        jQuery.each({!! $user->privilege !!}, function(key, value)
        {
            // console.log(value['id_rms_modules'], value['id_rms_actions'])
            $('#privilege_'+value['id_rms_modules']+'_'+value['id_rms_actions']).attr('checked', true);
        });
        </script>
    @endif
    <script type="text/javascript">
        function get_module(x){
            $("#moduleModal").modal('show')
            $.ajax({
                url : "{{ route('user.priv.module') }}",
                type: "post",
                data: {
                    _token : "{{ csrf_token() }}",
                    id : {{ $user->id }},
                    module : x
                },
                success: function(response){
                    $("#module-content").html(response)
                    var btn = $("#module-content").find("#btn-save")
                    console.log(btn)
                    btn.click(function(event){
                        $(this).addClass("spinner spinner-white spinner-right")
                        event.preventDefault()
                        $(this).prop('disabled', true)
                        var ck = $("#module-content").find(".ck_action")
                        var ckc = $("#module-content").find(".ck_comp")
                        var ck_act = 0
                        var ck_comp = 0
                        ck.each(function(){
                            if($(this).prop('checked') == true){
                                ck_act += 1
                            }
                        })

                        ckc.each(function(){
                            if($(this).prop('checked') == true){
                                ck_comp += 1
                            }
                        })

                        if(ck_comp == 0){
                            Swal.fire('No Company Selected', 'Please select at least one company', 'error')
                            $(this).removeClass("spinner spinner-white spinner-right")
                            $(this).prop('disabled', false)
                        } else {
                            if(ck_act == 0){
                                Swal.fire({
                                    title: "Are you sure?",
                                    text: "This action will remove some privilege from the user",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "Yes"
                                }).then(function(result) {
                                    if (result.value) {
                                        $("#form-module-save").submit()
                                    }
                                });
                            } else {
                                $("#form-module-save").submit()
                            }
                        }
                    })
                }
            })
        }
        function td_click(x){
            var td = $(x)
            var input = td.find('input')
            input.prop('checked', !input.prop('checked'))
        }
        $(document).ready(function() {
            var clicked = false;
            $("#selectButton").on("click", function() {
                $(":checkbox").prop("checked", !clicked);
                clicked = !clicked;
            });

            $("#saveUserPrivelege").click(function(){
            	$('#userPrivelegeUpdate').submit();
            });
        });
    </script>
@endsection
