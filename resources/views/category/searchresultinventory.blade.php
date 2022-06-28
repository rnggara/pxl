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
            <form method="post" action="{{route('categoryinventory.search')}}">
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
                    </tr>
                    </thead>
                    <tbody>

                    @if(isset($items_array['id']))
                        @foreach($items_array['id'] as $key => $value)
                            <tr>
                                @php
                                    $count_score = 0;
                                @endphp
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
                                    <a href="{{ route('itemsInventory.detail', $key) }}">
                                        {{(isset($items_array['name'][$key]))?$items_array['name'][$key]:''}}
                                        {{(isset($items_array['series'][$key]))?$items_array['series'][$key]:''}}
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.display').DataTable({
                "order": [[ 0, "desc" ]],
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });
        });
    </script>
@endsection
