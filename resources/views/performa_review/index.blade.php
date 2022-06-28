@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Performa Review
            </div>
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">NIK</th>
                    <th class="text-center">Position</th>
                    <th class="text-center">Last Review</th>
                    <th class="text-center">Review</th>
                    <th class="text-center">Score</th>
                    <th class="text-center">Approval</th>
                </tr>
                </thead>
                <tbody>
                @actionStart('performa', 'read')
                @foreach($emp as $key => $item)
                    <tr>
                        <td align="center">{{$key + 1}}</td>
                        <td>{{$item->emp_name}}</td>
                        <td align="center">{{$item->emp_id}}</td>
                        <td align="center">{{$item->empType}}</td>
                        <td align="center">
                            @if(isset($performa_ref[$item->id]))
                                {{date('H:i:s d F Y', strtotime($performa_ref[$item->id]->review_date))}}
                            @else
                                N/A
                            @endif
                        </td>
                        <td align="center">
                            @if(strtotime(date('Y-m')) >= strtotime(date('Y')."-".Session::get('company_performa_start')) && strtotime(date('Y-m')) <= strtotime(date('Y')."-".Session::get('company_performa_end')))
                                @if(isset($performa_ref[$item->id]) && date('Y', strtotime($performa_ref[$item->id]->review_date)) == date('Y'))
                                    reviewed by {{$performa_ref[$item->id]->superior_id}}
                                @else
                                    <button class="btn btn-warning btn-xs" onclick="button_review('{{$item->id}}')" type="button">Write a review</button>
                                @endif
                            @else
                                - {{Session::get('company_performa_end')}}
                            @endif
                        </td>
                        <td align="center">
                            <?php
                            /** @var TYPE_NAME $item */
                            if (isset($performa_ref[$item->id]) && $performa_ref[$item->id]->approved_by != null){
                                $points = json_decode($performa_ref[$item->id]->entry_point);
                                $sumpoint = 0;
                                $count = 0;
                                foreach ($points as $po){
                                    $sumpoint += $po;
                                    $count++;
                                }
                                $avg = round($sumpoint / $count);
                                switch ($avg){
                                    case "1":
                                        echo "Unacceptable";
                                        break;
                                    case "2":
                                        echo "Need Improvement";
                                        break;
                                    case "3":
                                        echo "Satisfactory";
                                        break;
                                    case "4":
                                        echo "More than satisfactory";
                                        break;
                                    case "5":
                                        echo "Exceptional";
                                        break;
                                }
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </td>
                        <td align="center">
                            @if(!isset($performa_ref[$item->id]) || $performa_ref[$item->id]->approved_date == null)
                                @if(isset($performa_ref[$item->id]))
                                    @if(\Illuminate\Support\Facades\Auth::user()->id_rms_roles_divisions == 1)
                                        <button class="btn btn-light-success btn-xs" onclick="button_approve('{{$item->id}}')" type="button">Approve</button>
                                    @endif
                                @else
                                    @if(\Illuminate\Support\Facades\Auth::user()->id_rms_roles_divisions == 1)
                                        <button class="btn btn-light-success btn-xs" onclick="button_approve('{{$item->id}}')" type="button" disabled>Approve</button>
                                    @endif
                                @endif

                            @else
                                <span class="btn label label-inline label-success" onclick="button_view('{{$item->id}}')">Approved</span> at {{$performa_ref[$item->id]->approved_date}} <br> by {{$performa_ref[$item->id]->approved_by}}
                            @endif
                        </td>
                    </tr>
                @endforeach
                @actionEnd
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addReview" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('general.pr.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <table cellspacing=0 class='table table_hover' >
                                <tr>
                                    <td style="min-width:50px"><h2>SUBJECT</h2></td>
                                    <td style="min-width:50px" colspan='5'><h2>PERFORMANCE LEVEL</h2> <br />*1 = kurang baik, 5 = paling baik</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>JOB SKILLS AND KNOWLEDGE (POSITION EXPERTISE)</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memiliki keterampilan praktis, teknis dan profesional yang diperlukan untuk pekerjaan pada posisinya.</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[1]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[1]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[1]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[1]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[1]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memiliki pengetahuan dan pengalaman yang cukup dari semua aspek operasi bisnis untuk membuat keputusan berdasarkan cakupan tanggung jawab.</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[2]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[2]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[2]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[2]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[2]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Tetap up to date dengan praktik terbaik dan perkembangan baru</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[3]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[3]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[3]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[3]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[3]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memastikan praktik dan prosedur keselamatan dan keamanan diikuti</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[4]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[4]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[4]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[4]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[4]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[1]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[1]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PLANNING AND ORGANIZATION</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Tetapkan tujuan yang tepat dan terukur yang realistis, menantang, dan kompatibel dengan sasaran perusahaan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[5]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[5]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[5]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[5]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[5]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengatur waktu secara efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[6]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[6]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[6]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[6]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[6]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengantisipasi masalah dan merencanakan yang sesuai dengan masalahnya</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[7]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[7]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[7]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[7]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[7]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Delegasikan tanggung jawab dengan tepat</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[8]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[8]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[8]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[8]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[8]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mampu memprioritaskan pekerjaan. Menetapkan tenggat waktu yang realistis untuk diri sendiri dan orang lain dan memastikan tenggat waktu terpenuhi</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[9]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[9]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[9]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[9]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[9]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[2]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[2]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>BUDGET (FUND) ADMINISTRATION</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Kemampuan untuk mengembangkan dan mengelola anggaran</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[10]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[10]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[10]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[10]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[10]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memanfaatkan perkiraan dalam perencanaan, pengeluaran dan pengendalian biaya</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[11]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[11]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[11]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[11]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[11]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengidentifikasi dan menerapkan langkah-langkah pengurangan biaya tanpa mengurangi tingkat layanan, operasional atau kualitas</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[12]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[12]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[12]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[12]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[12]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengamati tanda-tanda awal dari kondisi yang berubah; merespons dengan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[13]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[13]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[13]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[13]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[13]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[3]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[3]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PROBLEM SOLVING</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengamati tanda-tanda awal kondisi yang berubah</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[14]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[14]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[14]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[14]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[14]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menawarkan solusi kreatif dan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[15]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[15]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[15]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[15]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[15]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menggunakan semua sumber daya yang tersedia dan tepat, termasuk karyawan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[16]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[16]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[16]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[16]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[16]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengikuti untuk memastikan bahwa tindakan yang tepat telah diambil</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[17]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[17]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[17]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[17]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[17]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[4]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[4]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>COMMUNICATIONS</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menyajikan ide dan informasi dengan cara yang ringkas dan terorganisasi dengan baik</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[18]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[18]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[18]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[18]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[18]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mendengarkan; berkonsentrasi pada informasi yang disajikan; mengambil tindakan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[19]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[19]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[19]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[19]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[19]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menginformasikan atasan, rekan kerja, dan karyawan tepat waktu</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[20]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[20]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[20]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[20]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[20]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Adakan meetings yang terorganisasi dengan baik dan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[21]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[21]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[21]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[21]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[21]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Berpartisipasi aktif dalam meetings; membuat kontribusi yang berarti</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[22]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[22]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[22]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[22]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[22]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[5]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[5]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>TEAMWORK</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memotivasi orang lain; menciptakan antusiasme untuk upaya tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[23]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[23]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[23]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[23]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[23]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menetapkan model untuk kerja tim yang mendorong tujuan bersama</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[24]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[24]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[24]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[24]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[24]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Seseorang yang merupakan pembangun tim yang efektif untuk mempromosikan hubungan kerja yang kuat</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[25]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[25]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[25]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[25]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[25]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Secara berkala merencanakan kegiatan untuk mengembangkan kerja tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[26]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[26]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[26]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[26]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[26]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menetapkan contoh positif untuk semua orang didalam tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[27]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[27]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[27]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[27]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[27]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[6]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[6]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>TRAINING AND DEVELOPMENT</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengikuti dan mengintegrasikan pelatihan yang diterima</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[28]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[28]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[28]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[28]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[28]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengemban tanggung jawab untuk pelatihan dan pengembangannya sendiri</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[29]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[29]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[29]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[29]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[29]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PERSONALITY AND ATTITUDE</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Kerapian dan keseuaian dresscode dalam bekerja</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[30]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[30]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[30]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[30]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[30]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Disiplin</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[31]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[31]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[31]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[31]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[31]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Respek dan sikap terhadap karyawan lain</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[32]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[32]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[32]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[32]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[32]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Respek dan sikap terhadap atasan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[33]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[33]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[33]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[33]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer[33]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength[7]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal[7]'></textarea></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_emp" id="id-emp">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        @actionStart('performa', 'create')
                        <button type="submit" id="btn-save-leads" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Save</button>
                        @actionEnd
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalApprove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addProject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">PERFORMA REVIEW RESULT OF <span id="appr-emp-name"></span> <span id="pref-rev"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form method="POST" action="{{URL::route('general.pr.approve')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <table cellspacing=0 class='table table_hover' >
                                <tr>
                                    <td style="min-width:50px"><h2>SUBJECT</h2></td>
                                    <td style="min-width:50px" colspan='5'><h2>PERFORMANCE LEVEL</h2> <br />*1 = kurang baik, 5 = paling baik</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>JOB SKILLS AND KNOWLEDGE (POSITION EXPERTISE)</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memiliki keterampilan praktis, teknis dan profesional yang diperlukan untuk pekerjaan pada posisinya.</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[1]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[1]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[1]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[1]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[1]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memiliki pengetahuan dan pengalaman yang cukup dari semua aspek operasi bisnis untuk membuat keputusan berdasarkan cakupan tanggung jawab.</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[2]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[2]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[2]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[2]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[2]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Tetap up to date dengan praktik terbaik dan perkembangan baru</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[3]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[3]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[3]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[3]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[3]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memastikan praktik dan prosedur keselamatan dan keamanan diikuti</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[4]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[4]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[4]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[4]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[4]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[1]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[1]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PLANNING AND ORGANIZATION</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Tetapkan tujuan yang tepat dan terukur yang realistis, menantang, dan kompatibel dengan sasaran perusahaan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[5]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[5]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[5]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[5]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[5]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengatur waktu secara efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[6]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[6]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[6]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[6]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[6]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengantisipasi masalah dan merencanakan yang sesuai dengan masalahnya</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[7]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[7]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[7]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[7]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[7]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Delegasikan tanggung jawab dengan tepat</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[8]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[8]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[8]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[8]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[8]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mampu memprioritaskan pekerjaan. Menetapkan tenggat waktu yang realistis untuk diri sendiri dan orang lain dan memastikan tenggat waktu terpenuhi</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[9]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[9]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[9]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[9]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[9]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[2]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[2]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>BUDGET (FUND) ADMINISTRATION</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Kemampuan untuk mengembangkan dan mengelola anggaran</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[10]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[10]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[10]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[10]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[10]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memanfaatkan perkiraan dalam perencanaan, pengeluaran dan pengendalian biaya</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[11]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[11]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[11]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[11]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[11]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengidentifikasi dan menerapkan langkah-langkah pengurangan biaya tanpa mengurangi tingkat layanan, operasional atau kualitas</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[12]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[12]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[12]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[12]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[12]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengamati tanda-tanda awal dari kondisi yang berubah; merespons dengan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[13]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[13]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[13]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[13]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[13]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[3]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[3]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PROBLEM SOLVING</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengamati tanda-tanda awal kondisi yang berubah</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[14]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[14]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[14]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[14]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[14]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menawarkan solusi kreatif dan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[15]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[15]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[15]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[15]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[15]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menggunakan semua sumber daya yang tersedia dan tepat, termasuk karyawan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[16]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[16]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[16]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[16]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[16]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengikuti untuk memastikan bahwa tindakan yang tepat telah diambil</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[17]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[17]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[17]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[17]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[17]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[4]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[4]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>COMMUNICATIONS</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menyajikan ide dan informasi dengan cara yang ringkas dan terorganisasi dengan baik</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[18]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[18]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[18]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[18]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[18]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mendengarkan; berkonsentrasi pada informasi yang disajikan; mengambil tindakan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[19]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[19]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[19]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[19]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[19]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menginformasikan atasan, rekan kerja, dan karyawan tepat waktu</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[20]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[20]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[20]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[20]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[20]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Adakan meetings yang terorganisasi dengan baik dan efektif</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[21]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[21]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[21]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[21]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[21]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Berpartisipasi aktif dalam meetings; membuat kontribusi yang berarti</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[22]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[22]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[22]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[22]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[22]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[5]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[5]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>TEAMWORK</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Memotivasi orang lain; menciptakan antusiasme untuk upaya tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[23]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[23]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[23]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[23]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[23]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menetapkan model untuk kerja tim yang mendorong tujuan bersama</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[24]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[24]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[24]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[24]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[24]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Seseorang yang merupakan pembangun tim yang efektif untuk mempromosikan hubungan kerja yang kuat</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[25]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[25]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[25]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[25]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[25]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Secara berkala merencanakan kegiatan untuk mengembangkan kerja tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[26]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[26]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[26]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[26]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[26]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Menetapkan contoh positif untuk semua orang didalam tim</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[27]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[27]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[27]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[27]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[27]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[6]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[6]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>TRAINING AND DEVELOPMENT</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengikuti dan mengintegrasikan pelatihan yang diterima</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[28]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[28]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[28]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[28]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[28]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Mengemban tanggung jawab untuk pelatihan dan pengembangannya sendiri</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[29]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[29]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[29]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[29]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[29]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px"><h3>PERSONALITY AND ATTITUDE</h3></td>
                                    <td style="min-width:50px" align="center">1</td>
                                    <td style="min-width:50px" align="center">2</td>
                                    <td style="min-width:50px" align="center">3</td>
                                    <td style="min-width:50px" align="center">4</td>
                                    <td style="min-width:50px" align="center">5</td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Kerapian dan keseuaian dresscode dalam bekerja</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[30]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[30]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[30]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[30]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[30]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Disiplin</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[31]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[31]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[31]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[31]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[31]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Respek dan sikap terhadap karyawan lain</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[32]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[32]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[32]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[32]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[32]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">Respek dan sikap terhadap atasan</td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[33]' value='1'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[33]' value='2'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[33]' value='3'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[33]' value='4'></td>
                                    <td style="min-width:50px" align="center"><input type='radio' name='answer_edit[33]' value='5'></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">STRENGTHS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='strength_edit[7]'></textarea></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px">GOALS</td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                    <td style="min-width:50px"></td>
                                </tr>
                                <tr>
                                    <td style="min-width:50px" colspan='6'><textarea cols='100' name='goal_edit[7]'></textarea></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_emp" id="edit-id-emp">
                        <input type="hidden" name="id_per" id="edit-id-per">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-save-appr" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="json_per" value="{{json_encode($performa_ref)}}">
    <input type="hidden" id="json_emp" value="{{json_encode($emp_id)}}">
@endsection

@section('custom_script')
    <script>
        function button_view(x) {
            button_approve(x)
            $("#btn-save-appr").hide()
            $("#modalApprove input").attr('disabled', true)
            $("#modalApprove textarea").attr('disabled', true)
        }

        function button_approve(x){
            $("#btn-save-appr").show()
            $("#modalApprove input").attr('disabled', false)
            $("#modalApprove textarea").attr('disabled', false)
            $("#modalApprove").modal('show')
            var json_per = $("#json_per").val()
            var per = JSON.parse(json_per)
            var data = per[x]
            console.log(data)
            var emp = JSON.parse($("#json_emp").val())
            console.log(emp)
            $("#appr-emp-name").text(emp[data.emp_id].emp_name+" :")
            var answer = JSON.parse(data.entry_point)
            var goal = JSON.parse(data.entry_goal)
            var strength = JSON.parse(data.entry_strength)
            var sumans = 0;
            var countans = 0;
            for (item in answer){
                sumans += parseInt(answer[item])
                countans++
            }

            for (const goalKey in goal) {
                console.log(goal[goalKey])
                $("textarea[name='goal_edit["+goalKey+"]']").val(goal[goalKey])
            }

            for (const strKey in strength) {
                $("textarea[name='strength_edit["+strKey+"]']").val(strength[strKey])
            }

            var final_score = Math.round(parseInt(sumans) / parseInt(countans))
            console.log()
            if (final_score == 1){
                $("#pref-rev").text(data.final_score+"% - Unacceptable / Tidak dapat diterima")
            } else if (final_score == 2){
                $("#pref-rev").text(data.final_score+"% - Need Improvement / Perlu perbaikan")
            } else if (final_score == 3){
                $("#pref-rev").text(data.final_score+"% - Satisfactory / Memuaskan")
            } else if (final_score == 4){
                $("#pref-rev").text(data.final_score+"% - More than satisfactory / Lebih dari memuaskan")
            } else if (final_score == 5){
                $("#pref-rev").text(data.final_score+"% - Exceptional / Luar biasa")
            }

            for (let i = 1; i <= countans; i++) {
                // console.log(answer[i])
                var input_answer = $("input[name='answer_edit["+i+"]']").toArray()
                // console.log(input_answer)
                for (let j = 0; j < input_answer.length; j++) {
                    if (input_answer[j].value == answer[i]){
                        input_answer[j].checked = true
                    }
                }
            }

            $("#edit-id-emp").val(data.emp_id)
            $("#edit-id-per").val(data.id)
        }

        function button_delete(x){
            Swal.fire({
                title: "Delete ",
                text: "Delete this leave request?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then(function(result){
                if(result.value){
                    $.ajax({
                        url: "{{route('leave.delete')}}/"+ x,
                        type: "get",
                        dataType: "json",
                        cache: false,
                        success: function(response){
                            if (response.error == 0){
                                location.reload()
                            } else {
                                Swal.fire('Error Occured', 'Please contact your administrator', 'error')
                            }
                        }
                    })
                }
            })
        }

        function button_review(x){
            $("#addReview").modal('show')
            $("#id-emp").val(x)
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

        })
    </script>
@endsection
