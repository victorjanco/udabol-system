<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 4/6/2018
 * Time: 10:47
 */

$select1 = '';
$select2 = '';
$select3 = '';
$select4 = '';
$select5 = '';
$select6 = '';
$select7 = '';
$select8 = '';
$select9 = '';
if (isset($customer)) {
    switch ($customer) {
        case "Santa Cruz" == $customer->ciudad:
            $select1 = "selected";
            break;
        case "Cochabamba" == $customer->ciudad:
            $select2 = "selected";
            break;
        case "La Paz" == $customer->ciudad:
            $select3 = "selected";
            break;
        case "Tarija" == $customer->ciudad:
            $select4 = "selected";
            break;
        case "Beni" == $customer->ciudad:
            $select5 = "selected";
            break;
        case "Chuquisaca" == $customer->ciudad:
            $select6 = "selected";
            break;
        case "Pando" == $customer->ciudad:
            $select7 = "selected";
            break;
        case "Oruro" == $customer->ciudad:
            $select8 = "selected";
            break;
        case "Potosi" == $customer->ciudad:
            $select9 = "selected";
            break;
    }
}

$title = "NUEVO PAGO";
$url_action = site_url('credit_payment/register_payment');
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm ">
            <div style="">
                <h2 class="panel-title titulo_frm"><?= $title ?></h2>
            </div>
        </div>
        <div class="body">
            <form id="frm_register_payment" name="frm_register_payment"
                  action="<?= $url_action ?>"
                  method="post">
                <input type="text" id="customer_id" name="customer_id"
                       value="<?= isset($customer) ? $customer->id : null; ?>" hidden>
                <fieldset>
                    <legend align="left">Datos del cliente &nbsp;&nbsp;&nbsp;&nbsp;</legend>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="ci">CI: <?= !empty($customer) ? $customer->ci : 'No registro CI'; ?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="nombre">Nombre: <?= !empty($customer) ? $customer->nombre : 'No registro Nombre'; ?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Telefono
                                1: <?= !empty($customer) ? $customer->telefono1 : 'No registro Telefono 1'; ?></label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Telefono
                                2: <?= !empty($customer) ? $customer->telefono2 : 'No registro Telefono 2'; ?></label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="email">Email: <?= !empty($customer) ? $customer->email : 'No registro Email'; ?></label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label"
                                   for="nombre">Direccion: <?= !empty($customer) ? $customer->direccion : 'No registro Direccion'; ?></label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo Cliente: </label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento</label>
                            <select id="ciudad" name="ciudad" class="form-control">
                                <option value="Santa Cruz" <?= $select1 ?>>Santa Cruz</option>
                                <option value="Cochabamba" <?= $select2 ?>>Cochabamba</option>
                                <option value="La Paz" <?= $select3 ?>>La Paz</option>
                                <option value="Tarija" <?= $select4 ?>>Tarija</option>
                                <option value="Beni" <?= $select5 ?>>Beni</option>
                                <option value="Chuquisaca" <?= $select6 ?>>Chuquisaca</option>
                                <option value="Pando" <?= $select7 ?>>Pando</option>
                                <option value="Oruro" <?= $select8 ?>>Oruro</option>
                                <option value="Potosi" <?= $select9 ?>>Potosi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
                        <strong><label for="observation">Observacion del Pago:</label></strong>
                        <div class="form-group">
                            <div class="form-line">
                                            <textarea class="form-control no-resize"
                                                      placeholder="Ingrese una glosa por favor..." name="observation"
                                                      id="observation"></textarea>
                            </div>
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
                                <th style="width: 20%" class="text-center">Total Venta</th>
                                <th style="width: 20%" class="text-center">Total Credito</th>
                                <th style="width: 15%" class="text-center">Total Saldo</th>
                                <th style="width: 15%" class="text-center">Pago</th>
                                <th style="width: 5%" class="text-center">Opcion</th>
                                </thead>
                                <tbody>
                                <?php if (isset($credit_payment)) {
                                    $credit_total_amount = 0;
                                    $pointer = 0;
                                    foreach ($credit_payment as $row_detail):
                                        $html = '<tr>';
                                        $html .= '<td align="center"><input value="' . $row_detail->venta_id . '"  name="sale_id[]" hidden/>' . $row_detail->nro_venta . '</td>';
                                        $html .= '<td align="center"><input value="' . $row_detail->id . '"  name="credit_sale_id[]" hidden/>' . $row_detail->nro_venta_credito . '</td>';
                                        $html .= '<td align="right">' . $row_detail->total . '</td>';
                                        $html .= '<td align="right">' . $row_detail->monto_credito . '</td>';
                                        $html .= '<td align="right"> <input value="' . $row_detail->monto_saldo . '" id="residue' . $pointer . '" name="residue[]" hidden/>' . $row_detail->monto_saldo . '</td>';
                                        $html .= '<td><input class="form-control" style="background-color: rgb(235, 235, 228);text-align: right;" value="0.00" id="payment' . $pointer . '" name="payment[]" onclick="put_empty('.$pointer.')" onkeyup="modify_payment('.$pointer.')"/></td>';

                                        $html .= '</tr>';
                                        echo $html;
                                        $pointer++;
                                        $credit_total_amount = $credit_total_amount + ($row_detail->monto_saldo);
                                    endforeach;
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td style="text-align: right" colspan="5"><b>Total Deuda Bs.:</b></td>
                                    <td style="padding: 10px; text-align: right"><b><?php if (isset($credit_payment)) {
                                                echo number_format($credit_total_amount, CANTIDAD_MONTO_DECIMAL, '.', '');
                                            } else {
                                                echo '0.00';
                                            } ?></b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right" colspan="5"><b>Total Pagar Bs.:</b></td>
                                    <td style="padding: 10px; text-align: right"><b><input class="form-control"
                                                                                           style="background-color: rgb(235, 235, 228);text-align: right;"
                                                                                           value="0.00"
                                                                                           id="total_payment"
                                                                                           name="total_payment"/></b>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div align="center">
                        <div class="table-responsive" style="width:50%;">
                            <table class="table table-striped table-hover table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th width="30%">FORMA PAGO</th>
                                    <th width="70%">MONTOS</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> <input type="checkbox" id="check_efectivo" name="check_efectivo"
                                                    class="filled-in"  value="1"/>
                                            <label for="check_efectivo">EFECTIVO</label>
                                        </td>
                                        <td>
                                            <label for="check_cheque">Total Pagar Bs</label>
                                            <input class="form-control" type="number" step="any"
                                            id="monto_efectivo" name="monto_efectivo"
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                            <label for="check_cheque">Cambio Bs</label>
                                            <input class="form-control" type="number" step="any"
                                            id="monto_cambio" name="monto_cambio"
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>  <input type="checkbox" id="check_contado" name="check_contado"
                                                    class="filled-in"  value="1"/>
                                            <label for="check_contado">Bolivianos Bs</label>
                                        </td>
                                        <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                            id="monto_bs" name="monto_bs" 
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>  <input type="checkbox" id="check_dolares" name="check_dolares"
                                                    class="filled-in"  value="1"/>
                                            <label for="check_dolares">DOLARES $</label>
                                        </td>
                                        <td> <label for="check_cheque">Tipo Cambio <?= $change_type->monto_cambio_venta ?></label>
                                            <input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                            id="monto_sus" name="monto_sus"
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> <input type="checkbox" id="check_tarjeta" name="check_tarjeta"
                                                    class="filled-in"  value="1"/>
                                            <label for="check_tarjeta">TARJETA</label>
                                        </td>
                                        <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                            id="monto_tarjeta" name="monto_tarjeta"
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" id="check_cheque" name="check_cheque"
                                                    class="filled-in"  value="1"/>
                                            <label for="check_cheque">TRANSFERENCIA</label>
                                        </td>
                                        <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                            id="monto_cheque" name="monto_cheque"
                                            style="background-color: rgb(235, 235, 228);text-align: right;"
                                            value="0"
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </fieldset>

                <div class="modal-footer">

                    <button class="btn btn-success waves-effect no-modal" type="submit"><i
                                class="fa fa-save"></i> Guardar
                    </button>
                    <a href="<?= site_url('credit_payment') ?>" class="btn btn-danger waves-effect" type="button"><i
                                class="fa fa-times"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    var change_type = <?php echo json_encode($change_type) ?>;

</script>
<script src="<?= base_url('jsystem/credit_payment.js') ?>"></script>