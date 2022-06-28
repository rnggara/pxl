<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\Mtg_main;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Helpers\ActivityConfig;
use App\Models\Marketing_project;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Element;
use App\Models\General_meeting_scheduler_book;
use App\Models\General_meeting_scheduler_room;
use App\Models\General_meeting_scheduler_topic;
use App\Models\General_meeting_scheduler_absensi;
use App\Models\General_meeting_scheduler_timecheck;

class GeneralMeetingScheduler extends Controller
{
    public function index(){
        $book = DB::table('rv_book')
            ->join('rv_room as room','room.id','rv_book.id_ruangan')
            ->select('rv_book.*','room.nama_ruangan as ruangan','room.id as id_ruangan')
            ->where('rv_book.company_id',\Session::get('company_id'))
            ->get();
//        dd($book);
        return view('meeting_scheduler.index',[
            'schedules' => $book,
        ]);
    }
    public function getRoom($tanggal){
        $rooms = General_meeting_scheduler_room::where('company_id',\Session::get('company_id'))
            ->get();
        $date = base64_decode($tanggal);

        return view('meeting_scheduler.room',[
            'date' => $date,
            'rooms' => $rooms,
        ]);
    }
    public function newRoom(Request $request){
        $room = new General_meeting_scheduler_room();
        $room->nama_ruangan = $request['name'];
        $room->created_by = Auth::user()->username;
        $room->created_at = date('Y-m-d H:i:s');
        $room->company_id = \Session::get('company_id');
        $room->save();

        return redirect()->route('ms.day',['tanggal' => base64_encode($request['tanggal'])]);
    }

    public function getNewBook($tanggal,$id_room){
        $tgl = base64_decode($tanggal);
        $hours = [
            '06:00 - 07:00','07:00 - 08:00','08:00 - 09:00','09:00 - 10:00','10:00 - 11:00','11:00 - 12:00','12:00 - 13:00','13:00 - 14:00','14:00 - 15:00',
            '15:00 - 16:00','16:00 - 17:00','17:00 - 18:00','18:00 - 19:00','19:00 - 20:00','20:00 - 21:00','21:00 - 22:00','22:00 - 23:00','23:00 - 24:00',
        ];
        $room = General_meeting_scheduler_room::where('id', $id_room)
            ->where('company_id', \Session::get('company_id'))
            ->first();
        $book = General_meeting_scheduler_book::where('id_ruangan', $id_room)
            ->where('tanggal',$tgl)
            ->where('company_id', \Session::get('company_id'))
            ->get();
        $topicchecker = [];
        $topic2 = General_meeting_scheduler_topic::all();
        foreach ($topic2 as $t){
            $topicchecker[$t->id_book][] = $t->id;
        }
        $topic = DB::table('rv_topic')
            ->join('rv_book as book','book.id','=','rv_topic.id_book')
            ->join('rv_room as room','room.id','=','book.id_ruangan')
            ->join('marketing_projects as prj', 'prj.id','=','rv_topic.projek')
            ->join('hrd_employee as leader','leader.id','=','rv_topic.emp_id_notulen')
            ->select('rv_topic.*','prj.prj_name as prjName','leader.emp_name as notulaName')
            ->where('book.id_ruangan',$id_room)
            ->where('rv_topic.tanggal', $tgl)
            ->where('.rv_topic.company_id', \Session::get('company_id'))
            ->get();
//        dd($topic);


        $timecheck = General_meeting_scheduler_timecheck::where('id_ruangan', $id_room)
            ->where('tanggal', $tgl)
            ->where('company_id', \Session::get('company_id'))
            ->get();

        return view('meeting_scheduler.book',[
            'date' => $tgl,
            'room' => $room,
            'books' => $book,
            'topics' => $topic,
            'hours' => $hours,
            'timecheck' => $timecheck,
            'topicchecker' => $topicchecker
        ]);
    }

