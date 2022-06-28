<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use App\Models\Ref_source_pressure_vessel;
use App\Models\Ref_source_lining_inorganic;
use App\Models\Ref_source_lining_inorganic_condition;
use MathPHP\Probability\Distribution\Table\StandardNormal;

class TePressureVessel extends Controller
{
    function index(){
        $columns = ['cl_number', 'tag', 'name_equipment', 'model_rbi_type', 'fluid_content', 'stream_no', 'vol_inv_num', 'id_system', 'pnid', 'design_code', 'risk_target'];
        $data = Ref_source_pressure_vessel::all();
        return view('pv.index', compact('columns', 'data'));
    }

    function chart($code){
        $items = Ref_source_pressure_vessel::where('tag', $code)->get();
        return view('pv.chart', compact('items'));
    }

    function chart_data($code){
        $items = Ref_source_pressure_vessel::where('tag', $code)->get();
        $col = [];
        if(!empty($items)){
            foreach($items as $value){
                $row = [];
                $row['date'] = $value->thinning_df_rbi_date;
                $row["d.thin.f"] = $value->thinning_df_d_thin_f;
                $row["d.elin.f"] = $value->lining_df_age_d_elin_f;
                // $row['row'] = [

                // ];
                $col[] = $row;
            }
        }

        $results = [
            "data" => $col
        ];

        return json_encode($results);
    }

    function add_record(){
        return view('pv.input');
    }

