<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 31/03/2020
 * Time: 17:02 PM
 */ 
$amount_index=0;?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm ">
                <div>
                    <h2 class="panel-title titulo_frm">
                        Edicion de Egreso de Caja
                    </h2>
                    <h2 class="panel-title titulo_frm">
                    (Saldo Efectivo en Caja Bs: <?= isset($cash_total_bs)? $cash_total_bs:0 ; ?>) 
                    (Saldo Efectivo en Caja Sus: <?= isset($cash_total_sus)? $cash_total_sus:0; ?>) 
                    </h2>
                </div>
            </div>
            <form id="frm_edit_cash_output" name="frm_edit_cash_output"
                action="<?= site_url('cash_output/edit_cash_output') ?>"
                method="post">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" hidden>
                            <div class="form-group">
                            <input type="text" id="edit_id_cash_output" name="edit_id_cash_output" value="<?= isset($cash_output) ? $cash_output->id : '' ?>" hidden>
                                <div class="form-line">
                                    <b>Caja</b>
                                    <select class="form-control" id="cash" name="cash">
                                        <option value=""> Seleccione un Caja</option>
                                        <?php foreach ($cashs as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?= isset($cash_output) ? is_selected($cash_output->caja_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Tipo de Egreso</b>
                                    <select class="form-control" id="cash_output_type" name="cash_output_type">
                                        <option value=""> Seleccione un Tipo de egreso</option>
                                        <?php foreach ($cash_output_types as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?= isset($cash_output) ? is_selected($cash_output->tipo_egreso_caja_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Fecha de Egreso</b>
                                    <input type="date" class="form-control" id="edit_date_output"
                                                name="edit_date_output"
                                                placeholder="Ingrese la fecha de egreso"
                                                value="<?= isset($cash_output) ? $cash_output->fecha_egreso : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto Bs</b>
                                    <input type="text" class="form-control" id="amount_bs"
                                                name="amount_bs"
                                                placeholder="Ingrese el Monto"
                                                value="<?= isset($cash_output) ? $cash_output->monto_bs : '' ?>"">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto Sus</b>
                                    <input type="text" class="form-control" id="amount_sus"
                                                name="amount_sus"
                                                placeholder="Ingrese el Monto"
                                                value="<?= isset($cash_output) ? $cash_output->monto_sus : '' ?>"">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Detalle</b>
                                    <input type="text" class="form-control" id="edit_detail"
                                                name="edit_detail"
                                                placeholder="Ingrese el detalle"
                                                value="<?= isset($cash_output) ? $cash_output->detalle : '' ?>"">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                             
                <div class="panel-footer" align="center">
                    <button id="btn_edit_cash_output" class="btn btn-success waves-effect no-modal" type="submit">
                        <i class="fa fa-save"></i> Actulizar
                    </button>
                    <a id="cancel_sale" href="<?= site_url('cash_output') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Salir </a>
                </div>

            </form>
        </div>
    </div>
</div>
<script>
var cash_total_bs = <?php  echo json_encode($cash_total_bs); ?>;
var cash_total_sus = <?php  echo json_encode($cash_total_sus); ?>;
 console.log(cash_total_bs);
var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    if(cash_id==false || cash_aperture_id==0){
        console.log(cash_id);
        console.log(cash_aperture_id);
        // alert('CAJA NO APERTURADA');
        // $.redirect('/cash_output');
    }else{
    }
</script>
<script src="<?= base_url('jsystem/cash_output.js') ?>"></script>
