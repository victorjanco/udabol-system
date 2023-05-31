<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 4/6/2018
 * Time: 10:47
 */

$title = "VER PAGO";
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm ">
            <div style="">
                <h2 class="panel-title titulo_frm"><?= $title ?></h2>
            </div>
        </div>
        <div class="body">
                <fieldset>
                    <legend align="left">Datos de la Transaccion del Pago&nbsp;&nbsp;&nbsp;&nbsp;</legend>
                    <div class="row clearfix">
                       <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="ci">CI: <?/*= !empty($customer) ? $customer->ci : 'No registro CI'; */?></label>
                        </div>-->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="nombre">Nombre: <?= !empty($credit_payment) ? $credit_payment->nombre_cliente : 'No registro Nombre'; ?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Nro. Transaccion : <?= !empty($credit_payment) ? $credit_payment->nro_transaccion_pago : 'No registro Telefono 1'; ?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Fecha Emision
                                : <?= !empty($credit_payment) ? $credit_payment->fecha_emision : 'No registro Telefono 2'; ?></label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="email">Sucursal: <?= get_branch_office_name_in_session() ?></label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label">Usuario: <?= !empty($credit_payment) ? $credit_payment->user_cobrador : 'No registro Direccion'; ?></label>
                        </div>
                        <div class=" col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <strong><label for="observation">Observacion del Pago: <?=$credit_payment->observacion?></label></strong>

                        </div>
                    </div>

                </fieldset>

                <fieldset>
                    <legend align="left">Ventas al Credito con Saldo</legend>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-xs-12">
                            <table width="100%" id="list_credit_sale" class="table-bordered ">
                                <thead>
                                <th style="width: 12%" class="text-center">Nro. Venta</th>
                                <th style="width: 13%" class="text-center">Nro. Venta Credito</th>
                                <th style="width: 15%" class="text-center">Total Venta</th>
                                <th style="width: 15%" class="text-center">Total Credito</th>
                                <th style="width: 15%" class="text-center">Saldo Anterior</th>
                                <th style="width: 12%" class="text-center">Monto Pagado</th>
                                <th style="width: 13%" class="text-center">Saldo Actual</th>
                                </thead>
                                <tbody>
                                <?php if (isset($detail_credit_payment)) {
                                    foreach ($detail_credit_payment as $row_detail):
                                        $html = '<tr>';
                                        $html .= '<td align="center">' . $row_detail->nro_venta . '</td>';
                                        $html .= '<td align="center">' . $row_detail->nro_venta_credito . '</td>';
                                        $html .= '<td align="right">' . $row_detail->total . '</td>';
                                        $html .= '<td align="right">' . $row_detail->monto_credito . '</td>';
                                        $html .= '<td align="right"> ' . $row_detail->monto_saldo_actual . '</td>';
                                        $html .= '<td align="right"> ' . $row_detail->monto_pagado . '</td>';
                                        $html .= '<td align="right"> ' . $row_detail->monto_saldo . '</td>';

                                        $html .= '</tr>';
                                        echo $html;
                                    endforeach;
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td style="text-align: right" colspan="6"><b>Total Pagado Bs.:</b></td>
                                    <td style="padding: 10px; text-align: right"><b><?php if (isset($credit_payment)) {
                                                echo number_format($credit_payment->monto_total, CANTIDAD_MONTO_DECIMAL, '.', '');
                                            } else {
                                                echo '0.00';
                                            } ?></b></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </fieldset>

                <div class="modal-footer">

                    <a href="<?= site_url('credit_payment/payments') ?>" class="btn btn-danger waves-effect" type="button"><i
                            class="fa fa-times"></i> Volver</a>
                </div>

        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/credit_payment.js') ?>"></script>