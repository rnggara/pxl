<div class="modal-header">
    <h1 class="modal-title">Module {{ ucwords($module->name) }}</h1>
</div>
<form action="{{ route('user.priv.module.save') }}" method="post" id="form-module-save">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group row">
                    <table class="table table-borderless table-responsive-xl">
                        <tr>
                            <th></th>
                            @foreach ($actions as $actionKey => $item)
                                <th class="text-center">
                                    {{ $item }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th>{{ ucwords($module->desc) }}</th>
                            @foreach ($actions as $actionKey => $item)
                                <th class="text-center">
                                    <div class="checkbox-inline justify-content-center">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" name="privilege[{{ $actionKey }}]" value="1" class="ck_action">
                                            <span></span>
                                        </label>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            <th colspan="{{ count($actions) + 1 }}">List Company</th>
                        </tr>
                        @foreach ($userComp as $userKey => $userC)
                        <tr>
                            <td colspan="{{ count($actions) + 1 }}">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="checkbox" name="company[{{ $userC }}]" class="ck_comp" value="1">
                                        <span></span>
                                        {{ $companies[$userC] }}
                                    </label>
                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id_user" value="{{ $user->id }}">
        <input type="hidden" name="id_module" value="{{ $module->id }}">
        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
    </div>
</form>
