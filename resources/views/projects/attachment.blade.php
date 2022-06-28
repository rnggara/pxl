@extends('layouts.template')
@section('content')
<div class="card card-custom gutter-b">
	<div class="card-header">
		<h3 class="card-title">Files Attachment - <span class="text-primary">{{$project->prj_name}}</span></h3>
		<div class="card-toolbar">
			<button type="button" data-toggle="modal" data-target="#addItem" class="btn btn-primary"><i class="fa fa-plus"></i> Add File</button>
		</div>
	</div>
	<div class="card-body">
		<div class="row mb-5">
			<div class="col-6">
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-primary" id="btn-all">All</button>
					<button type="button" class="btn btn-sm btn-success" id="btn-skpi">SKPI</button>
					<button type="button" class="btn btn-sm btn-info" id="btn-skpp">SKPP</button>
					<button type="button" class="btn btn-sm btn-warning" id="btn-contract">Contract</button>
					<button type="button" class="btn btn-sm btn-danger" id="btn-photo">Photo</button>
					<button type="button" class="btn btn-sm btn-dark" id="btn-quot">Quotation</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-bordered display table-hover">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Document Name</th>
							<th class="text-center">Type</th>
							<th class="text-center">File</th>
							<th class="text-center">Uploaded Date</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						@foreach($files as $i => $item)
							<?php
							switch ($item->type) {
								case 'skpi':
									$bg = "success";
									break;
								case 'skpp':
									$bg = "info";
									break;
								case 'contract':
									$bg = "warning";
									break;
								case 'photo':
									$bg = "danger";
									break;
								case 'quotation':
									$bg = "dark";
									break;
								default:
									$bg = "default";
									break;
							}
							 ?>
							<tr>
								<td align="center">{{$i+1}}</td>
								<td>{{$item->document_name}}</td>
								<td align="center">
									<span class="label label-inline label-{{$bg}}">{{strtoupper($item->type)}}</span>
								</td>
								<td align="center">
                                    @if (!empty($item->old_id))
                                        <a href="{{str_replace("public", "public_html", asset("media/projects/".$item->file_hash))}}" target="_blank" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-download"></i></a>
                                    @else
                                        <a href="{{route('download', $item->file_hash)}}" class="btn btn-xs btn-icon btn-primary"><i class="fa fa-download"></i></a>
                                    @endif
								</td>
								<td align="center">{{date('d F Y', strtotime($item->created_at))}}</td>
								<td align="center">
									<button type="button" onclick="delete_item('{{$item->id}}')" class="btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addItem" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<h3>Add Files</h3>
            </div>
	        <form method="post" action="{{route('marketing.project.attachment.add')}}" enctype="multipart/form-data">
	        	@csrf
	        	<div class="modal-body">
	            	<div class="form-group row">
	            		<label class="col-form-label col-3">Document Name</label>
	            		<div class="col-9">
	            			<input type="text" class="form-control" name="doc_name">
	            		</div>
	            	</div>
	            	<div class="form-group row">
	            		<label class="col-form-label col-3">Document Type</label>
	            		<div class="col-9">
	            			<select class="form-control select2" name="type" required="">
	            				<option value="">Select Type</option>
	            				<option value="skpi">SKPI</option>
	            				<option value="skpp">SKPP</option>
	            				<option value="contract">Contract</option>
	            				<option value="photo">Photo</option>
	            				<option value="quotation">Quotation</option>
	            			</select>
	            		</div>
	            	</div>
	            	<div class="form-group row">
	            		<label class="col-form-label col-3">File</label>
	            		<div class="col-9">
	            			<div class="custom-file">
	            				<input type="file" class="custom-file-input" name="_file">
	            				<span class="custom-file-label">Choose File</span>
	            			</div>
	            		</div>
	            	</div>
	            </div>
	            <div class="modal-footer">
	            	<input type="hidden" name="id_project" value="{{$project->id}}">
	            	<button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
	            	<button type="submit" class="btn btn-primary">Add</button>
	            </div>
	        </form>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript">
	function delete_item(x){
		Swal.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		    location.href = "{{route('marketing.project.attachment.delete')}}/"+x
		  }
		})
	}

	function table_filter(t, x){
		console.log(x)
		t.column(2).search(x).draw();
	}
	$(document).ready(function(){
		$("select.select2").select2({
			width: '100%'
		})
		var table = $("table.display").DataTable({
			pageLength: 100
		})

		$("#btn-all").click(function(){
			table_filter(table, "")
		})

		$("#btn-skpi").click(function(){
			table_filter(table, "skpi")
		})

		$("#btn-skpp").click(function(){
			table_filter(table, "skpp")
		})

		$("#btn-contract").click(function(){
			table_filter(table, "contract")
		})

		$("#btn-photo").click(function(){
			table_filter(table, "photo")
		})

		$("#btn-quot").click(function(){
			table_filter(table, "quotation")
		})
	})
</script>
@endsection
