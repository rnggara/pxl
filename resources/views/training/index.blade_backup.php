@extends('layouts.template')

@section('content')
	<div class="card card-custom gutter-b">
		<div class="card-header">
			<div class="card-title">
				<h3>Training</h3><br>
			</div>
			<div class="card-toolbar">
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalSettingPoint">Setting Point</button>
				</div>
				&nbsp;
				<div class="btn-group" role="group" aria-label="Basic example">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItem"><i class="fa fa-plus"></i>New Record</button>
				</div>
			</div>
		</div>
		<div class="card-body">
			<table class="table display">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Title</th>
						<th class="text-center">Description</th>
						<th class="text-center">Status</th>
						<th class="text-center">Type</th>
						<th class="text-center">Completion Point</th>
						<th class="text-center">Minus Point</th>
						<th class="text-center">Start Date</th>
						<th class="text-center">Deadline</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($hrdTrainings as $hrdTraining)
					<tr>
						<td align="center">{{$number++}}</td>
						<td align="center">{{$hrdTraining->title}}</td>
						<td align="center">{!!$hrdTraining->description!!}</td>
						<td align="center">{{$trainingStatus[$hrdTraining->id]}}</td>
						<td align="center">{{$hrdTraining->type}}</td>
						<td align="center">{{$hrdTraining->complete_point}}</td>
						<td align="center">{{$hrdTraining->minus_point}}</td>
						<td align="center">{{$hrdTraining->start_date}}</td>
						<td align="center">{{$hrdTraining->deadline}}</td>
						<td align="center">
							@if($syllabusDocs[$hrdTraining->id] || $syllabusVids[$hrdTraining->id])
								<a href="#file{{$hrdTraining->id}}" data-toggle="modal" class="btn btn-sm btn-success btn-icon btn-icon-md" title="Edit"><i class="fa fa-file"></i></a>
							@endif

							<a href="#edit{{$hrdTraining->id}}" data-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="fa fa-edit"></i></a>

                            <a href="#delete{{$hrdTraining->id}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" data-toggle="modal"><i class="fa fa-trash"></i></a>
						</td>
					</tr>

					{{-- BEGIN MODAL EDIT --}}
					<div class="modal fade" id="edit{{$hrdTraining->id}}" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Edit Training</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
									</button>
								</div>
								<form method="post" action="{{URL::route('training.update',$hrdTraining->id)}}">
									@csrf
									<div class="modal-body">

										<div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" name="title" required="true" value="{{$hrdTraining->title}}">
										</div>
										<div class="form-group">
											<label>Description</label>
											<textarea class="form-control" name="description" required="true">{!!$hrdTraining->description!!}</textarea>
										</div>
										<div class="form-group">
											<label>Training Website</label>
											<input type="text" class="form-control" name="link" required="true" placeholder="URL" value="{{$hrdTraining->link}}">
										</div>
										<div class="form-group">
											<label>Type</label>
											<select class="form-control" name="type" id="type{{$hrdTraining->id}}" required="true" onChange="changetrainingtype{{$hrdTraining->id}}();">
												<option value="">Choose</option>
												<option value="Mandatory" {{($hrdTraining->type === "Mandatory")? 'selected="selected"':""}}>Mandatory</option>
												<option value="Optional" {{($hrdTraining->type === "Optional")? 'selected="selected"':""}}>Optional</option>
											</select>
										</div>
										<div class="form-group">
											<label>Completion Point</label>
											<input type="number" class="form-control" id="complete_point{{$hrdTraining->id}}"  min="0" name="complete_point" required="true" value="{{$hrdTraining->complete_point}}">
										</div>
										<div class="form-group">
											<label>Minus Point</label>
											<div class="input-group">
												<input type="number" class="form-control" id="minus_point{{$hrdTraining->id}}" name="minus_point" min="0" required="true" aria-describedby="basic-addon2" value="{{$hrdTraining->minus_point}}">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">/ Day</span></div>
											</div>
										</div>
										<div class="form-group">
											<label>Pass Score</label>
											<input type="number" class="form-control" id="pass_score{{$hrdTraining->id}}" name="pass_score" min="0" required="true" value="{{$hrdTraining->pass_score}}">
										</div>
										<div class="form-group">
											<label>Start Date</label>
											<div class="row">
												<div class="col-md-6">
													<input type="date" min="{{date('Y-m-d')}}" class="form-control" id="start_date" name="start_date" required="true" value="{{date('Y-m-d',strtotime($hrdTraining->start_date))}}">
												</div>
												<div class="col-md-6">
													<input type="time" class="form-control" id="start_date2" name="start_date2" required="true" value="{{date('H:i',strtotime($hrdTraining->start_date))}}">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Deadline</label>
											<div class="row">
												<div class="col-md-6">
													<input type="date" class="form-control" id="deadline" name="deadline" required="true" value="{{date('Y-m-d',strtotime($hrdTraining->deadline))}}">
												</div>
												<div class="col-md-6">
													<input type="time" class="form-control" id="deadline2" name="deadline2" required="true" value="{{date('H:i',strtotime($hrdTraining->deadline))}}">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Syllabus Document</label>
											<input type="file" multiple="multiple" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" class="form-control" name="syllabus_document[]" required="true">
										</div>
										<div class="form-group">
											<label>Syllabus Video</label>
											<div id="divVidLink{{$hrdTraining->id}}">
												<div class="row">
													<div class="col-md-9">
														<input type="text" name="video_link[]" class="form-control" placeholder="URL">&nbsp;
													</div>
													<div class="col-md-3">
														<button type="button" class="btn btn-success" id="editVidLinkBtn{{$hrdTraining->id}}">+</button>
													</div>
												</div>
											</div>
										</div>

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
										<button type="submit" name="submit" class="btn btn-primary font-weight-bold">
											<i class="fa fa-check"></i>
											Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					{{-- END MODAL EDIT --}}

					{{-- BEGIN MODAL FILE --}}
					<div id="file{{$hrdTraining->id}}" class="modal fade" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Syllabus</h5>
									 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
									</button>
								</div>
								<div class="modal-body">

									<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x nav-tabs-line-success" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#document{{$hrdTraining->id}}" role="tab">Document</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#video{{$hrdTraining->id}}" role="tab">Video</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="document{{$hrdTraining->id}}" role="tabpanel">
											&nbsp;
											@foreach($syllabusDocs[$hrdTraining->id] as $syllabusDoc)
												<p>
													<a href="{{asset('../public_html/hrd/uploads')}}/{{$syllabusDoc->name}}" target="_blank">{{$syllabusDoc->name}}</a>
													&nbsp;
													<a href="#deleteDoc{{$hrdTraining->id}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" data-toggle="modal"><i class="fa fa-trash"></i></a>
												</p>
												{{-- BEGIN MODAL DELETE DOCUMENT --}}
												<div id="deleteDoc{{$hrdTraining->id}}" class="modal fade" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<i aria-hidden="true" class="ki ki-close"></i>
																</button>
															</div>
															<form method="post" action="{{URL::route('training.deletedoc',$syllabusDoc->id)}}">
															@csrf
															<div class="modal-body">
																Are you sure want to delete this document?
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
																<button type="submit" name="submit" class="btn btn-success">Yes</button>
															</div>
															</form>
														</div>
													</div>
												</div>
												{{-- END MODAL DELETE DOCUMENT --}}
											@endforeach
										</div>
										<div class="tab-pane" id="video{{$hrdTraining->id}}" role="tabpanel">
											&nbsp;
											@foreach($syllabusVids[$hrdTraining->id] as $syllabusVid)
												<p>
													<a href="{{$syllabusVid->link}}" target="_blank">{{$syllabusVid->link}}</a>
													&nbsp;
													<a href="#deleteVid{{$hrdTraining->id}}" title="Delete" class="btn btn-sm btn-danger btn-icon btn-icon-md" data-toggle="modal"><i class="fa fa-trash"></i></a>
												</p>
												{{-- BEGIN MODAL DELETE VIDEO --}}
												<div id="deleteVid{{$hrdTraining->id}}" class="modal fade" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<i aria-hidden="true" class="ki ki-close"></i>
																</button>
															</div>
															<form method="post" action="{{URL::route('training.deletevid',$syllabusVid->id)}}">
															@csrf
															<div class="modal-body">
																Are you sure want to delete this video link?
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
																<button type="submit" name="submit" class="btn btn-success">Yes</button>
															</div>
															</form>
														</div>
													</div>
												</div>
												{{-- END MODAL DELETE VIDEO --}}
											@endforeach
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					{{-- END MODAL FILE --}}
					<script>
					function changetrainingtype{{$hrdTraining->id}}()
					{
						if ($("#type{{$hrdTraining->id}}").val() === "Mandatory") {
							console.log("ini")
							$("#complete_point{{$hrdTraining->id}}").prop("readonly", true);
							$("#complete_point{{$hrdTraining->id}}").val({{$settingPoint['complete_point']}});
							$("#minus_point{{$hrdTraining->id}}").prop("readonly", true);
							$("#minus_point{{$hrdTraining->id}}").val({{$settingPoint['minus_point']}});
						}
						else
						{
							$("#complete_point{{$hrdTraining->id}}").prop("readonly", false);
							$("#minus_point{{$hrdTraining->id}}").prop("readonly", false);
						}
					}

					$("#editVidLinkBtn{{$hrdTraining->id}}").click(function(){
						console.log('asdasd');
						$('#divVidLink{{$hrdTraining->id}}').append(
							'<div class="row">'
								+'<div class="col-md-9">'
									+'<input type="text" name="video_link[]" class="form-control" placeholder="URL">&nbsp;'
								+'</div>'
								+'<div class="col-md-3">'
									+'<button type="button" class="btn btn-danger delVidLinkBtn">x</button>'
								+'</div>'
							+'</div>'
						);
					});

					$("#divVidLink{{$hrdTraining->id}}").on('click','.delVidLinkBtn',function(){
						$(this).parent().parent().remove();
					});
					</script>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	{{-- BEGIN MODAL ADD --}}
	<div class="modal fade" id="addItem" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Training</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
				</div>
				<form method="post" action="{{URL::route('training.store')}}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">

						<div class="form-group">
							<label>Title</label>
							<input type="text" class="form-control" id="title" name="title" required="true">
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control" id="description" name="description" required="true"></textarea>
						</div>
						<div class="form-group">
							<label>Training Website</label>
							<input type="text" class="form-control" id="link" name="link" required="true" placeholder="URL">
						</div>
						<div class="form-group">
							<label>Type</label>
							<select class="form-control" name="type" id="type" required="true" onChange="changetrainingtype();">
								<option value="">Choose</option>
								<option value="Mandatory">Mandatory</option>
								<option value="Optional">Optional</option>
							</select>
						</div>
						<div class="form-group">
							<label>Completion Point</label>
							<input type="number" class="form-control" id="complete_point"  min="0" name="complete_point" required="true">
						</div>
						<div class="form-group">
							<label>Minus Point</label>
							<div class="input-group">
								<input type="number" class="form-control" id="minus_point" name="minus_point" min="0" required="true" aria-describedby="basic-addon2">
								<div class="input-group-append"><span class="input-group-text" id="basic-addon2">/ Day</span></div>
							</div>
						</div>
						<div class="form-group">
							<label>Pass Score</label>
							<input type="number" class="form-control" id="pass_score" name="pass_score" min="0" required="true">
						</div>
						<div class="form-group">
							<label>Start Date</label>
							<div class="row">
								<div class="col-md-6">
									<input type="date" min="{{date('Y-m-d')}}" class="form-control" id="start_date" name="start_date" required="true">
								</div>
								<div class="col-md-6">
									<input type="time" class="form-control" id="start_date2" name="start_date2" required="true">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Deadline</label>
							<div class="row">
								<div class="col-md-6">
									<input type="date" class="form-control" id="deadline" name="deadline" required="true">
								</div>
								<div class="col-md-6">
									<input type="time" class="form-control" id="deadline2" name="deadline2" required="true">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Syllabus Document</label>
							<input type="file" multiple="multiple" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" class="form-control" name="syllabus_document[]" required="true">
						</div>
						<div class="form-group">
							<label>Syllabus Video</label>
							<div id="divVidLink">
								<div class="row">
									<div class="col-md-9">
										<input type="text" name="video_link[]" class="form-control" placeholder="URL">&nbsp;
									</div>
									<div class="col-md-3">
										<button type="button" class="btn btn-success" id="addVidLinkBtn">+</button>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
						<button type="submit" name="submit" class="btn btn-primary font-weight-bold">
							<i class="fa fa-check"></i>
							Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	{{-- END MODAL ADD --}}

	{{-- BEGIN MODAL SETTING POINT --}}
	<div id="modalSettingPoint" class="modal fade" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Setting Mandatory Point</h5>
					 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close"></i>
					</button>
				</div>
				<form method="post" action="{{URL::route('settingpoint.store')}}">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Completion Point</label>
								<input type="number" class="form-control" min="0" name="completion_point" required="true" value="{{(isset($settingPoint['complete_point']) ? $settingPoint['complete_point'] : "")}}">
							</div>

							<div class="form-group">
								<label>Minus Point</label>
								<div class="input-group">
									<input type="number" class="form-control" name="minus_point" min="0" required="true" aria-describedby="basic-addon2" value="{{(isset($settingPoint['minus_point'])) ? $settingPoint['minus_point'] : ""}}">
									<div class="input-group-append"><span class="input-group-text" id="basic-addon2">/ Day</span></div>
								</div>
							</div>

							<div class="form-group">
								<label>Max Minus Point</label>
								<div class="input-group">
									<input type="number" class="form-control" name="max_minus_point" min="0" required="true" aria-describedby="basic-addon2" value="{{(isset($settingPoint['max_minus_point'])) ? $settingPoint['max_minus_point'] : ""}}">
									<div class="input-group-append"><span class="input-group-text" id="basic-addon2">/ Employee</span></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" name="submit" class="btn btn-success">Save</button>
				</div>
				</form>
			</div>
		</div>
	</div>
	{{-- END MODAL SETTING POINT --}}
