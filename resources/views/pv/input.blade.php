@extends('layouts.template') @section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">Input Record</h3>
        <div class="card-toolbar">
            <a href="{{route('te.pv.index')}}" class="btn btn-success btn-icon btn-sm">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('te.pv.add') }}" method="post">
            <div class="row">
                <div class="col-6">
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("cl number") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='cl_number' placeholder='cl_number'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("tag") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='tag' placeholder='tag'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("name equipment") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='name_equipment' placeholder='name_equipment'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("model rbi type") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='model_rbi_type' placeholder='model_rbi_type'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("fluid content") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='fluid_content' placeholder='fluid_content'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("stream no") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='stream_no' placeholder='stream_no'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("vol inv num") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='vol_inv_num' placeholder='vol_inv_num'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("id system") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='id_system' placeholder='id_system'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("pnid") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='pnid' placeholder='pnid'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("design code") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='design_code' placeholder='design_code'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("risk target") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='risk_target' placeholder='risk_target'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("material code") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='material_code' placeholder='material_code'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("reliasoft") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='reliasoft' placeholder='reliasoft'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("lining") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='lining' placeholder='lining'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("insulation") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='insulation' placeholder='insulation'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("cloride and water") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='cloride_and_water'
                            placeholder='cloride_and_water'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("fluid type") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='fluid_type' placeholder='fluid_type'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("operating temperature") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='operating_temperature'
                            placeholder='operating_temperature'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("operating pressure") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='operating_pressure'
                            placeholder='operating_pressure'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("ph") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='ph' placeholder='ph'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("date of component installation") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='date_of_component_installation'
                            placeholder='date_of_component_installation'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("plan date") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' type="date" name='plan_date' placeholder='plan_date'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("damage factor type") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='damage_factor_type'
                            placeholder='damage_factor_type'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning type") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='thinning_type' placeholder='thinning_type'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("date of last wall thickness inspection") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='date_of_last_wall_thickness_inspection'
                            placeholder='date_of_last_wall_thickness_inspection'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("number of inspections") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='number_of_inspections'
                            placeholder='number_of_inspections'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("last inspection thickness") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='last_inspection_thickness'
                            placeholder='last_inspection_thickness'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("corrosion rate of base metal") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='corrosion_rate_of_base_metal'
                            placeholder='corrosion_rate_of_base_metal'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("minimum wall thickness per code (mm)") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='minimum_wall_thickness_per_code'
                            placeholder='minimum_wall_thickness_per_code'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("cladding present") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='cladding_present'
                            placeholder='cladding_present'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("furnished thickness at last inspection") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='furnished_thickness_at_last_inspection'
                            placeholder='furnished_thickness_at_last_inspection'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("corrosion rate of cladding") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='corrosion_rate_of_cladding'
                            placeholder='corrosion_rate_of_cladding'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("online monitoring type") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='online_monitoring_type'
                            placeholder='online_monitoring_type'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning mechanism type update") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_mechanism_type_update'
                            placeholder='thinning_mechanism_type_update'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("date of last inspection") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='date_of_last_inspection'
                            placeholder='date_of_last_inspection'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("type of lining") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='type_of_lining' placeholder='type_of_lining'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("type of inorganic lining") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='type_of_inorganic_lining'
                            placeholder='type_of_inorganic_lining'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("lining installation date") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='lining_installation_date'
                            placeholder='lining_installation_date'></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("quality lining") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='quality_lining' placeholder='quality_lining'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("online monitoring") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='online_monitoring'
                            placeholder='online_monitoring'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("last inspection date") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='last_inspection_date'
                            placeholder='last_inspection_date'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("no of insection") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='no_of_insection'
                            placeholder='no_of_insection'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("past inspection") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='past_inspection'
                            placeholder='past_inspection'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("internal cracking present") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='internal_cracking_present'
                            placeholder='internal_cracking_present'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("internal cracking") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='internal_cracking'
                            placeholder='internal_cracking'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("ph cracking") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='ph_cracking' placeholder='ph_cracking'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("h2s content") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='h2s_content' placeholder='h2s_content'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("cyanide present") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='cyanide_present'
                            placeholder='cyanide_present'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("cyanide present") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='cyanide_present'
                            placeholder='cyanide_present'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("pwht") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='pwht' placeholder='pwht'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("est brinell hardness") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='est_brinell_hardness'
                            placeholder='est_brinell_hardness'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("fluide representative") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='fluide_representative'
                            placeholder='fluide_representative'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("storage phase") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='storage_phase' placeholder='storage_phase'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("atm temperature") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='atm_temperature'
                            placeholder='atm_temperature'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("componennt dimention") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='componennt_dimention'
                            placeholder='componennt_dimention'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("detection class") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='detection_class'
                            placeholder='detection_class'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("isolation class") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='isolation_class'
                            placeholder='isolation_class'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("component mass") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='component_mass' placeholder='component_mass'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("inventory mass") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='inventory_mass' placeholder='inventory_mass'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("flammability mitigation system") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='flammability_mitigation_system'
                            placeholder='flammability_mitigation_system'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("toxicity cl2") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='toxicity_cl2' placeholder='toxicity_cl2'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df rbi date") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='thinning_df_rbi_date'
                            placeholder='thinning_df_rbi_date'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df install date general") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='thinning_df_install_date_general'
                            placeholder='thinning_df_install_date_general'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("rbi date general") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='rbi_date_general'
                            placeholder='rbi_date_general'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("last inspection date general") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            type="date"
                            name='last_inspection_date_general'
                            placeholder='last_inspection_date_general'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df yeild str") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_yeild_str'
                            placeholder='thinning_df_yeild_str'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df tensile str") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_tensile_str'
                            placeholder='thinning_df_tensile_str'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df s") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='thinning_df_s' placeholder='thinning_df_s'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df tc") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='thinning_df_tc' placeholder='thinning_df_tc'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df confidence level") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_confidence_level'
                            placeholder='thinning_df_confidence_level'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df e") }}</label>
                        <div class='col-md-9 col-sm-12'><input class='form-control' name='thinning_df_e' placeholder='thinning_df_e'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df sr thin2") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_sr_thin2'
                            placeholder='thinning_df_sr_thin2'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df nthin a") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_nthin_a'
                            placeholder='thinning_df_nthin_a'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df nthin b") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_nthin_b'
                            placeholder='thinning_df_nthin_b'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df nthin c") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_nthin_c'
                            placeholder='thinning_df_nthin_c'></div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-form-label col-md-3 col-sm-12'>{{ ucwords("thinning df nthin d") }}</label>
                        <div class='col-md-9 col-sm-12'><input
                            class='form-control'
                            name='thinning_df_nthin_d'
                            placeholder='thinning_df_nthin_d'></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-3 col-sm-12"></label>
                        <div class="col-md-9 col-sm-12">
                            @csrf
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@section('custom_script')
    <script>
        $(document).ready(function(){
            var inputs = $("input").toArray()
            console.log(inputs.length)
        })
    </script>
@endsection
