<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hrd_training;
use App\Models\Hrd_training_users;
use App\Models\Hrd_training_syllabus;
use App\Models\Hrd_training_link_video;
use App\Models\Hrd_setting_point;
use Session;

class HrdTrainingController extends Controller
{
	public function index()
	{
		$number = 1;

		$settingPoint = Hrd_setting_point::first();

		$datetimeToday = date('Y-m-d H:i:s');
		
		$trainingStatus = [];
		$syllabusDocs = [];
		$syllabusVids = [];
		$hrdTrainings = Hrd_training::all();
		foreach ($hrdTrainings as $hrdTraining) 
		{
			//training status
			if(strtotime($datetimeToday) < strtotime($hrdTraining->start_date))
			{
				$trainingStatus [$hrdTraining->id]= 'UPCOMING';
			}
			elseif(strtotime($datetimeToday) > strtotime($hrdTraining->deadline)) 
			{
				$trainingStatus [$hrdTraining->id]= 'FINISHED';
			}
			else
			{
				$trainingStatus [$hrdTraining->id]= 'ONGOING';
			}

			//syllabus doc
			$syllabusDocs [$hrdTraining->id]= Hrd_training_syllabus::where('id_hrd_training', $hrdTraining->id)->get();

			//syllabus vid
			$syllabusVids [$hrdTraining->id]= Hrd_training_link_video::where('id_hrd_training', $hrdTraining->id)->get();
			
		}

		return view('training.index', compact('number','settingPoint','hrdTrainings','trainingStatus','syllabusDocs','syllabusVids'));
	}
	
	public function store(Request $request)
	{
		$start_date = date("Y-m-d", strtotime($request->start_date));
		$start_time = date("H:i", strtotime($request->start_date2));
		$deadline = date("Y-m-d", strtotime($request->deadline));
		$deadline_time = date("H:i", strtotime($request->deadline2));

		$start_date_string = $start_date." ".$start_time;
		$deadline_string = $deadline." ".$deadline_time;

		$hrdTraining = new Hrd_training;
		$hrdTraining->title = $request->title;
		$hrdTraining->type = $request->type;
		$hrdTraining->description = $request->description;
		$hrdTraining->link = $request->link;
		$hrdTraining->complete_point = $request->complete_point;
		$hrdTraining->minus_point = $request->minus_point;
		$hrdTraining->pass_score = $request->pass_score;
		$hrdTraining->start_date = $start_date_string;
		$hrdTraining->deadline = $deadline_string;
		$hrdTraining->active = 1;
		$hrdTraining->save();

		//Upload syllabus doc
		$file = $request->file('syllabus_document');
		$uploadDir = public_path('hrd\\uploads');

		if(!empty($file) && count($file) > 0)
		{
			foreach ($file as $key => $value) 
			{
				if($value->isValid())
				{
					$indexFile = $key + 1;
					$renameFile = date('Ymd', strtotime($request->input('date')))."T".date('His')."_syllabus_".$indexFile.".".$value->extension();

					$syllabusDoc = new Hrd_training_syllabus;
					$syllabusDoc->id_hrd_training = $hrdTraining->id;
					$syllabusDoc->name = $renameFile;
					$syllabusDoc->type = $value->extension();
					$syllabusDoc->save();

					$value->move($uploadDir,$renameFile);
				}
			}
		}

		//Insert link video
		$video = $request->video_link;
		
		if(!empty($video) && count($video) > 0)
		{
			foreach ($video as $value) 
			{
				$syllabusVid = new Hrd_training_link_video;
				$syllabusVid->id_hrd_training = $hrdTraining->id;
				$syllabusVid->link = $value;
				$syllabusVid->save();
			}
		}

		return redirect()->route('training.index');
	}

	public function settingPoint(Request $request)
	{
		$settingPoint = Hrd_setting_point::first();
		if($settingPoint)
		{
			$updateSettingPoint = Hrd_setting_point::find($settingPoint->id);
			$updateSettingPoint->complete_point = $request->completion_point;
			$updateSettingPoint->minus_point = $request->minus_point;
			$updateSettingPoint->max_minus_point = $request->max_minus_point;
			$updateSettingPoint->save();
		}
		else
		{
			$updateSettingPoint = new Hrd_setting_point;
			$updateSettingPoint->complete_point = $request->completion_point;
			$updateSettingPoint->minus_point = $request->minus_point;
			$updateSettingPoint->max_minus_point = $request->max_minus_point;
			$updateSettingPoint->save();
		}

		return redirect()->route('training.index');
	}

	public function update($id, Request $request)
	{
		$start_date = date("Y-m-d", strtotime($request->start_date));
		$start_time = date("H:i", strtotime($request->start_date2));
		$deadline = date("Y-m-d", strtotime($request->deadline));
		$deadline_time = date("H:i", strtotime($request->deadline2));

		$start_date_string = $start_date." ".$start_time;
		$deadline_string = $deadline." ".$deadline_time;

		$hrdTraining = Hrd_training::find($id);
		$hrdTraining->title = $request->title;
		$hrdTraining->type = $request->type;
		$hrdTraining->description = $request->description;
		$hrdTraining->link = $request->link;
		$hrdTraining->complete_point = $request->complete_point;
		$hrdTraining->minus_point = $request->minus_point;
		$hrdTraining->pass_score = $request->pass_score;
		$hrdTraining->start_date = $start_date_string;
		$hrdTraining->deadline = $deadline_string;
		$hrdTraining->save();

		//Upload syllabus doc
		$file = $request->file('syllabus_document');
		$uploadDir = public_path('hrd\\uploads');

		if(!empty($file) && count($file) > 0)
		{
			foreach ($file as $key => $value) 
			{
				if($value->isValid())
				{
					$indexFile = $key + 1;
					$renameFile = date('Ymd', strtotime($request->input('date')))."T".date('His')."_syllabus_".$indexFile.".".$value->extension();

					$syllabusDoc = new Hrd_training_syllabus;
					$syllabusDoc->id_hrd_training = $hrdTraining->id;
					$syllabusDoc->name = $renameFile;
					$syllabusDoc->type = $value->extension();
					$syllabusDoc->save();

					$value->move($uploadDir,$renameFile);
				}
			}
		}

		//Insert link video
		$video = $request->video_link;
		
		if(!empty($video) && count($video) > 0)
		{
			foreach ($video as $value) 
			{
				$syllabusVid = new Hrd_training_link_video;
				$syllabusVid->id_hrd_training = $hrdTraining->id;
				$syllabusVid->link = $value;
				$syllabusVid->save();
			}
		}

		return redirect()->route('training.index');
	}

	public function deleteDoc($docid,Request $request)
	{
		Hrd_training_syllabus::find($docid)->delete();
		return redirect()->route('training.index');
	}

	public function deleteVid($vidid,Request $request)
	{
		Hrd_training_link_video::find($vidid)->delete();
		return redirect()->route('training.index');
	}
}
