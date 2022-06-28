<div class="col-6">
    <h3 class="card-header text-muted">Project Done</h3>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table table-borderless table-hover display">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Project</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($done as $item)
                    <tr class="cursor-pointer">
                        <td align="center">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" onclick="push_list_project(this)" value="{{ $item->id }}" name="prj_done[]"/>
                                    <span></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            {{ $item->prj_name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-6">
    <h3 class="card-header text-muted">Project Ongoing</h3>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table table-borderless table-hover display">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-left">Project</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ongoing as $item)
                    <tr class="cursor-pointer">
                        <td align="center">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" onclick="push_list_project(this)" value="{{ $item->id }}" name="prj_ongoing[]"/>
                                    <span></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            {{ $item->prj_name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>
<div class="col-12 text-right mt-5">
    <div class="separator separator-solid separator-border-2 separator-dark"></div>
</div>
<div class="col-4 mx-auto mt-5">
    <input type="hidden" id="year-post" value="{{ $year }}">
    <button type="button" class="btn btn-primary btn-block btn-lg" id="btn-submit">Save</button>
</div>
