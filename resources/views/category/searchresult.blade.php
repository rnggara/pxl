@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Search Result of &nbsp;
                        @foreach($searchArr as $key => $value)
                            <button type="button" class="btn btn-xs btn-secondary">"{{$value}}"</button>
                        @endforeach
                    </h5>

                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('category.index')}}" class="btn btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('category.search')}}">
                @csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-right"></label>
                    <div class="col-md-6">
                        <input type="text" name="search_val" id="search_val" class="form-control" placeholder="Search here.." required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th nowrap="nowrap" class="text-center">Score</th>
                        <th nowrap="nowrap" class="text-center">Item Code</th>
                        <th nowrap="nowrap" class="text-left">Item Name</th>
                        <th nowrap="nowrap" class="text-left">Category</th>
                        <th nowrap="nowrap" class="text-left">Classification</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items_array as $key => $item)
                        @php
                            $link = null;
                            if (isset($dep[$item['id']])) {
                                $link = route('finance.dp.detail', $dep[$item['id']]);
                            }
                        @endphp
                        <tr>
                            <th class="text-center">{{ $key+1 }} </th>
                            <th class="text-center">{{ $item['code'] }}</th>
                            <th class="text-left" nowrap="nowrap">
                                <a href="javascript:edit_item({{ $item['id'] }})">
                                    {{ $item['name'] }}
                                </a>
                                @if (!empty($link))
                                <a href="{{ $link }}" target="_blank" class="btn btn-outline-info btn-icon btn-xs" data-toggle="tooltip" title="Depreciation"><i class="fa fa-compress-alt"></i></a>
                                @endif
                            </th>
                            <th class="text-center"><label for="" class="text-primary">{{ $item['cat'] }}</th></label></th>
                            <th class="text-center"><label for="" class="text-primary">{{ $item['class'] }}</th></label></th>
                        </tr>
                    @endforeach

                    @if(isset($items_array['id']))
                        @php
                            $count_score = 0;
                        @endphp

                        {{-- @foreach($items_array['id'] as $key => $value)
                            <tr>
                                <th nowrap="nowrap" class="text-center">
                                    @for($i = 0; $i<count($items_array['id_count']); $i++)
                                        @if($items_array['id_count'][$i] == $key)
                                            @php
                                                /** @var TYPE_NAME $count_score */

                                                $count_score += 1;
                                            @endphp
                                        @endif
                                    @endfor
                                    {{$count_score}}
                                </th>
                                <th nowrap="nowrap" class="text-center">{{(isset($items_array['code'][$key]))?$items_array['code'][$key]:''}}</th>
                                <th nowrap="nowrap" class="text-left">
                                    <a href="{{(isset($items_array['cat'][$key]) && isset($items_array['class'][$key]))?route('items.index',['category' => $items_array['cat_id'][$key],'classification' => $items_array['class_id'][$key]]):'#'}}" onclick="edit_item({{ $items_array['class_id'][$key] }})">
                                        {{(isset($items_array['name'][$key]))?$items_array['name'][$key]:''}}
                                    </a>
                                    {{ $items_array['score'][$key] }}
                                </th>
                                <td nowrap="nowrap"><label for="" class="text-primary">{!! (isset($items_array['cat'][$key]))?$items_array['cat'][$key]:'' !!}</label></td>
                                <td nowrap="nowrap"><label for="" class="text-primary">{!! (isset($items_array['class'][$key]))?$items_array['class'][$key]:'' !!}</label></td>
                            </tr>
                        @endforeach --}}
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('items.edit')}}" id="form-edit" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Basic Information</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Item Name</label>
                                    <div class="col-md-9">
                                    @actionStart('item_database', 'approvedir')
                                        <input type="text" class="form-control" placeholder="Item Name" id="item_name" name="item_name" required>
                                    @actionElse
                                        <input type="text" class="form-control bg-secondary" readonly placeholder="Item Name" id="item_name" name="item_name">
                                    @actionEnd
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Brand Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Brand Name" id="item_series" name="item_series" required>
                                    </div>
                                </div>
                                <input type="hidden" name="class_hidden" id="class_hidden" value="">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Serial Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Serial Number" id="serial_number" name="serial_number" required>
                                    </div>
                                </div>
                                <br>
                                <h4>Detail Info</h4>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Type</label>
                                    <div class="col-md-9">
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="1">Consumable</option>
                                            <option value="2">Non Consumable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Minimal Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" placeholder="Minimal Stock" id="minimal_stock" name="min_stock" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">UoM</label>
                                    <div class="col-md-9">
                                        <select name="uom" id="uomedit" class="form-control" required>
                                            <option value="">- Select UOM -</option>
                                            @foreach($uom as $v)
                                                <option value="{{$v}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Picture</label>
                                    <div class="col-md-9">
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="image-input image-input-outline" id="app_logo">
                                                <div class="image-input-wrapper"></div>
                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                    <input type="file" name="pict" id="p_logo_edit" accept=".png, .jpg, .jpeg" />
                                                </label>
                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                            </div>
                                            <span class="form-text text-muted">
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-success">
                                                <input type="checkbox" name="del_pict"/>
                                                <span></span>
                                                Check this to delete the picture
                                            </label>
                                        </div>
                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Notes</label>
                                    <div class="col-md-9">
                                        <textarea name="notes" class="form-control" id="notes" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right">Specification</label>
                                    <div class="col-md-9">
                                        <textarea name="specification" class="form-control" id="specification" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_item" name="id_item">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="button" onclick="button_edit()" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script type="text/javascript">
        function edit_item(id){
            $("#editItem").modal('show')
            $.ajax({
                url: '{{URL::route('items.find')}}',
                data: {
                    '_token': '{{csrf_token()}}',
                    'id': id
                },
                type: "POST",
                cache: false,
                dataType: 'json',
                success : function(response){
                    $("#id_item").val(response.item.id)
                    $("#item_name").val(response.item.name)
                    $("#item_code").val(response.item.item_code)
                    $("#item_series").val(response.item.item_series)
                    $("#serial_number").val(response.item.serial_number)
                    $("#price").val(response.item.price)
                    $("#notes").val(response.item.notes)
                    $("#specification").val(response.item.specification)
                    $("#minimal_stock").val(response.item.minimal_stock)
                    $("#supplier").val(response.item.supplier).trigger('change')
                    $("#category").val(response.item.category_id).trigger('change')
                    $("#type").val(response.item.type_id).trigger('change')
                    $("#uomedit").val(response.item.uom).trigger('change')
                    var stock = $(".stocks").toArray()
                    var total_stock = 0

                    $("#total-stock").val(total_stock)
                    if (total_stock < $("#minimal_stock").val()){
                        $("#alert-stock").show()
                    } else {
                        $("#alert-stock").hide()
                    }
                    if (response.item.picture !== null){
                        var imgUrl = "{{str_replace("\\", "/", str_replace('public', 'public_html', asset('media/asset/')))}}/" + response.item.picture
                        $("#app_logo .image-input-wrapper").css('background-image', "url('"+imgUrl+"')")
                    }
                }
            })
        }
        function button_edit(){
            Swal.fire({
                title: "Edit data",
                text: "Are you sure you want to edit this data?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Edit",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $("#form-edit").submit()
                }
            })
        }
        $(document).ready(function () {
            $('.display').DataTable({
                searching: false,
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