    function view($id){
        $field = [];

        $row['Properties'] = ['cl_number', 'tag', 'name_equipment', 'model_rbi_type', 'fluid_content', 'stream_no', 'vol_inv_num', 'id_system', 'pnid', 'design_code', 'risk_target'];
        $row['RBI_General_Properties'] = ['material_code', 'reliasoft', 'lining', 'insulation', 'cloride_and_water', 'fluid_type', 'operating_temperature', 'operating_pressure', 'ph', 'date_of_component_installation', 'plan_date'];
        $row['Thinning_Damage_Factor'] = ['damage_factor_type', 'thinning_type', 'date_of_last_wall_thickness_inspection', 'number_of_inspections', 'last_inspection_thickness', 'corrosion_rate_of_base_metal', 'minimum_wall_thickness_per_code', 'corrosion_allowance', 'cladding_present', 'furnished_thickness_at_last_inspection', 'corrosion_rate_of_cladding', 'online_monitoring_type', 'thinning_mechanism_type_update'];
        $row['Component_Lining_Damage_Factor'] = ['date_of_last_inspection', 'type_of_lining', 'type_of_inorganic_lining', 'lining_installation_date', 'quality_lining', 'online_monitoring'];
        $row['SCC_Damage_Fator-Sulfide_Stress_Cracking'] = ['last_inspection_date', 'no_of_insection', 'past_inspection', 'internal_cracking_present', 'internal_cracking', 'ph_cracking', 'h2s_content', 'h2s_ppm', 'cyanide_present', 'cyanide_present', 'pwht', 'est_brinell_hardness'];
        $row['Consequence'] = ['fluide_representative', 'storage_phase', 'atm_temperature', 'componennt_dimention', 'detection_class', 'isolation_class', 'component_mass', 'inventory_mass', 'flammability_mitigation_system', 'toxicity_h2s', 'toxicity_cl2'];
        $cal['THINNING_DF_CALCULATION'] = ['thinning_df_rbi_date', 'thinning_df_install_date_general', 'rbi_date_general', 'last_inspection_date_general', 'thinning_df_age_from_install', 'thinning_df_agetk_time_in_service', 'thinning_df_agerc', 'art_cladding', 'art_w_clad_agetk_less_agerc', 'art_w_clad_agetk_more_agerc', 'art_selected', 'thinning_df_yeild_str', 'thinning_df_tensile_str', 'thinning_df_s', 'thinning_df_tc', 'thinning_df_confidence_level', 'thinning_df_e', 'thinning_df_fsthin', 'thinning_df_sr_thin1', 'thinning_df_sr_thin2', 'thinning_df_nthin_a', 'thinning_df_nthin_b', 'thinning_df_nthin_c', 'thinning_df_nthin_d', 'thinning_df_prthin_p1', 'thinning_df_prthin_p2', 'thinning_df_prthin_p3', 'thinning_df_lthin_1', 'thinning_df_lthin_2', 'thinning_df_lthin_3', 'thinning_df_pothin_p1', 'thinning_df_pothin_p2', 'thinning_df_pothin_p3', 'thinning_df_bthin_1', 'thinning_df_bthin_2', 'thinning_df_bthin_3', 'thinning_df_dthin_fb', 'thinning_df_f_om', 'thinning_df_f_ip_cond', 'thinning_df_f_ip_val', 'thinning_df_f_dl_cond', 'thinning_df_f_dl_val', 'thinning_df_f_wd_cond', 'thinning_df_f_wd_val', 'thinning_df_f_am_cond', 'thinning_df_f_am_val', 'thinning_df_f_sm_cond', 'thinning_df_f_am_val', 'thinning_df_d_thin_f'];
        $cal['LINING_DF_CALCULATION'] = ['lining_df_age_general', 'lining_df_age_years', 'lining_df_age_d_elin_fb_inorganic', 'lining_df_age_d_elin_fb_organic', 'lining_df_age_flc', 'lining_df_age_fom', 'lining_df_age_d_elin_f'];
        $cal['SSC-SULFIDE_DF_CALCULATION'] = ['ssc_sulfide_df_age_general', 'ssc_sulfide_df_age_years', 'ssc_sulfide_df_env_severity', 'ssc_sulfide_df_cyanide_adj_ph8', 'ssc_sulfide_df_cyanide_adj_ph8_h2s', 'ssc_sulfide_df_cyanide_adj_adjustment', 'ssc_sulfide_df_cyanide_adj_severity', 'ssc_sulfide_df_crack_present', 'ssc_sulfide_df_ssc_susceptible', 'ssc_sulfide_df_svi', 'ssc_sulfide_df_d_ssc_fb', 'ssc_sulfide_df_d_ssc_f', 'ssc_sulfide_df_d_thin_f', 'ssc_sulfide_df_d_elin_f', 'ssc_sulfide_df_d_thin_f_gov', 'ssc_sulfide_df_d_extf_f', 'ssc_sulfide_df_d_cuif_f', 'ssc_sulfide_df_d_ext_clscc_f', 'ssc_sulfide_df_d_cui_clscc_f', 'ssc_sulfide_df_d_ext_f_gov', 'ssc_sulfide_df_d_caustif_f', 'ssc_sulfide_df_d_amine_f', 'ssc_sulfide_df_d_hic_sohic_h2s_f', 'ssc_sulfide_df_d_ascc_f', 'ssc_sulfide_df_d_pascc_f', 'ssc_sulfide_df_d_cslcc', 'ssc_sulfide_df_d_hsc_hf_f', 'ssc_sulfide_df_d_hic_sohic_hf_f', 'ssc_sulfide_df_d_htha_f', 'ssc_sulfide_df_d_brit_f', 'ssc_sulfide_df_d_tempe_f', 'ssc_sulfide_df_d_885f_f', 'ssc_sulfide_df_d_sigma_f', 'ssc_sulfide_df_d_brit_f_gov', 'ssc_sulfide_df_d_mfat_f', 'ssc_sulfide_df_d_f_total'];

        $ref = Ref_source_pressure_vessel::find($id);

        return view('pv.view', compact('ref', 'row', 'cal'));
    }

