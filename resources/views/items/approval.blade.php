@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Items Approval</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 mx-auto">
                    <table class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">#FR</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $i =>$item)
                                @if (isset($fr[$item->fr_id]))
                                    <tr>
                                        <td align="center">{{ $i+1 }}</td>
                                        <td align="center">{{ $item->name }}</td>
                                        <td align="center"><a href="{{ route('fr.view', ['id'=>$item->fr_id]) }}">{{ $fr[$item->fr_id] }}</a></td>
                                        <td align="center">
                                            <button type="button" onclick="btn_approve({{ $item->item_id }})" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="content-modalApprove">

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function btn_approve(x){
            $("#modalApprove").modal('show')
            $.ajax({
                url: "{{ route('items.approval.get') }}/" + x,
                type: "get",
                success : function(response){
                    $("#content-modalApprove").html(response)
                    $("select.select2").select2({
                        width : "100%"
                    })

                    $("#_item_exist").select2({
                        placeholder : "Select item"
                    })

                    // $("#item-category").on('change', function(){
                    //     console.log(this.value)
                    // })
                    $("#item-category").change(function(){
                        console.log(this.value)
                        $("#item_code").val("")
                        $("#item-class").select2({
                            ajax : {
                                url : "{{ route('items.approval.class.get') }}/" + $("#item-category").val(),
                                dataType : 'json'
                            }
                        })
                    })

                    $("#item-class").change(function(){
                        console.log(this.value)
                        $.ajax({
                            url : "{{ route('items.approval.get.code') }}",
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                cat : $("#item-category").val(),
                                class : $("#item-class").val()
                            },
                            cache : false,
                            success : function(response){
                                $("#item_code").val(response)
                            }
                        })
                    })

                    $("#btn-assign").click(function(e){
                        e.preventDefault()
                        var gr = $(this).parents('div.input-group')
                        var sl = gr.find('select')
                        var form = $(this).parents('form')
                        if(sl.val() == ''){
                            Swal.fire('Empty', 'Item must be filled!', 'error')
                        } else {
                            var req = form.find('input')
                            console.log(req)
                            form.submit()
                        }
                    })
                }
            })
        }

        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
