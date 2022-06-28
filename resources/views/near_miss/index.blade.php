@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Near Miss
            </div>
            <div class="card-toolbar">

                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{route('nearmiss.getview')}}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Near Miss</a>
                </div>

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th nowrap="nowrap" class="text-center">Status</th>
                        <th nowrap="nowrap" class="text-center">Date</th>
                        <th nowrap="nowrap" class="text-center">Project</th>
                        <th nowrap="nowrap" class="text-center">Title</th>
                        <th nowrap="nowrap" class="text-center">Short Description</th>
                        <th nowrap="nowrap" class="text-center">Photo</th>
                        <th nowrap="nowrap" class="text-center">Follow Up</th>
                        <th nowrap="nowrap" class="text-center">Follow Up Photo</th>
                        {{--<th nowrap="nowrap" class="text-center">Point</th>--}}
                        <th nowrap="nowrap" class="text-center">Approval</th>
                        <th nowrap="nowrap" data-priority=1 class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($nearmiss as $key => $value)
                        <tr>
                            <td>{{($key+1)}}</td>
                            @if($value->approved == null)
                                <td class="text-center text-danger danger"><i class="fa fa-unlock-alt"></i>&nbsp;Open</td>
                            @else
                                <td class="text-center text-success success"><i class="fa fa-lock"></i>&nbsp;Close</td>
                            @endif
                            <td class="text-center">{{date("d M Y",strtotime($value->date))}}</td>
                            <td class="text-left">
                                {{isset($prj_name[$value->prj])?$prj_name[$value->prj]:'-'}}
                            </td>
                            <td class="text-left"><a href="{{route('nearmiss.nm_view',['id'=>$value->id])}}" class="btn btn-link"><i class="fa fa-search"></i>&nbsp;&nbsp;{{$value->title}}</a></td>
                            <td class="text-center">
                                {{substr(strip_tags($value->deskripsi),0,60) }}
                                @if (strlen($value->deskripsi) > 60)
                                    ... <a href="#" data-toggle="modal" data-target="#modalDeskripsi{{ $value->id }}">Read more</a>
                                    <div class="modal fade" id="modalDeskripsi{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title"></h1>
                                                    <button class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                                </div>
                                                <div class="modal-body">
                                                    {!! $value->deskripsi !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center" width="15%">
                                @if($value->pict == null)
                                    <a href='{{route('nearmiss.getviewphoto',['id' => $value->id,'status' => 'edit'])}}' title='Upload Image'
                                    class='btn btn-primary btn-sm btn-icon'><i class='fa fa-pencil-alt'></i></a>
                                @else
                                    <a class='fancybox' href='{{route('nearmiss.getviewphoto',['id' => $value->id,'status' => 'edit'])}}' data-fancybox-group='gallery' title='{{$value->title}}'>
                                    <img src='{{str_replace('public','public_html',asset('/media/nearmiss_attachment'))}}/{{$value->pict}}' data-fancybox-group='gallery' alt height='40px'/></a>
                                    &nbsp; <a href='{{route('nearmiss.getviewphoto',['id' => $value->id,'status' => 'edit'])}}' class='btn btn-primary btn-xs' title='Edit Photo'><i class='fa fa-pencil-alt'></i></a>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href='{{route('nearmiss.getview',['id' => $value->id,'status' => 'follow'])}}' class='btn btn-primary btn-xs' title='Insert Follow Up'><i class='fa fa-pencil-alt'></i></a>
                            </td>
                            <td class="text-center" width="15%">
                                @if($value->pict_follow == '' || $value->pict_follow == null)
                                    <a href='{{route('nearmiss.getviewphoto',['id' => $value->id,'status' => 'follow'])}}' title='Upload Image' class='btn btn-primary btn-xs'><i class='fa fa-pencil-alt'></i></a>
                                @else
                                    <a href='' target='_new'>
                                        <img src='{{str_replace('public','public_html',asset('/media/nearmiss_attachment/'))}}/{{$value->pict_follow}}' data-fancybox-group='gallery' alt height='40px'/></a>

                                    <a href='{{route('nearmiss.getviewphoto',['id' => $value->id,'status' => 'follow'])}}' class='btn btn-primary btn-xs'><i class='fa fa-pencil-alt'></i></a>
                                @endif
                            </td>
                            {{--<td>--}}
                                {{--@if(($value->warning_letter == '' || $value->warning_letter == null) && ($value->close == '' || $value->close == null))--}}
                                    {{--<a href='#' class='btn btn-link'>Issue<br />Warning Letter</a>--}}
                                {{--@elseif(($value->warning_letter == '' || $value->warning_letter == null) && ($value->close != '' || $value->close != null))--}}
                                    {{--N/A--}}
                                {{--@elseif(($value->warning_letter != '' || $value->warning_letter != null) && ($value->close == '' || $value->close == null))--}}
                                    {{--<a href='' class='btn btn-link'>View<br />Warning Letter</a>--}}
                                {{--@elseif(($value->warning_letter != '' || $value->warning_letter != null) && ($value->close != '' || $value->close != null))--}}

                                    {{--<a href='' class='btn btn-link'>View<br />Warning Letter</a>--}}
                                {{--@else--}}
                                    {{-----}}
                                {{--@endif--}}
                            {{--</td>--}}
                            <td class="text-center">
                                @if((($value->approved == '' || $value->approved == null) && ($value->follow_up_task != '' || $value->follow_up_task != null)))
                                    <form action='{{route('nearmiss.approval')}}' method='post'>
                                        @csrf
                                        <input name='date' type='hidden' value="{{$value->date}}" />
                                        <input name='nama_pelapor' type='hidden' value="{{$value->pelapor_name}}" />
                                        <input name='id_nearmiss' type='hidden' value="{{$value->id}}" />
                                        <button type='submit' class='btn btn-success btn-xs btn-icon' name='app' value='APPROVE' onclick="return confirm('Approve near miss?')">
                                        <i class='fa fa-check'></i></button> |
                                        <button type='submit' class='btn btn-danger btn-xs btn-icon' name='app' value='DENIED' onclick="return confirm('Denied near miss?')" >
                                            <i class='fa fa-window-close'></i>
                                        </button>
                                    </form>
                                @else
                                    @if($value->close != null)
                                        {!! $value->approved."<br/>".date('d M Y',strtotime($value->close)) !!}
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>

                            <td width="10%" class="text-center">
                                &nbsp;&nbsp;&nbsp;
                                <form action='{{route('nearmiss.delete')}}' method='post'>
                                    @csrf
                                    <input name='del_id' type='hidden' value={{$value->id}} />
                                    <button name="submit" class="btn btn-default btn-xs btn-icon" id="submit" value="DEL" type="submit" onclick="return confirm('Delete near miss?')"><i class="fa fa-trash"></i></button>
                                    {{--<button name="transfer" class="btn btn-primary btn-xs" id="transfer" value="TRANSFER" type="submit"><i class="fa fa-exchange-alt"></i></button>--}}
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{asset('theme/tinymce/tinymce.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('table.display').DataTable({
                responsive: true,
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            });

            tinymce.init({
                editor_selector : ".description_area",
                selector:'textarea',
                mode : "textareas",
                menubar: true,
                toolbar: true,
            });
            $("#prev_eq1").hide();
            $("#prev_eq2").hide();
            $("#prev_eq3").hide();
            $("#prev_eq4").hide();
            $("#prev_eq5").hide();
        })

        function editPict(target,img){
            console.log($(img).val())
            if($(img).val()) {
                readURL(img, target);
                $("#"+target).show();
            } else {
                $("#"+target).hide();
            }
        }

        function readURL(input, idn) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + idn).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