    public function addReservation(Request $request){
        $ambil_id       = $request['id_room'];
        $jamMasuk       = $request['jam_masuk'];
        $jamKeluar      = $request['jam_keluar'];
        $ambil_tanggal  = $request['tgl'];

        $timecheck = General_meeting_scheduler_timecheck::where('id_ruangan', $ambil_id)
            ->where('jam',$jamMasuk)
            ->where('tanggal', $ambil_tanggal)
            ->where('company_id', Session::get('company_id'))
            ->get();

        if ($timecheck->count() > 0){
            return redirect()->back()->with('message', 'Waktu pemesanan yang anda minta tidak tersedia');
        } else {
            $hitungJam     = round((strtotime("$ambil_tanggal $jamKeluar") - strtotime("$ambil_tanggal $jamMasuk"))/3600, 1);
            $book = new General_meeting_scheduler_book();
            $book->id_ruangan = $ambil_id;
            $book->tanggal = $ambil_tanggal;
            $book->jam_masuk = $jamMasuk;
            $book->jam_keluar = $jamKeluar;
            $book->company_id = Session::get('company_id');
            $book->save();
            $latest_book   = $book->id;
            for($i=0;$i<$hitungJam;$i++) {
                $time            = strtotime($jamMasuk);
                $waktu_pemesanan = date("H:i", strtotime('+'.$i.'hours', $time));

                $t_check = new General_meeting_scheduler_timecheck();
                $t_check->id_ruangan = $ambil_id;
                $t_check->id_book = $latest_book;
                $t_check->tanggal = $ambil_tanggal;
                $t_check->jam = $waktu_pemesanan;
                $t_check->save();
            }

            return redirect()->route('ms.book',[
                'tanggal' => base64_encode($ambil_tanggal),
                'id_room' => $ambil_id,
            ]);
        }
    }

    public function getEvent($tanggal,$id_room,$id_book){
        $tgl = base64_decode($tanggal);

        $employee = Hrd_employee::where('company_id', \Session::get('company_id'))
            ->get();
        $projects = Marketing_project::where('company_id', \Session::get('company_id'))
            ->get();

        return view('meeting_scheduler.event',[
            'date' => $tgl,
            'employees'=>$employee,
            'projects' => $projects,
            'id_book' => $id_book,
            'room' => $id_room,
        ]);
    }

