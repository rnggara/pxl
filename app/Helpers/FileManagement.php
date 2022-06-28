<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class FileManagement
{
    public static function save_file_management($hash, $file, $file_name, $target_dir){
        $fM = new \App\Models\File_Management();
        $fM->hash_code = $hash;
        $fM->file_name = $target_dir."/".$file_name;
        $fM->created_by = Auth::user()->username;
        $dir = str_replace("\\", "/", public_path($target_dir));
        if ($fM->save()){
            if ($file->move($dir, $file_name)){
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }

    public static function get_child($id){
        $comp = \App\Models\ConfigCompany::where('id_parent', $id)->get();
        $childs = array();
        foreach ($comp as $item){
            $childs[] = $item->id;
        }

        return $childs;
    }
}
