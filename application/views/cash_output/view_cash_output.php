<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 31/03/2020
 * Time: 16:02 PM
 */ ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm ">
                <div>
                    <h2 class="panel-title titulo_frm">Visualizacion de Egreso de Caja</h2>
                </div>
            </div>
            <form id="frm_new_cash_output" name="frm_new_cash_output"
                action="<?= site_url('cash_output/register_cash_output') ?>"
                method="post">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Caja</b>
                                    <select class="form-control" id="cash" name="cash" disabled>
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
                                    <select class="form-control" id="cash_output_type" name="cash_output_type" disabled>
                                        <option value=""> Seleccione un Tipo de Egreso</option>
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
                                    <input type="date" class="form-control" id="add_date_output"
                                                name="add_date_output"
                                                placeholder="Ingrese la fecha de egreso"
                                                value="<?= isset($cash_output) ? $cash_output->fecha_egreso : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
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
                                    <input type="text" class="form-control" id="add_detail"
                                                name="add_detail"
                                                placeholder="Ingrese el detalle"
                                                value="<?= isset($cash_output) ? $cash_output->detalle : '' ?>"">
                                </div>
                            </div>
                        </div>
                        
                    </div>

                </div>
                                             
                <div class="panel-footer" align="center">
                    <a id="cancel_sale" href="<?= site_url('cash_output') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Salir </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/cash_output.js') ?>"></script>
