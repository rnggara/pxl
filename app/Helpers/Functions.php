<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Functions {
    public static function terbilang($num){
        $digits = array(
            0 => "nol",
            1 => "satu",
            2 => "dua",
            3 => "tiga",
            4 => "empat",
            5 => "lima",
            6 => "enam",
            7 => "tujuh",
            8 => "delapan",
            9 => "sembilan");
        $orders = array(
            0 => "",
            1 => "puluh",
            2 => "ratus",
            3 => "ribu",
            6 => "juta",
            9 => "miliar",
            12 => "triliun",
            15 => "kuadriliun");

        $is_neg = $num < 0; $num = "$num";

        //// angka di kiri desimal

        $int = ""; if (preg_match("/^[+-]?(\d+)/", $num, $m)) $int = $m[1];
        $mult = 0; $wint = "";

        // ambil ribuan/jutaan/dst
        while (preg_match('/(\d{1,3})$/', $int, $m)) {

            // ambil satuan, puluhan, dan ratusan
            $s = $m[1] % 10;
            $p = ($m[1] % 100 - $s)/10;
            $r = ($m[1] - $p*10 - $s)/100;

            // konversi ratusan
            if ($r==0) $g = "";
            elseif ($r==1) $g = "se$orders[2]";
            else $g = $digits[$r]." $orders[2]";

            // konversi puluhan dan satuan
            if ($p==0) {
                if ($s==0);
                elseif ($s==1) $g = ($g ? "$g ".$digits[$s] :
                    ($mult==0 ? $digits[1] : "se"));
                else $g = ($g ? "$g ":"") . $digits[$s];
            } elseif ($p==1) {
                if ($s==0) $g = ($g ? "$g ":"") . "se$orders[1]";
                elseif ($s==1) $g = ($g ? "$g ":"") . "sebelas";
                else $g = ($g ? "$g ":"") . $digits[$s] . " belas";
            } else {
                $g = ($g ? "$g ":"").$digits[$p]." puluh".
                    ($s > 0 ? " ".$digits[$s] : "");
            }

            // gabungkan dengan hasil sebelumnya
            $wint = ($g ? $g.($g=="se" ? "":" ").$orders[$mult]:"").
                ($wint ? " $wint":"");

            // pangkas ribuan/jutaan/dsb yang sudah dikonversi
            $int = preg_replace('/\d{1,3}$/', '', $int);
            $mult+=3;
        }
        if (!$wint) $wint = $digits[0];

        // angka di kanan desimal

        $frac = ""; if (preg_match("/\.(\d+)/", $num, $m)) $frac = $m[1];
        $wfrac = "";
        for ($i=0; $i<strlen($frac); $i++) {
            $wfrac .= ($wfrac ? " ":"").$digits[substr($frac,$i,1)];
        }
        $wfrac = 0;

        return ($is_neg ? "minus ":"").$wint.($wfrac ? "koma $wfrac":"");
    }

    public static function checkEnergy(){
        $user = User::find(Auth::id());
        if($user->attend_code <= 0){
            return false;
        }

        $user->attend_code = $user->attend_code - 1;
        $user->save();
        return $user->attend_code;
    }

    public static function checkBalance($amount){
        $currBalance = intval(Auth::user()->do_code);
        if($amount > $currBalance){
            return false;
        } else {
            $user = User::find(Auth::id());
            $user->do_code = intval($currBalance) - $amount;
            $user->save();
            return true;
        }
    }

    public static function instance()
    {
        return new Functions();
    }
}


?>
