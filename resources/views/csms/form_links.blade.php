<div class="row">
    <div class="col-md-12">
        <div class="card card-custom gutter-b bg-light-secondary">
            <div class="card-header">
                <h3 class="card-title">Upload Link</h3>
                <div class="card-toolbar">
                    <button type="button" data-toggle="modal" onclick="modal_link({{ $item->id }})" data-target="#linkAddModal" class="btn btn-primary btn-icon btn-sm"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($links as $link)
                        @if ($link->id_step == $item->id)
                            <div class="col-12">
                                <div class="card card-custom gutter-b">
                                    <div class="card-header">
                                        <h3 class="card-title"></h3>
                                        <div class="card-toolbar">
                                            <a href="{{ route('qhse.csms.links.delete', $link->id) }}" class="btn btn-danger btn-icon" onclick="return confirm('delete?')"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <iframe src="{{ $link->links }}?csms=on" width="100%" height="500px" frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

