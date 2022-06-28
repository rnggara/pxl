<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee;
use App\Models\Rms_divisions;
use Illuminate\Http\Request;
use App\Models\Hrd_training;
use App\Models\Hrd_training_users;
use App\Models\Hrd_training_syllabus;
use App\Models\Hrd_training_link_video;
use App\Models\Hrd_setting_point;
use Session;
use DB;


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

        if (isset($request['detail'])){
            return redirect()->route('training.detail',['id' => $request['detail']]);
        } else {
            return redirect()->route('training.index');

        }
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

	public function getDetailTraining($id){
        $settingPoint = Hrd_setting_point::first();
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
	    $training = Hrd_training::where('id', $id)->first();
	    $syllabus = Hrd_training_syllabus::where('id_hrd_training', $id)->get();
	    $video = Hrd_training_link_video::where('id_hrd_training', $id)->get();
	    $users = Hrd_training_users::where('training_id', $id)->get();

	    $employee = Hrd_employee::whereIn('company_id', $id_companies)->get();
	    $emp =[];
        $emp_position = [];
        $emp_point_mandatory = [];

	    foreach ($employee as $key => $value){
            $emp[$value->id]['emp_name'] = $value->emp_name;
            $emp_position[$value->id]['emp_position'] = $value->emp_position;
            $emp_point_mandatory[$value->id]['emp_point_mandatory'] = $value->point_mandatory;

        }
	    $hrdSettingpoint = Hrd_setting_point::first();
//	    dd($hrdSettingpoint->max_minus_point);
        $datetimeToday = date('Y-m-d H:i:s');
        $trainingStatus = [];
        $hrdTrainings = Hrd_training::all();
        foreach ($hrdTrainings as $hrdTraining) {
            //training status
            if (strtotime($datetimeToday) < strtotime($hrdTraining->start_date)) {
                $trainingStatus [$hrdTraining->id] = 'UPCOMING';
            } elseif (strtotime($datetimeToday) > strtotime($hrdTraining->deadline)) {
                $trainingStatus [$hrdTraining->id] = 'FINISHED';
            } else {
                $trainingStatus [$hrdTraining->id] = 'ONGOING';
            }
        }


        $hrd_emp_pos = Hrd_employee::select('emp_position')
            ->where('emp_position','!=' ,'')
            ->whereNotNull('emp_position')
            ->whereIn('company_id', $id_companies)
            ->groupBy('emp_position')->get();

        $divisions = Rms_divisions::where('name','!=','admin')
                        ->whereIn('id_company', $id_companies)->get();
//        dd($hrd_emp_pos);

//        dd($training->id);
//        dd($training);
        return view('training.detail_users', [
            'emp_name' => $emp,
            'emp_position' => $emp_position,
            'emp_point_mandatory' => $emp_point_mandatory,
            'detail' => $training,
            'syllabus' => $syllabus,
            'videos' => $video,
            'users' => $users,
            'settingPoint' => $settingPoint,
            'trainingStatus' => $trainingStatus,
            'hrdsettingpoint' => $hrdSettingpoint,
            'all_employee' => $employee,
            'all_emp_pos' =>$hrd_emp_pos,
            'divisions' => $divisions,
        ]);
    }

    public function saveParticipant(Request $request){

//	    dd($request);

        if (isset($request->training_users)){
            $emp_id = $request->training_users;
            for ($i = 0; $i<count($emp_id); $i++){
                $training_users = new Hrd_training_users();
                $training_users->training_id = $request->training_id;
                $training_users->emp_id = $emp_id[$i];
                $training_users->active = 1;
                $training_users->created_at = date('Y-m-d H:i:s');
                $training_users->save();
            }
        }

        if (isset($request->training_users_division)){
            $id_division = $request->training_users_division;
            $emp_id = Hrd_employee::select('id')
                ->whereIn('division',$id_division)->get();
            foreach ($emp_id as $key => $value){
                $training_users = new Hrd_training_users();
                $training_users->training_id = $request->training_id;
                $training_users->emp_id = $value->id;
                $training_users->active = 1;
                $training_users->created_at = date('Y-m-d H:i:s');
                $training_users->save();
            }
        }

        return redirect()->route('training.detail',['id' => $request->training_id]);
    }

    public function saveScore(Request $request){
//	    dd($request);
        $training_users_id_arr = $request->training_users_id_arr;
        $score = $request->score;
        $emp_id = $request->employee_id;
        $train_id_arr = $request->training_id_arr;
        $training_id = '';
        for ($i = 0; $i < count($training_users_id_arr); $i++){
            $training_id = $train_id_arr[$i];
            $training_detail = Hrd_training::where('id', $train_id_arr[$i])->first();
            if ($score[$i] >= $training_detail->pass_score){
                Hrd_training_users::where('id',$training_users_id_arr[$i])
                    ->update([
                        'exam_pass' => 1,
                    ]);
                Hrd_employee::where('id', $emp_id[$i])
                    ->update([
                        'point_mandatory' => 0
                    ]);
            } else {
                Hrd_training_users::where('id',$training_users_id_arr[$i])
                    ->update([
                        'exam_pass' => 0,
                    ]);
            }
            Hrd_training_users::where('id',$training_users_id_arr[$i])
                ->update([
                    'score' => $score[$i],
                    'passed_on' => date('Y-m-d H:i:s')
                ]);
        }

        return redirect()->route('training.detail',['id' => $training_id]);

    }

    public function deleteParticipant($id){
        Hrd_training_users::find($id)->delete();
        return redirect()->back();
    }
}
