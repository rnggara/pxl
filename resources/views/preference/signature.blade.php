<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Signature</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12">
        <form action="{{route('pref.sign.save')}}" method="post" enctype="multipart/form-data" id="form-sign">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h3>Signature PO</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th class="text-center">MIN</th>
                            <th class="text-center">MAX</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Bypass Approve</th>
                        </tr>
                        @for($i = 0; $i < 3; $i++)
                        <?php 
                        $signPO = json_decode($preferences->po_signature);
                        $min = ($i ==0) ? 0 : "";
                        $max = ($i ==0) ? 0 : "";
                        $img = "";
                        $bypass = 0;

                        if (is_object($signPO)) {
                            if (isset($signPO->min)) {
                                if (isset($signPO->min[$i])) {
                                    $min = $signPO->min[$i];
                                }
                            }
                            if (isset($signPO->max)) {
                                if (isset($signPO->max[$i])) {
                                    $max = $signPO->max[$i];
                                }
                            }

                            if (isset($signPO->bypass)) {
                                if (isset($signPO->bypass[$i])) {
                                    $bypass = $signPO->bypass[$i];
                                }
                            } 

                            if (isset($signPO->img)) {
                                if (isset($signPO->img[$i])) {
                                    $img = str_replace("public", "public_html", asset('images/signature/'.$signPO->img[$i]));
                                }
                            }
                        }
                         ?>
                        <tr>
                            <td>
                                <input type="text" name="data[po][min][{{$i}}]" class="form-control number" value="{{$min}}">
                            </td>
                            <td>
                                <input type="text" name="data[po][max][{{$i}}]" class="form-control number" value="{{$max}}">
                            </td>
                            <td valign="middle" align="center">
                                @if($img != "")
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="symbol symbol-90 mr-3">
                                                <img alt="Pic" src="{{$img}}"/>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div> 
                                @else
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="data[po][img][{{$i}}]">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <div class="checkbox-inline justify-content-center">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="checkbox"  name="data[po][bypass][{{$i}}]" {{($bypass == 1) ? "checked" : ""}}>
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endfor
                    </table>
                </div>
                <hr>
                <div class="col-12 mt-5">
                    <h3>Signature WO</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th class="text-center">MIN</th>
                            <th class="text-center">MAX</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Bypass Approve</th>
                        </tr>
                        @for($i = 0; $i < 3; $i++)
                        <?php 
                        $signWO = json_decode($preferences->wo_signature);
                        $min = ($i ==0) ? 0 : "";
                        $max = ($i ==0) ? 0 : "";
                        $img = "";
                        $bypass = 0;
                        if (is_object($signWO)) {
                            if (isset($signWO->min)) {
                                if (isset($signWO->min[$i])) {
                                    $min = $signWO->min[$i];
                                }
                            }
                            if (isset($signWO->max)) {
                                if (isset($signWO->max[$i])) {
                                    $max = $signWO->max[$i];
                                }
                            }

                            if (isset($signWO->bypass)) {
                                if (isset($signWO->bypass[$i])) {
                                    $bypass = $signWO->bypass[$i];
                                }
                            }


                            if (isset($signWO->img)) {
                                if (isset($signWO->img[$i])) {
                                    $img = str_replace("public", "public_html", asset('images/signature/'.$signWO->img[$i]));
                                }
                            }
                        }
                         ?>
                        <tr>
                            <td>
                                <input type="text" name="data[wo][min][{{$i}}]" class="form-control number" value="{{$min}}">
                            </td>
                            <td>
                                <input type="text" name="data[wo][max][{{$i}}]" class="form-control number" value="{{$max}}">
                            </td>
                            <td>
                                @if($img != "")
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="symbol symbol-90 mr-3">
                                                <img alt="Pic" src="{{$img}}"/>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div> 
                                @else
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="data[wo][img][{{$i}}]">
                                        <span class="custom-file-label">Choose File</span>
                                    </div>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <div class="checkbox-inline justify-content-center">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="checkbox"  name="data[wo][bypass][{{$i}}]" {{($bypass == 1) ? "checked" : ""}}>
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endfor
                    </table>
                </div>
                <div class="col-12 mt-5 text-right">
                    <input type="hidden" name="id_company" value="{{$preferences->id}}">
                    <button type="button" onclick="btn_one_click(this, '#form-sign')" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
