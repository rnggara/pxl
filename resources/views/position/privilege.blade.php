@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{$roleDiv->roleName}}&nbsp;{{$roleDiv->divName}} - Privilege</h3>
            </div>
            <div class="card-toolbar">

                <a href="{{route('company.role_controll', $companyId)}}" class="btn btn-secondary font-weight-bolder">
				<span class="svg-icon svg-icon-md">
					<i class="la la-angle-double-right"></i>
				</span>Company
                </a>&nbsp;
                <button class="btn btn-primary font-weight-bolder" id="selectButton">
				<span class="svg-icon svg-icon-md">
				</span>Select All / Deselect All
                </button>&nbsp;
                <button class="btn btn-info font-weight-bolder" id="savePositionPrivelege">
                    <i class="fa fa-check"></i>Save
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="positionPrivelegeUpdate" action="{{route('rprivilege.update', $roleDiv->id)}}" method="post">
                @csrf
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th style="text-align: right; max-width: 100px;">Inherit to child</th>
                        <th colspan="{{count($actionList) + 1}}">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="to_child">
                                    <span></span>
                                </label>
                            </div>

                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: right; max-width: 100px;">Inherit to users</th>
                        <th colspan="{{count($actionList) + 1}}">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="to_user">
                                    <span></span>
                                </label>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        @foreach($actionList as $key => $action)
                            <th style="text-align: center; max-width: 30px;">
							<span>
								{{$action}}
							</span>
                            </th>
                        @endforeach
                        <th style="text-align: center; max-width: 30px;">Check All</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($moduleList as $moduleKey => $module)
                        <tr>
                            <td style="text-align: right; max-width: 100px;">
								<span data-container="body" data-toggle="kt-tooltip" data-placement="left">
									{{$module}}
								</span>
                            </td>
                            @foreach($actionList as $actionKey => $action)
                                <td style="text-align: center;" align="center">
                                    <div class="checkbox-inline justify-content-center">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" class="ck_box {{$module}}" name="privilege[{{$moduleKey}}][{{$actionKey}}]" id="privilege_{{$moduleKey}}_{{$actionKey}}" value="1">
                                            <span></span>
                                        </label>
                                    </div>
                                </td>
                            @endforeach
                            <td align="center">
                                <div class="checkbox-inline justify-content-center">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="checkbox" class="ck_box" onclick="check_all(this,'.{{$module}}')" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
@section('custom_script')
    @if(isset($roleDiv))
        <script>
        jQuery.each({!! $rolePriv->privilege !!}, function(key, value)
        {
            console.log(value['id_rms_modules'], value['id_rms_actions'])
            $('#privilege_'+value['id_rms_modules']+'_'+value['id_rms_actions']).attr('checked', true);
        });
        </script>
    @endif
    <script type="text/javascript">
        function check_all(check, checks){
            if (check.checked){
                console.log(checks)
                $(checks).each(function(){
                    this.checked = true
                })

            } else {
                $(checks).each(function(){
                    this.checked = false
                })
            }
        }
        $(document).ready(function() {
            var clicked = false;
            $("#selectButton").on("click", function() {
                $(".ck_box").prop("checked", !clicked);
                clicked = !clicked;
            });

            $("#savePositionPrivelege").click(function(){
            	$('#positionPrivelegeUpdate').submit();
            });
        });
    </script>
@endsection
