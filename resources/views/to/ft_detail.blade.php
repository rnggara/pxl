@extends('layouts.template')
@section('content')
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Financial Traveling
                        @if($to->action_time == null)
                            <span class="d-block text-muted pt-2 font-size-sm">untuk print TO harus di approve oleh GM terlebih dahulu</span>
                        @endif

                    </h3>
                </div>
                <div class="card-toolbar">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{route('to.index')}}" class="btn btn-secondary">Back</a>
                        @if($to->action_time != null)
                            <a target='_blank' href='{{ route('to.print.to', ["type" => "to", "id" => $to->id]) }}' class='btn btn-xs btn-info pull-right'>
                                <i class='fa fa-print'></i> Print TO
                            </a>
                            <a target='_blank' href='{{ route('to.print.to', ["type" => "sppd", "id" => $to->id]) }}' class='btn btn-xs btn-primary pull-right'>
                                <i class='fa fa-print'></i> Print FT
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table" border="0" >
                            <thead>
                            <tr>
                                <th colspan="4" style="text-align: center"><h4> <?= '<b>'.$emp->emp_name.'</b> ('.$emp->emp_position.')' ?> </h4></th>
                            </tr>
                            <tr>
                                <th width="200">Name</th>
                                <th class="text-right">By {{strtoupper(\Session::get('company_tag'))}}</th>
                                @php
                                    $to_duration = ($to->dest_type == "wh") ? $to->duration + 1 : $to->duration;
                                @endphp
                                <th >Day[{{$to_duration}}]</th>
                                <th class="text-right">Cost</th>
                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                <td >Meal</td>
                                <td align="right">{{($to->to_cekmeal == 1) ? number_format($to->to_meal,2):'0'}}</td>
                                <td >{{($to->to_cekmeal == 1) ? number_format($to_duration):'0'}} day(s).</td>
                                @php
                                    $subcost_meal = intval($to_duration) * intval($to->to_meal);
                                @endphp
                                <td align="right" >{{($to->to_cekmeal == 1) ? number_format($subcost_meal,2):'0'}}</td>
                            </tr>
                            <tr>
                                <td >Spending</td>
                                <td align="right">{{($to->to_cekspending == 1) ? number_format($to->to_spending,2):'0'}}</td>
                                <td >{{($to->to_cekspending == 1) ? number_format($to_duration):'0'}} day(s).</td>
                                @php
                                    $subcost_spending = intval($to_duration) * intval($to->to_spending);
                                @endphp
                                <td align="right" >{{($to->to_cekspending == 1) ? number_format($subcost_spending,2):'0'}}</td>
                            </tr>
                            <tr>
                                <td >Stay Overnight</td>
                                <td align="right">{{($to->to_cekovernight == 1) ? number_format($to->to_overnight,2):'0'}}</td>
                                <td >{{($to->to_cekovernight == 1) ? number_format($to_duration):'0'}} day(s).</td>
                                @php
                                    $subcost_overnight = intval($to_duration) * intval($to->to_overnight);
                                @endphp
                                <td align="right" >{{($to->to_cekovernight == 1) ? number_format($subcost_overnight,2):'0'}}</td>
                            </tr>
                            <tr>
                                <td >Local Transportation by
                                    @if($to->to_cektransport==1)
                                        Train
                                    @elseif($to->to_cektransport==2)
                                        Plane
                                    @elseif($to->to_cektransport==3)
                                        Bus
                                    @elseif($to->to_cektransport==4)
                                        WH PSI Cileungsi
                                    @endif
                                </td>
                                <td align="right" >{{($to->to_cektransport != null) ? number_format($to->to_transport,2) : '0'}}</td>
                                <td > - </td>
                                <td align="right" >{{($to->to_cektransport != null) ? number_format($to->to_transport,2) : '0'}}</td>
                            </tr>
                            <tr>
                                <td >Transport Travel/Boat</td>
                                <td align="right">{{number_format($to->transport,2)}}</td>
                                <td > - </td>
                                <td align="right">{{number_format($to->transport,2)}}</td>
                            </tr>
                            <tr>
                                <td >Taxi</td>
                                <td align="right">{{number_format($to->taxi,2)}}</td>
                                <td > - </td>
                                <td align="right" >{{number_format($to->taxi,2)}}</td>
                            </tr>
                            <tr>
                                <td >Car Rent</td>
                                <td align="right">{{number_format($to->rent,2)}}</td>
                                <td > - </td>
                                <td align="right" >{{number_format($to->rent,2)}}</td>
                            </tr>
                            <tr>
                                <td >Airport Tax</td>
                                <td align="right">{{number_format($to->airtax,2)}}</td>
                                <td > - </td>
                                <td align="right" >{{number_format($to->airtax,2)}}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            @php
                                $totalcost = intval($to->airtax) + intval($to->rent) + intval($to->taxi) + intval($to->transport) + intval($to->to_transport) + $subcost_overnight + $subcost_spending + $subcost_meal;
                            @endphp
                            <tr>
                                <th colspan="3" >Total Cost
                                </th><td align="right" >{{number_format($totalcost,2)}}</td>
                            </tr>
                            </tfoot>
                        </table>

                    </div>

                </div>
            </div>
        </div>
@endsection
@section('custom_script')
@endsection