    public function storeEvent(Request $request){
        ActivityConfig::store_point('meeting_scheduler', 'create');
        if ($request['leader'] == 'new'){
            $leader = $request['meeting_leader'];
            $emp_id_leader = 0;
        } else {
            $leader = $request['leader'];
            $emp_id_leader = $leader;
        }

        if ($request['notulen'] == 'new'){
            $notulen = $request['notula'];
            $emp_id_notulen = 0;
        } else {
            $notulen = $request['notulen'];
            $emp_id_notulen = $notulen;
        }

        $search = ['{"value":','}'];
        $attendees = str_replace($search,'',$request['attendees']);
        $attendArr = json_decode($attendees);

        $topic = new General_meeting_scheduler_topic();
        $topic->id_book = $request['book_id'];
        $topic->topic_meeting = $request['topic'];
        $topic->projek = $request['project'];
        $topic->emp_id_pemimpin = $emp_id_leader;
        $topic->emp_id_notulen = $emp_id_notulen;
        $topic->attendees = $attendees;
        $topic->tanggal = $request['tgl'];
        $topic->company_id = \Session::get('company_id');
        $topic->save();

        $book = General_meeting_scheduler_book::find($request->book_id);
        $room = General_meeting_scheduler_room::find($book->id_ruangan);

        $mtg_main = new Mtg_main();
        $mtg_main->topic = $topic->topic_meeting;
        $mtg_main->location = $room->nama_ruangan;
        $mtg_main->created_by = Auth::user()->username;
        $mtg_main->date_main = date("Y-m-d H:i:s", strtotime($book->tanggal . ' ' . $book->jam_masuk));
        $mtg_main->date_end = date("Y-m-d H:i:s", strtotime($book->tanggal . ' ' . $book->jam_keluar));
        $mtg_main->progress = 'created';
        $mtg_main->company_id = $book->company_id;
        $mtg_main->save();

        $id_topic = $topic->id_topic;
        $notula_ = Hrd_employee::where('id', $emp_id_notulen)->get();
        $leader_ = Hrd_employee::where('id', $emp_id_leader)->get();

        if (count($leader_) > 0){
            $absenLeader = new General_meeting_scheduler_absensi();
            $absenLeader->id_topic = $id_topic;
            $absenLeader->nama = $leader_[0]->emp_name;
            $absenLeader->divisi = $leader_[0]->emp_position;
            $absenLeader->emp_id = $leader_[0]->id;
            $absenLeader->company_id = \Session::get('company_id');
            $absenLeader->created_by = Auth::user()->username;
            $absenLeader->save();
        } else {
            $absenLeader = new General_meeting_scheduler_absensi();
            $absenLeader->id_topic = $id_topic;
            $absenLeader->nama = $leader;
            $absenLeader->divisi = "Other";
            $absenLeader->emp_id = 0;
            $absenLeader->company_id = \Session::get('company_id');
            $absenLeader->created_by = Auth::user()->username;
            $absenLeader->save();
        }

        if (count($notula_) > 0){
            $absenNotula = new General_meeting_scheduler_absensi();
            $absenNotula->id_topic = $id_topic;
            $absenNotula->nama = $notula_[0]->emp_name;
            $absenNotula->divisi = $notula_[0]->emp_position;
            $absenNotula->emp_id = $notula_[0]->id;
            $absenNotula->company_id = \Session::get('company_id');
            $absenNotula->created_by = Auth::user()->username;
            $absenNotula->save();
        } else {
            $absenNotula = new General_meeting_scheduler_absensi();
            $absenNotula->id_topic = $id_topic;
            $absenNotula->nama = $notulen;
            $absenNotula->divisi = "Other";
            $absenNotula->emp_id = 0;
            $absenNotula->company_id = \Session::get('company_id');
            $absenNotula->created_by = Auth::user()->username;
            $absenNotula->save();
        }



        for($i = 0; $i< count($attendArr);$i++){
            $absAttend = new General_meeting_scheduler_absensi();
            $absAttend->id_topic = $id_topic;
            $absAttend->nama = $attendArr[$i];
            $absAttend->divisi = '';
            $absAttend->emp_id = 0;
            $absAttend->company_id = \Session::get('company_id');
            $absAttend->created_by = Auth::user()->username;
            $absAttend->save();
        }

        return redirect()->route('ms.book',['tanggal' => base64_encode($request['tgl']),'id_room'=>$request['id_room']]);
    }

    public function getAbsensi($tanggal,$id_topic){
        $absensi = General_meeting_scheduler_absensi::join('rv_topic as topic', 'topic.id_topic','=','rv_absensi.id_topic')
            ->join('rv_book as book','book.id','=','topic.id_book')
            ->join('rv_room as room', 'room.id','=','book.id_ruangan')
            ->select('rv_absensi.*','topic.tanggal as meetingDate','topic.topic_meeting as meetingTopic','book.jam_masuk as meetingIn','book.jam_keluar as meetingOut','room.nama_ruangan as location')
            ->where('rv_absensi.id_topic',$id_topic)
            ->where('rv_absensi.company_id', \Session::get('company_id'))
            ->get();
//        dd($absensi);
        $tgl = base64_decode($tanggal);
        return view('meeting_scheduler.absen',[
            'date' => $tgl,
            'absensi' => $absensi,
        ]);
    }

    function updateStatus(Request $request){
        $absen = General_meeting_scheduler_absensi::find($request->id_absen);
        if($request->absen == 1){
            $absen->kehadiran = "hadir";
        } else {
            $absen->kehadiran = "tidak hadir";
        }

        $absen->save();

        return redirect()->back();
    }
}