    function add(Request $request){

        $record = new Ref_source_pressure_vessel();
        foreach ($request->all() as $key => $value) {
            if (Schema::hasColumn($record->getTable(), $key)) {
                $record->$key = $value;
            }
        }

        $record->corrosion_allowance = $record->last_inspection_thickness - $record->minimum_wall_thickness_per_code;
        $record->h2s_ppm = $record->h2s_content * 10000;
        $record->toxicity_h2s = $record->h2s_content / 100;

        $record->thinning_df_age_from_install = $this->find_diff($record->rbi_date_general, $record->thinning_df_install_date_general);
        $record->thinning_df_agetk_time_in_service = $this->find_diff($record->rbi_date_general, $record->last_inspection_date_general);
        $record->thinning_df_agerc = max(array(($record->last_inspection_thickness - $record->furnished_thickness_at_last_inspection) / $record->corrosion_rate_of_cladding, 0));
        if (strtoupper($record->cladding_present) == "YES") {
            $record->art_cladding = "N/A";
        } else {
            $record->art_cladding = $record->corrosion_rate_of_base_metal * $record->thinning_df_agetk_time_in_service / $record->last_inspection_thickness;
        }

        if(floatval($record->thinning_df_agetk_time_in_service) < $record->thinning_df_agerc){
            $record->art_w_clad_agetk_less_agerc = $record->corrosion_rate_of_cladding * $record->thinning_df_agetk_time_in_service / $record->last_inspection_thickness;
        }

        // dd($record->thinning_df_agetk_time_in_service, $record->thinning_df_agerc);

        if(floatval($record->thinning_df_agetk_time_in_service) < $record->thinning_df_agerc){
            $record->art_w_clad_agetk_more_agerc = "N/A";
        } else {
            $record->art_w_clad_agetk_more_agerc = (($record->corrosion_rate_of_cladding * $record->thinning_df_agerc + $record->corrosion_rate_of_base_metal * ($record->thinning_df_agetk_time_in_service - $record->thinning_df_agerc))/$record->last_inspection_thickness);
        }

        $record->art_selected = round(max(array($record->art_cladding, $record->art_w_clad_agetk_less_agerc, $record->art_w_clad_agetk_more_agerc)), 9);


        $record->thinning_df_fsthin = (($record->thinning_df_yeild_str+$record->thinning_df_tensile_str)/2)*$record->thinning_df_e*1.1;

        $record->thinning_df_sr_thin1 = round((($record->thinning_df_s*$record->thinning_df_e)/$record->thinning_df_fsthin)*(MAX(array($record->thinning_df_tc, $record->minimum_wall_thickness_per_code))/$record->last_inspection_thickness), 2);

        $record->lining_df_age_general = $record->date_of_last_inspection;

        $record->lining_df_age_years = $this->find_diff($record->rbi_date_general, $record->lining_df_age_general);

        $row_lining = strtolower(str_replace(" ", "_", $record->type_of_inorganic_lining));

        $record->lining_df_age_d_elin_fb_inorganic = Ref_source_lining_inorganic::where('years', '<', $record->lining_df_age_years)
            ->orderBy('years', 'desc')
            ->first()->$row_lining;

        $record->ssc_sulfide_df_age_general = $record->last_inspection_date;
        $record->ssc_sulfide_df_age_years = $this->find_diff($record->rbi_date_general, $record->ssc_sulfide_df_age_general);

        $record->thinning_df_pothin_p1 = 0.5;
        $record->thinning_df_pothin_p2 = 0.3;
        $record->thinning_df_pothin_p3 = 0.2;

        $record->thinning_df_bthin_1 = round((1-1*$record->art_selected-$record->thinning_df_sr_thin1)/(1**2*$record->art_selected**2*0.2**2+(1-1*$record->art_selected)**2*0.2**2+$record->thinning_df_sr_thin1**2*0.05**2)**0.5, 6);
        $record->thinning_df_bthin_2 = round((1-2*$record->art_selected-$record->thinning_df_sr_thin1)/(2**2*$record->art_selected**2*0.2**2+(1-2*$record->art_selected)**2*0.2**2+$record->thinning_df_sr_thin1**2*0.05**2)**0.5, 6);
        $record->thinning_df_bthin_3 = round((1-4*$record->art_selected-$record->thinning_df_sr_thin1)/(4**2*$record->art_selected**2*0.2**2+(1-4*$record->art_selected)**2*0.2**2+$record->thinning_df_sr_thin1**2*0.05**2)**0.5, 6);
        $record->thinning_df_dthin_fb = round((($record->thinning_df_pothin_p1*$this->cumnormdist(-$record->thinning_df_bthin_1)) + ($record->thinning_df_pothin_p2*$this->cumnormdist(-$record->thinning_df_bthin_2)) + ($record->thinning_df_pothin_p3*$this->cumnormdist(-$record->thinning_df_bthin_3))) / 0.000156, 6);

        $record->thinning_df_f_om = 1;
        $record->thinning_df_f_ip_val = 1;
        $record->thinning_df_f_dl_val = 1;
        $record->thinning_df_f_wd_val = 1;
        $record->thinning_df_f_am_val = 1;

        $record->lining_df_age_flc = (empty(Ref_source_lining_inorganic_condition::where('_condition', $record->quality_lining)->first())) ? null : Ref_source_lining_inorganic_condition::where('_condition', $record->quality_lining)->first()->value;
        $record->lining_df_age_fom = (empty(Ref_source_lining_inorganic_condition::where('_condition', $record->online_monitoring)->first())) ? null : Ref_source_lining_inorganic_condition::where('_condition', $record->online_monitoring)->first()->value;
        $record->thinning_df_d_thin_f = max([$record->thinning_df_dthin_fb, $record->thinning_df_f_ip_val, $record->thinning_df_f_dl_val, $record->thinning_df_f_wd_val, $record->thinning_df_f_am_val])/ $record->thinning_df_f_om;
        $record->lining_df_age_d_elin_f = ceil(max([$record->lining_df_age_d_elin_fb_inorganic, $record->lining_df_age_d_elin_fb_organic]) * $record->lining_df_age_flc * $record->lining_df_age_fom);

        $record->ssc_sulfide_df_d_thin_f = $record->thinning_df_d_thin_f;
        $record->ssc_sulfide_df_d_elin_f = $record->lining_df_age_d_elin_f;
        if($record->type_of_lining == "NO"){
            $record->ssc_sulfide_df_d_thin_f_gov = $record->ssc_sulfide_df_d_thin_f;
        } else {
            $record->ssc_sulfide_df_d_thin_f_gov = min([$record->ssc_sulfide_df_d_thin_f, $record->ssc_sulfide_df_d_elin_f]);
        }

        $record->ssc_sulfide_df_d_f_total = $record->ssc_sulfide_df_d_thin_f_gov;

        $record->created_by = Auth::user()->username;
        $record->company_id = Session::get('company_id');

        if($record->save()){
            return redirect()->route('te.pv.index')->with('success', 'Data Saved');
        } else {
            return redirect()->route('te.pv.index')->with('error', 'Please contact your system administrator');
        }
    }

    function delete($id){
        Ref_source_pressure_vessel::find($id)->delete();

        return redirect()->back()->with('delete', 'Data Deleted');
    }

    function find_diff($date1, $date2){
        $diff_age = date_diff(date_create($date1), date_create($date2));
        $y = $diff_age->format("%y");
        $m = round(intval($diff_age->format("%m")) / 12, 2);
        return $y+$m;
    }

    function duplicate($id){
        $deId = explode("-", base64_decode($id));
        $ID = end($deId);
        $ref = Ref_source_pressure_vessel::find($ID);

        return view("pv.duplicate", compact('ref'));
    }

    function export(){
        $columns = Schema::getColumnListing('ref_source_pressure_vessel');
        $data = Ref_source_pressure_vessel::all();

        return view('pv.export', compact("data", 'columns'));
    }

    function cumnormdist($x)
    {
        $z = round(($x - 0) / 1, 6);

        $zindex = number_format(round($z, 1), 1,'.','');
        $zy = round($z, 2);
        $table = StandardNormal::Z_SCORES;
        $prob = $table[$zindex][substr($zy, -1)];

        return $prob;
    }
}
