@extends('layouts.template')
@section('content')
    <form method="post" action="{{route('to.update')}}" >
        @csrf
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Edit Travel Order Detail
                        <span class="d-block text-muted pt-2 font-size-sm">Itinerary of <b>{{$emp->emp_name}}</b></span></h3>
                    <input type="hidden" name="emp_id" id="emp_id" value="{{$emp->id}}">
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="form col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Departs On</label>
                        <div class="col-md-8">
                            <input type="date" name="departs_on" id="departs_on" class="form-control" value="{{date('Y-m-d',strtotime($to->departure_dt))}}" onchange="cal()" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Returns On</label>
                        <div class="col-md-8">
                            <input type="date" name="returns_on" id="returns_on" class="form-control" onchange="cal()" value="{{date('Y-m-d',strtotime($to->return_dt))}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Duration</label>
                        <div class="col-md-8">
                            <?php 
                            $d1 = date_create($to->departure_dt);
                            $d2 = date_create($to->return_dt);
                            $days = date_diff($d2, $d1);
                            $ddays = $days->format("%a");
                             ?>
                            <input type="text" name="duration" id="duration" readonly value="{{$ddays}}" class="form-control">
                            <span><small>day(s)</small></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Project</label>
                        <div class="col-md-8">
                            <input type="text" name="" id="project_name" readonly class="form-control" value="{{$prj->prj_name}}">
                            <input type="hidden" name="project" id="project" value="{{$prj->id}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">From Airport</label>
                        <div class="col-md-8">
                            <input type="text" name="from_airport" id="from_airport" class="form-control" value="{{$to->location}}" required>
                            <span><small>(*input will be issued when air tickets)</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">To Airport</label>
                        <div class="col-md-8">
                            <input type="text" name="to_airport" id="to_airport" class="form-control" value="{{$to->tolocation}}" required>
                            <span><small>(*input will be issued when air tickets)</small></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Destination</label>
                        <div class="col-md-8">
                            <input type="text" name="destination" id="destination" class="form-control" value="{{$to->destination}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Destination Type</label>
                        <div class="col-md-8">
                            <select class="form-control" name="destination_type" id="destination_type" required>
                                <option></option>
                                <option value="vst" @if($to->dest_type == 'vst') selected @endif>Visit</option>
                                <option value="wh" @if($to->dest_type == 'wh') selected @endif>Warehouse</option>
                                <option value="fld" @if($to->dest_type == 'fld') selected @endif>Field</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Travel Type</label>
                        <div class="col-md-8">
                            <select class="form-control" name="type_travel" id="type_travel" required>
                                <option></option>
                                <option value="reg" @if($to->travel_type == 'reg') selected @endif>Reguler</option>
                                <option value="odo" @if($to->travel_type == 'odo') selected @endif>On Days Off</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right">Working Environment Condition</label>
                        <div class="col-md-8">
                            <select class="form-control" name="working_environment_condition" id="working_environment_condition">
                                <option value="" selected="selected">Normal</option>
                                <option value="EPF" @if($to->location_rate == 'EPF') selected @endif>EPF</option>
                                <option value="SWT" @if($to->location_rate == 'SWT') selected @endif>Well Test</option>
                                <option value="DGR" @if($to->location_rate == 'DGR') selected @endif>Toxic Hazard</option>
                                <option value="HNA" @if($to->location_rate == 'HNA') selected @endif>Hostile Natives</option>
                                <option value="OFF" @if($to->location_rate == 'OFF') selected @endif>Offshore</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Financial Traveling</h3>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-2 col-form-label text-right">Spending</label>
                    <div class="col-8 col-form-label">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="to_spending" id="to_spending" @if($to->to_cekspending == 1) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp;&nbsp; FT
                                @if($type == 'dom')
                                    @if($emp->dom_spending != null)(IDR {{number_format($emp->dom_spending,2)}}) @else (0.00) @endif
                                        <input type="hidden" name="to_spending_val" id="" value="{{$emp->dom_spending}}">
                                        <input type="hidden" name="to_transport_air_val" id="" value="{{$emp->dom_transport_airport}}">
                                        <input type="hidden" name="to_transport_train_val" id="" value="{{$emp->dom_transport_train}}">
                                        <input type="hidden" name="to_transport_bus_val" id="" value="{{$emp->dom_transport_bus}}">
                                        <input type="hidden" name="to_transport_cil_val" id="" value="{{$emp->dom_transport_cil}}">
                                @else
                                    @if($emp->ovs_spending != null)(IDR {{number_format($emp->ovs_spending,2)}}) @else (0.00) @endif
                                        <input type="hidden" name="to_spending_val" id="" value="{{$emp->ovs_spending}}">
                                        <input type="hidden" name="to_transport_air_val" id="" value="{{$emp->ovs_transport_airport}}">
                                        <input type="hidden" name="to_transport_train_val" id="" value="{{$emp->ovs_transport_train}}">
                                        <input type="hidden" name="to_transport_bus_val" id="" value="{{$emp->ovs_transport_bus}}">
                                        <input type="hidden" name="to_transport_cil_val" id="" value="{{$emp->ovs_transport_cil}}">
                                @endif
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-right">Overnight</label>
                    <div class="col-8 col-form-label">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="to_overnight" id="to_overnight" @if($to->to_cekovernight == 1) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp;&nbsp; FT
                                @if($type == 'dom')
                                    @if($emp->dom_overnight != null)(IDR {{number_format($emp->dom_overnight,2)}}) @else (0.00) @endif
                                    <input type="hidden" name="to_overnight_val" id="" value="{{$emp->dom_overnight}}">
                                @else
                                    @if($emp->ovs_overnight != null)(IDR {{number_format($emp->ovs_overnight,2)}}) @else (0.00) @endif
                                    <input type="hidden" name="to_overnight_val" id="" value="{{$emp->ovs_overnight}}">
                                @endif
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-right">Meal</label>
                    <div class="col-8 col-form-label">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="to_meal" id="to_meal" @if($to->to_cekmeal == 1) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp;&nbsp; FT
                                @if($type == 'dom')
                                    @if($emp->dom_meal != null)(IDR {{number_format($emp->dom_meal,2)}}) @else (IDR 0.00) @endif
                                    <input type="hidden" name="to_meal_val" id="" value="{{$emp->dom_meal}}">
                                @else
                                    @if($emp->ovs_meal != null)(IDR {{number_format($emp->ovs_meal,2)}}) @else (IDR 0.00) @endif
                                    <input type="hidden" name="to_meal_val" id="" value="{{$emp->ovs_meal}}">
                                @endif
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-right">Local Transport</label>
                    <div class="col-md-8">
                        <select class="form-control" name="to_transport" id="to_transport">
                            <option value='' checked>- Select Local Transport -</option>
                            <option value='2' @if($to->to_cektransport == '2') selected @endif>To Airport</option>
                            <option value='1' @if($to->to_cektransport == '1') selected @endif>To Train station</option>
                            <option value='3' @if($to->to_cektransport == '3') selected @endif>To Bus station</option>
                            <option value='4' @if($to->to_cektransport == '4') selected @endif>To WH PSI Cileungsi</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-right">Project Transportation</label>
                    <div class="col-8 col-form-label">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="travel_boat" id="travel_boat" @if($to->transport != 0.00) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp; FT Travel/Boat @if($prj->transport != null)(IDR {{number_format($prj->transport,2)}}) @else (IDR 0.00) @endif &nbsp;
                                <input type="hidden" name="travel_boat_val" id="" value="{{$prj->transport}}">
                            </label>
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="taxi" id="travel_boat" @if($to->taxi != 0.00) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp;FT Taxi @if($prj->taxi != null)(IDR {{number_format($prj->taxi,2)}}) @else (IDR 0.00) @endif &nbsp;
                                <input type="hidden" name="taxi_val" id="" value="{{$prj->taxi}}">
                            </label>
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="rent" id="travel_boat" @if($to->renr != 0.00) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp; FT Car Rent @if($prj->rent != null)(IDR {{number_format($prj->rent,2)}}) @else (IDR 0.00) @endif &nbsp;
                                <input type="hidden" name="rent_val" id="" value="{{$prj->rent}}">
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label text-right">Airport Tax</label>
                    <div class="col-8 col-form-label">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-success">
                                <input type="checkbox" name="airtax" id="airtax" @if($to->airtax != 0.00) checked @endif/>
                                <span></span>
                                &nbsp;&nbsp;&nbsp; FT @if($prj->airtax != null)(IDR {{number_format($prj->airtax,2)}}) @else (IDR 0.00) @endif
                                <input type="hidden" name="airtax_val" id="" value="{{$prj->airtax}}">
                            </label>
                        </div>
                        <span class="form-text text-muted"></span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="sppd_type" value="{{$type}}" id="">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Travel Purpose</h3>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-10 col-form-label">
                        <textarea name="purpose" id="purpose" class="form-control" rows="5">{{$to->purpose}}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group row">
                    <label class="col-11 col-form-label text-right"></label>
                    <div class="col-1 col-form-label">
                        <input type="submit" name="submit" class="btn btn-success mr-2" value="Update">
                        <input type="hidden" name="id_to" id="" value="{{$to->id}}">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('custom_script')
    <script>
        function GetDays(){
            var dropdt = new Date(document.getElementById("returns_on").value);
            var pickdt = new Date(document.getElementById("departs_on").value);
            var counted_day = parseInt((dropdt - pickdt) / (24 * 3600 * 1000));
            if (counted_day < 0)
                return 0;
            return counted_day;
        }

        function cal(){
            if(document.getElementById("returns_on")){
                document.getElementById("duration").value = GetDays();
            }
        }
    </script>
@endsection
