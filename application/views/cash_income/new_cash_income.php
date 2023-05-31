<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/03/2020
 * Time: 10:02 AM
 */ ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm ">
                <div>
                    <h2 class="panel-title titulo_frm">Registro de Ingreso de Caja
                        
                    </h2>
                </div>
            </div>
            <form id="frm_new_cash_income" name="frm_new_cash_income"
                action="<?= site_url('cash_income/register_cash_income') ?>"
                method="post">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" hidden>
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Caja</b>
                                    <select class="form-control" id="cash" name="cash">
                                        <?php foreach ($cashs as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?= isset($cash_income) ? is_selected($cash_income->caja_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Tipo de Ingreso</b>
                                    <select class="form-control" id="cash_income_type" name="cash_income_type">
                                        <option value=""> Seleccione un Tipo de ingreso</option>
                                        <?php foreach ($cash_income_types as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?= isset($cash_income) ? is_selected($cash_income->tipo_ingreso_caja_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Fecha de Ingreso</b>
                                    <input type="date" class="form-control" id="add_date_income"
                                                name="add_date_income"
                                                placeholder="Ingrese la fecha de ingreso"
                                                value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto Bs</b>
                                    <input type="text" class="form-control" id="amount_bs" onkeypress="onKeyPressAmount(event)"
                                                name="amount_bs"
                                                placeholder="Ingrese el Monto"
                                                value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto Sus</b>
                                    <input type="text" class="form-control" id="amount_sus" onkeypress="onKeyPressAmount(event)"
                                                name="amount_sus"
                                                placeholder="Ingrese el Monto"
                                                value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto tarjeta</b>
                                    <input type="text" class="form-control" id="amount_tarjeta" onkeypress="onKeyPressAmount(event)"
                                                name="amount_tarjeta"
                                                placeholder="Ingrese el Monto"
                                                value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto Transferencia</b>
                                    <input type="text" class="form-control" id="amount_cheque" onkeypress="onKeyPressAmount(event)"
                                                name="amount_cheque"
                                                placeholder="Ingrese el Monto"
                                                value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Detalle</b>
                                    <input type="text" class="form-control" id="add_detail" 
                                                name="add_detail"
                                                placeholder="Ingrese el detalle"
                                                value="">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                                             
                <div class="panel-footer" align="center">
                    <button id="btn_new_cash_income" class="btn btn-success waves-effect no-modal" type="submit">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                   
                    <a id="cancel_sale" href="<?= site_url('cash_income') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Cancelar </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    if(cash_id==false || cash_aperture_id==0){
        console.log(cash_id);
        console.log(cash_aperture_id);
    
    }
</script>
<script src="<?= base_url('jsystem/cash_income.js') ?>"></script>