@endsection

@section('custom_script')
<script>
$("table.display").DataTable({
	fixedHeader: true,
	fixedHeader: {
		headerOffset: 90
	}
})

function changetrainingtype()
{
	if ($("#type").val() === "Mandatory") {
		$("#complete_point").prop("readonly", true);
		$("#complete_point").val({{(isset($settingPoint['complete_point'])) ? $settingPoint['complete_point'] : ""}});
		$("#minus_point").prop("readonly", true);
		$("#minus_point").val({{(isset($settingPoint['minus_point'])) ? $settingPoint['minus_point'] : ""}});
	}
	else
	{
		$("#complete_point").prop("readonly", false);
		$("#minus_point").prop("readonly", false);
	}
}

$("#addVidLinkBtn").click(function(){
	$('#divVidLink').append(
		'<div class="row">'
			+'<div class="col-md-9">'
				+'<input type="text" name="video_link[]" class="form-control" placeholder="URL">&nbsp;'
			+'</div>'
			+'<div class="col-md-3">'
				+'<button type="button" class="btn btn-danger delVidLinkBtn">x</button>'
			+'</div>'
		+'</div>'
	);
});

$("#divVidLink").on('click','.delVidLinkBtn',function(){
	$(this).parent().parent().remove();
});
</script>
@endsection
