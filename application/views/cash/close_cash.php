<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/02/2020
 * Time: 09:02 AM
 */ ?>
<form id="frm_cash_closing" method="post">
    <div class="block-header" id="container_buttons">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-success btn-block" id="btn_close_cash">
                <i class="material-icons">save</i> Cerrar Caja y Salir
                <div class="ripple-container"></div>
            </button>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <button class="btn btn-warning btn-block" id="btn_generate_pdf">
                <i class="material-icons">picture_as_pdf</i> Generar PDF
                <div class="ripple-container"></div>
            </button>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <a class="btn btn-danger btn-block" href="<?= base_url('home') ?>">
                <i class="material-icons">clear</i> Cancelar
                <div class="ripple-container"></div>
            </a>
        </div>
    </div>
    <br><br>
    <br><br>
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>DATOS</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <label class="col-sm-4 col-form-label"><b>SUCURSAL:</b></label>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <input type="text" id="close_branch_office_id" name="close_branch_office_id"
                                    value="<?= get_branch_id_in_session(); ?>" hidden>
                                <input type="text" class="form-control" id="close_branch_office_name"
                                    name="close_branch_office_name"
                                    value="<?= get_branch_office_name_in_session(); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label"><b>CAJA:</b></label>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                            <input type="text" id="close_cash_id" name="close_cash_id" value="<?= $cash_aperture->caja_id; ?>"
                                    hidden>
                                <input type="text" id="close_cash_aperture_id" name="close_cash_aperture_id"
                                    value="<?= $cash_aperture->id; ?>"
                                    hidden>
                                <input type="text" class="form-control" id="close_cash_name" name="close_cash_name"
                                    value="<?= $cash->descripcion; ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label"><b>FECHA APERTURA:</b></label>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <input type="text" class="form-control" id="close_date_open" name="close_date_open"
                                    value="<?= $cash_aperture->fecha_apertura; ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label"><b>FECHA CIERRE:</b></label>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <input type="text" class="form-control" id="close_date_close" name="close_date_close"
                                    value="<?= $date_close; ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 col-form-label"><b>USUARIO:</b></label>
                        <div class="col-sm-6">
                            <div class="form-group bmd-form-group">
                                <input type="text" id="close_user_id" name="close_user_id" value="<?= get_user_id_in_session(); ?>"
                                    hidden>
                                <input type="text" class="form-control" id="close_user_name" name="close_user_name"
                                    value="<?= get_user_name_in_session(); ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>BALANCE DE CAJA</h2>
                </div>
                <div class="body table-responsive">
                    <table id="list_cash_closing" class="table table-striped table-bordered">
                        <thead>
                        <th>TIPO</th>
                        <th>Bs</th>
                        <th>Sus(6.95)</th>
                        <th hidden>Cambio Bs</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Monto Apertura de Caja</td>
                                <th class="text-center"><b><?= number_format($cash_aperture->monto_apertura_bs, 2, '.', ''); ?></b></th>
                                <th class="text-center" title="<?= number_format($cash_aperture->monto_apertura_sus * $change_type->monto_cambio_venta, 2, '.', ''); ?> Bs"><b><?= number_format($cash_aperture->monto_apertura_sus, 2, '.', ''); ?></b></th>
                            </tr>
                            <tr>
                                <td class="text-center">Ingresos</td>
                                <th class="text-center"><b><?= number_format($cash_income_total_bss, 2, '.', ''); ?></b></th>
                                <th class="text-center" title="<?= number_format($cash_income_total_sus * $change_type->monto_cambio_venta, 2, '.', ''); ?> Bs"><b><?= number_format($cash_income_total_sus, 2, '.', ''); ?></b></th>
                            </tr>
                          
                            <tr>
                                <td class="text-center">Egresos</td>
                                <th class="text-center"><b><?= number_format($cash_output_total_bs, 2, '.', ''); ?></b></th>
                                <th class="text-center" title="<?= number_format($cash_output_total_sus * $change_type->monto_cambio_venta, 2, '.', ''); ?> Bs"><b><?= number_format($cash_output_total_sus, 2, '.', ''); ?></b></th>
                            </tr>
                            <tr>
                                <td class="td-price ; text-center" style="font-weight: bold">MONTO DE CIERRE</td>
                                <input type="hidden" name="cash_total_bs" id="cash_total_bs" value="<?=number_format($cash_total_bs, 2, '.', '');?>" 
                                    class="filled-in"/>
                                <th class="td-price ; text-center"><b><?= number_format($cash_total_bs, 2, '.', ''); ?></b></th>
                                <input type="hidden" name="cash_total_sus" id="cash_total_sus" value="<?=number_format($cash_total_sus, 2, '.', '');?>"
                                    class="filled-in"/>
                                <th class="td-price ; text-center" title="<?= number_format($cash_total_sus * $change_type->monto_cambio_venta, 2, '.', ''); ?> Bs">
                                    <b><?= number_format($cash_total_sus, 2, '.', ''); ?></b></th>
                            </tr>
                            <tr>
                                <td class="td-price ; text-center" style="font-weight: bold">Total Cambio Bs</td>
                                <th class="td-price ; text-center"><b><?= number_format($cash_income_total_cambios, 2, '.', ''); ?></b></th>
                            </tr>
                            <tr>
                                <td class="td-price ; text-center" style="font-weight: bold">TOTAL EFECTIVO BS</td>
                                <input type="hidden" name="cash_total_efective" id="cash_total_efective" value="<?=number_format($cash_total_efective, 2, '.', '');?>" 
                                    class="filled-in"/>
                                <th class="td-price ; text-center"><b><?= number_format($cash_total_efective, 2, '.', ''); ?></b></th>
                               
                            </tr>
                            <tr>
                                <td class="text-center">Tarjeta</td>
                                <th class="text-center"><b><?= number_format($cash_income_total_tarjetas, 2, '.', ''); ?></b></th>
                                
                            </tr>
                            <tr>
                                <td class="text-center">Transferencia</td>
                                <th class="text-center"><b><?= number_format($cash_income_total_cheques, 2, '.', ''); ?></b></th>
                                
                            </tr>
                            
                            <tr>
                                <td class="td-price ; text-center" style="font-weight: bold">TOTAL GENERAL BS</td>
                                <input type="hidden" name="cash_total_general" id="cash_total_general" value="<?=number_format($cash_total_general, 2, '.', '');?>" 
                                    class="filled-in"/> 
                                <th class="td-price ; text-center"><b><?= number_format($cash_total_general, 2, '.', ''); ?></b></th>
                               
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Ingresos de Cajas</h2>
                </div>
                <div class="body table-responsive">
                    <table id="list_cash_closing" class="table table-striped table-bordered ">
                        <thead>
                        <th>NRO</th>
                        <th><b>FECHA</b></th>
                        <th><b>TIPO</b></th>
                        <th><b>DETALLE</b></th>
                        <th><b>CAJA</b></th>
                        <th><b>MONTO VENTA</b></th>
                        <th><b>BOLIVIANO BS</b></th>
                        <th><b>DOLARES $</b></th>
                        <th><b>TARJETA</b></th>
                        <th><b>TRANSFERENCIA</b></th>
                        <th><b>CAMBIO BS</b></th>
                        </thead>
                        <tbody>
                        <?php 
                        foreach ($cash_incomes as $row) {
                            ?>
                            <tr>
                                <td class="text-center"><?= $row->nro_transaccion; ?></td>
                                <td class="text-center"><?= $row->fecha_ingreso; ?></td>
                                <td class="text-center"><?= $row->nombre_tipo_ingreso_caja; ?></td>
                                <td class="text-center"><?= $row->detalle; ?></td>
                                <td class="text-center"><?= $row->nombre_caja; ?></td>
                                <td class="text-center"><?= number_format($row->monto_venta, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_bs, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_sus, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_tarjeta, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_cheque, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_cambio, 2, '.', ''); ?></td>
                            </tr>
                        <?php
                        } ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center" style="font-weight: bold">TOTALES</td>
                            <th class="text-center"><input type="hidden" name="cash_income_total_venta" id="cash_income_total_venta" value="<?=number_format($cash_income_total_venta, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_income_total_venta, 2, '.', ''); ?></b></th>
                            <th class="text-center">
                                <input type="hidden" name="cash_income_total_bs" id="cash_income_total_bs" value="<?=number_format($cash_income_total_bss, 2, '.', '');?>" hidden/>
                                <b><?= number_format($cash_income_total_bss, 2, '.', ''); ?></b></th>
                            <th class="text-center"><input type="hidden" name="cash_income_total_sus" id="cash_income_total_sus" value="<?=number_format($cash_income_total_sus, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_income_total_sus, 2, '.', ''); ?></b></th>
                            <th class="text-center"><input type="hidden" name="cash_income_total_tarjeta" id="cash_income_total_tarjeta" value="<?=number_format($cash_income_total_tarjetas, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_income_total_tarjetas, 2, '.', ''); ?></b></th>
                            <th class="text-center"><input type="hidden" name="cash_income_total_cheque" id="cash_income_total_cheque" value="<?=number_format($cash_income_total_cheques, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_income_total_cheques, 2, '.', ''); ?></b></th>
                            <th class="text-center"><input type="hidden" name="cash_income_total_cambio" id="cash_income_total_cambio" value="<?=number_format($cash_income_total_cambios, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_income_total_cambios, 2, '.', ''); ?></b></th>
                        </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Egresos de Cajas</h2>
                </div>
                <div class="body table-responsive">
                    <table id="list_cash_closing" class="table table-striped table-bordered ">
                        <thead>
                        <th>NRO</th>
                        <th><b>FECHA</b></th>
                        <th><b>TIPO</b></th>
                        <th><b>DETALLE</b></th>
                        <th><b>CAJA</b></th>
                        <th><b>MONTO BS</b></th>
                        <th><b>MONTO SUS</b></th>
                        </thead>
                        <tbody>
                        <?php 
                        foreach ($cash_outputs as $row) {
                            ?>
                            <tr>
                                <td class="text-center"><?= $row->nro_transaccion; ?></td>
                                <td class="text-center"><?= $row->fecha_egreso; ?></td>
                                <td class="text-center"><?= $row->nombre_tipo_egreso_caja; ?></td>
                                <td class="text-center"><?= $row->detalle; ?></td>
                                <td class="text-center"><?= $row->nombre_caja; ?></td>
                                <td class="text-center"><?= number_format($row->monto_bs, 2, '.', ''); ?></td>
                                <td class="text-center"><?= number_format($row->monto_sus, 2, '.', ''); ?></td>
                            </tr>
                        <?php
                        } ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center" style="font-weight: bold">TOTALES</td>
                            <th class="text-center">
                                <input type="hidden" name="cash_output_total_bs" id="cash_output_total_bs" value="<?=number_format($cash_output_total_bs, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_output_total_bs, 2, '.', ''); ?></b>
                            </th>
                            <th class="text-center">
                                <input type="hidden" name="cash_output_total_sus" id="cash_output_total_sus" value="<?=number_format($cash_output_total_sus, 2, '.', '');?>"
                                    class="filled-in"/>
                                <b><?= number_format($cash_output_total_sus, 2, '.', ''); ?></b>
                            </th>
                        </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
   var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    if(cash_id==false || cash_aperture_id==0){
        console.log(cash_id);
        console.log(cash_aperture_id);
    }
    
</script>