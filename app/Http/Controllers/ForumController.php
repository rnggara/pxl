<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Frm_forum;
use App\Models\Frm_topik;
use App\Models\Frm_comment;
use App\Models\File_Management;
use App\Helpers\FileManagement;

class ForumController extends Controller
{
    public function index(){
        $forum = Frm_forum::where('company_id', \Session::get('company_id'))->get();
        $topic = Frm_topik::where('company_id', \Session::get('company_id'))->get();
        $comment = Frm_comment::where('company_id', \Session::get('company_id'))->get();

        return view('forum.index',[
            'forums' => $forum,
            'topics' => $topic,
            'comments' => $comment,
        ]);

    }

    public function deleteTopic($id,$id_forum){
        Frm_topik::where('id_topik', $id)->delete();
        return redirect()->route('forum.topic',['id' =>$id_forum]);
    }
    public function storeForum(Request $request){
        $forum = new Frm_forum();
        $forum->nama_forum = $request['forum_name'];
        $forum->date_forum = date('Y-m-d H:i:s');
        $forum->emp_id = Auth::user()->id;
        $forum->created_by = Auth::user()->username;
        $forum->created_at = date('Y-m-d H:i:s');
        $forum->baca = 't';
        $forum->company_id = \Session::get('company_id');
        $forum->save();

        return redirect()->route('forum.index');
    }

    public function getTopic($id){
        $topicname = Frm_forum::where('id', $id)->first();
        return view('forum.topic',[
            'forum_name' => $topicname,
            'id_forum' => $id,
        ]);
    }

    public function storePost(Request $request){
//        dd($request);
        $post = new Frm_comment();
        $post->id_topik = $request['id_topik'];
        $post->date_comment = date('Y-m-d H:i:s');
        $post->isi_comment = $request['isi_comment'];
        $post->emp_id = Auth::user()->id;
        $post->baca = 't';
        $post->created_at = date('Y-m-d H:i:s');
        $post->created_by = Auth::user()->username;
        $post->company_id = \Session::get('company_id');
        if ($request->hasFile('image1')){
            $file = $request->file('image1');
            $newFile = date('Y_m_d_H_i_s')."image1.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/forum_attachment");
            if ($upload == 1){
                $post->image1 = $newFile;
            }
        }
        if ($request->hasFile('image2')){
            $file = $request->file('image2');
            $newFile = date('Y_m_d_H_i_s')."image2.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/forum_attachment");
            if ($upload == 1){
                $post->image2 = $newFile;
            }
        }
        if ($request->hasFile('video')){
            $file = $request->file('video');
            $newFile = date('Y_m_d_H_i_s')."video.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/forum_attachment");
            if ($upload == 1){
                $post->video = $newFile;
            }
        }
        $post->save();
        return redirect()->route('forum.topic.post',['id' => $request['id_topik']]);
    }
    public function storeTopic(Request $request){
//        dd($request);
        $topic = new Frm_topik();
        $topic->id_forum = $request['id_forum'];
        $topic->emp_id = Auth::user()->id;
        $topic->nama_topik = $request['title'];
        $topic->desc_topik = $request['content'];
        $topic->date_topik = date('Y-m-d H:i:s');
        $topic->created_at = date('Y-m-d H:i:s');
        $topic->baca = 't';
        $topic->created_by = Auth::user()->username;
        $topic->company_id = \Session::get('company_id');
        $topic->save();
        return redirect()->route('forum.topic',['id' =>$request['id_forum']]);
    }

    public function getComments($id){
        $comments = Frm_comment::where('id_topik', $id)->get();
        $topic = Frm_topik::where('id_topik', $id)->first();
        return view('forum.post',[
            'comments' => $comments,
            'topic' => $topic,
        ]);
    }

    public function getTopicAjax ($id){
        $topics = Frm_topik::where('id_forum', $id)->get();
        $posts = Frm_comment::all();
        $row = [];
        $post =0;
        foreach ($topics as $key => $val){
            foreach ($posts as $key2 => $val2){
                if ($val2->id_topik == $val->id_topik){
                    $post +=1;
                }
            }
            $topic['no'] = ($key+1);
            $topic['title'] = "<a href='".route('forum.topic.post', $val->id_topik)."'>".$val->nama_topik."</a>";
            $topic['statistik'] = "<b class='text-black-50'>Posts: ".$post."</b>";
            $topic['last_post'] = "by ".$val->created_by.'<br>'.$val->date_topik;
            $topic['desc_topik'] = $val->desc_topik;
            $topic['action'] = "<a href='".route('forum.topic.delete', ['id' => $val->id_topik, 'id_forum' => $val->id_forum])."' class='btn btn-danger btn-icon btn-xs' title='Delete' onclick='return confirm(\"Are you sure you want to delete?\"); '><i class='fa fa-trash'></i></a>";
            $row[] = $topic;
        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);
    }

    public function deletePosts($id,$id_topik){
        Frm_comment::where('id_comment', $id)->delete();
        return redirect()->route('forum.topic.post',['id' => $id_topik]);
    }
}
