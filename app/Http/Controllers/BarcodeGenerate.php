<?php

namespace App\Http\Controllers;

use App\Models\Asset_item;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Mpdf\Barcode;

// use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarcodeGenerate extends Controller
{
    function generate($id, Request $request){
        $item = Asset_item::find($id);

        $br = new DNS1D();

        $qr = '<img src="data:image/png;base64,' . $br->getBarcodePNG($item->item_code, 'C128', 3,50, [0,0,0], true) . '" alt="barcode"   />';

        if(isset($request->act)){
            return view("items.print_barcode", compact("item", "qr"));
        }

        return view("items.barcode", compact("item", "qr"));
    }
}
