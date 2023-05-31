<?php
/**
 * Created by PhpStorm.
 * User: Ing. Renato Reyes Fuentes (Green Ranger)
 * Date: 15/11/2019
 * Time: 10:38 AM
 */

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"> INFORMACION DE LA RECEPCION </h4>
            </div>
            <form id="frm_register_note_sale" name="frm_register_note_sale" action="<?= site_url("reception/view_reception_and_note_sale") ?>"
                  method="post">

                <div class="body">

                    <div id="div_titulo_datos" class="header">
                        <h2>
                            <strong> DATOS DE LA RECEPCIÓN </strong>
                        </h2>
                    </div><br>

                    <div id="div_datos" class="row clearfix">

                        <input type="text" id="order_work_id" name="order_work_id"  value="<?= $recepcion["order_work"]->id; ?>" hidden>
                        <input type="text" id="reception_id" name="reception_id" value="<?= $reception_id; ?>" hidden>
                        <input type="text" id="code_reception" name="code_reception" value="<?= $recepcion["order_work"]->codigo_trabajo; ?>" hidden>
                        <input type="text" id="reception_payment_observation" name="reception_payment_observation" value="<?= $recepcion["payment_observation"]; ?>"hidden>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12" >
                                <label><b>Sucursal:</b></label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["branch_office"]->nombre_comercial; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Cod. Recepción:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->codigo_recepcion; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Nro. Comprobante:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["order_work"]->codigo_trabajo; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Vendedor:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["user"]->nombre; ?>
                            </div>
                        </div>

                    </div>

                    <div id="div_datos2" class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Fecha Recepción:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->fecha_registro; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Fecha Orden Trabajo:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["order_work"]->fecha_registro; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Tipo Cliente:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php switch ($recepcion["reception"]->tipo_cliente) {
                                    case 0:
                                        $customer_type = 'Cliente Diario';
                                        break;
                                    case 1:
                                        $customer_type = 'Cliente por Mayor';
                                        break;
                                    case 2:
                                        $customer_type = 'Cliente Express';
                                        break;
                                    case 3:
                                        $customer_type = 'Cliente laboratorio';
                                        break;
                                } ?>

                                <?= $customer_type; ?>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Cliente:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->nombre; ?>
                            </div>
                        </div>

                    </div>

                    <div id="div_datos3" class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">C.I.:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->ci; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Telefono:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->telefono1; ?> - <?= $recepcion["reception"]->telefono2; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Marca:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->nombre_marca; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Modelo:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->nombre_comercial; ?>
                            </div>
                        </div>

                    </div>

                    <div id="div_datos4" class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Imei:</label>
                            </div>
                            <div class="col-lg-12">
                                <?= $recepcion["reception"]->imei; ?>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Garantía:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php if($recepcion["reception"]->garantia == 0){ ?>
                                   Sin Garantía
                                <?php } else if($recepcion["reception"]->garantia == 1){ ?>
                                    Con Garantía
                                <?php } else { ?>
                                    Por Verificar
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div id="div_datos5" class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Observación Recepción:</label>
                                <?php
                                $observacion_recepcion = $recepcion["reception"]->observacion_recepcion;
                                if($observacion_recepcion == '') { ?>
                                    SIN OBSERVACIÓN
                                <?php } else { ?>
                                    <?= $observacion_recepcion; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div id="div_datos6" class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Observación Tecnico:</label>
                                <?php
                                $observacion = $recepcion["order_work"]->observacion;
                                if($observacion == '') { ?>
                                    SIN OBSERVACIÓN
                                <?php } else { ?>
                                    <?= $observacion; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div id="div_titulo_datos2" class="header">
                        <h2>
                            <strong> DETALLE DE FALLAS Y SOLUCIONES </strong>
                        </h2>
                    </div><br>

                    <div id="div_datos6" class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Fallas:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $data_detail_failure = $recepcion["detail_failures"];
                                $failure_detail = '';
                                for ($i = 0; $i < sizeof($data_detail_failure) ; $i++) {
				                    $item = $data_detail_failure[$i];
				                    $failure_detail =  $failure_detail . $item->nombre . '<br>';
                                }?>

                                <?= $failure_detail; ?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">Soluciones:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $data_detail_solution = $recepcion["detail_solutions"];
                                $solution_detail = '';
                                for ($i = 0; $i < sizeof($data_detail_solution) ; $i++) {
                                    $items = $data_detail_solution[$i];
                                    $solution_detail = $solution_detail . $items->nombre . '<br>';
                                }?>

                                <?= $solution_detail; ?>
                            </div>
                        </div>
                    </div>

                    <div id="div_titulo_datos2" class="header">
                        <h2>
                            <strong> DETALLE DE SERVICIOS </strong>
                        </h2>
                    </div><br>

                    <div id="div_datos6" class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">SERVICIO:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $service_detail = '';
                                if(count($recepcion["detail_services"]) > 0) {
                                    $data_detail_services = $recepcion["detail_services"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_services"]); $i++) {
                                        $item = $data_detail_services[$i];
                                        $service_observation = $item->observacion;
                                        if (!$service_observation) {
                                            $service_observation = 'SIN OBSERVACION';
                                        }
                                        $service_detail = $service_detail . $item->nombre_servicio . '<br>';

                                    }
                                }?>
                                <?= $service_detail; ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">OBSERVACION:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $service_detail_observation = '';
                                if(count($recepcion["detail_services"]) > 0) {
                                    $data_detail_services = $recepcion["detail_services"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_services"]); $i++) {
                                        $item = $data_detail_services[$i];
                                        $service_observation = $item->observacion;
                                        if (!$service_observation) {
                                            $service_observation = 'SIN OBSERVACION';
                                        }else{
                                            $service_detail_observation = $service_observation . '<br>';
                                        }
                                    }
                                }?>
                                <?= $service_detail_observation; ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">PRECIO:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $service_detail_price = '';
                                if(count($recepcion["detail_services"]) > 0) {
                                    $data_detail_services = $recepcion["detail_services"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_services"]); $i++) {
                                        $item = $data_detail_services[$i];
                                        $service_observation = $item->observacion;
                                        if (!$service_observation) {
                                            $service_observation = 'SIN OBSERVACION';
                                        }
                                        $service_detail_price = $service_detail_price . $item->precio_servicio . '<br>';

                                    }
                                }?>
                                <?= $service_detail_price; ?>
                            </div>
                        </div>
                    </div>

                    <div id="div_titulo_datos2" class="header">
                        <h2>
                            <strong> DETALLE DE REPUESTOS </strong>
                        </h2>
                    </div><br>

                    <div id="div_datos6" class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">REPUESTO:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $detail_products = '';
                                if(count($recepcion["detail_products"]) > 0) {
                                    $data_detail_products = $recepcion["detail_products"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_products"]); $i++) {
                                        $item = $data_detail_products[$i];
                                        $detail_products = $detail_products . $item->nombre_producto . '<br>';
                                    }
                                }?>
                                <?php if(count($recepcion["detail_products"]) > 0) { ?>
                                    <?= $detail_products; ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">CANTIDAD:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $detail_products_quantity = '';
                                if(count($recepcion["detail_products"]) > 0) {
                                    $data_detail_products = $recepcion["detail_products"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_products"]); $i++) {
                                        $item = $data_detail_products[$i];
                                        $detail_products_quantity = $detail_products_quantity . intval($item->cantidad) . '<br>';

                                    }
                                }?>
                                <?php if(count($recepcion["detail_products"]) > 0) { ?>
                                    <?= $detail_products_quantity; ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="col-lg-12">
                                <label class="form-control-label">PRECIO:</label>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                $detail_products_price = '';
                                if(count($recepcion["detail_products"]) > 0) {
                                    $data_detail_products = $recepcion["detail_products"];
                                    for ($i = 0; $i < sizeof($recepcion["detail_products"]); $i++) {
                                        $item = $data_detail_products[$i];
                                        $detail_products_price = $detail_products_price . number_format($item->precio_venta, 2, '.', '') . '<br>';

                                    }
                                }?>

                                <?php if(count($recepcion["detail_products"]) > 0) { ?>
                                    <?= $detail_products_price; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div><br>

                    <div class="row clearfix">
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <label class="control-label">Monto Total Trabajo: </label>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_total"
                                   name="reception_total" min="0"
                                   value="<?= number_format($recepcion["order_work"]->monto_total, 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <label class="control-label">Descuentos Anteriores: </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_discount_old"
                                   name="reception_discount_old" min="0"
                                   value="<?= number_format($totals->total_descuentos, 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <!-- <label class="control-label">Descuentos Actual: </label> -->
                            <label class="control-label">Descuentos: </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input class="form-control" type="text" step="any"
                                        id="sale_discount"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                        name="sale_discount" placeholder="0.00" value="0.00" onkeypress="onKeyPressAmount(event)"
                                        onkeyup="return view_calculate_total_reception_deliver()">        
                            <input type="hidden" step="any" class="form-control" id="reception_discount" 
                                   name="reception_discount" min="0"
                                   value="<?= number_format($calculate["discount"], 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <label class="control-label">Total a Pagar: </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_total_payment"
                                   name="reception_total_payment" min="0"
                                   value="<?= number_format($calculate["total_payment"], 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <label class="control-label">Monto Total Pagado: </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_payment_old"
                                   name="reception_payment_old" min="0"
                                   value="<?= number_format($totals->total_pagados, 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>

                    <div class="row clearfix" hidden>
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <label class="control-label">Saldo Total: </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_balance"
                                   name="reception_balance" min="0"
                                   value="<?= number_format($calculate["balance"], 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
                            <!-- <label class="control-label">Pagar para Entregar: </label> -->
                            <label class="control-label">Total : </label>
                        </div>
                        <div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <input type="number" step="any" class="form-control" id="reception_payment"
                                   name="reception_payment" min="0"
                                   value="<?= number_format($calculate["payment"], 2, '.', ''); ?>"
                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                   readonly>
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

                    <div class="row clearfix" align="center" hidden>
                        <br>
                        <br>
                        <label>¿DESEA HACER UN DESCUENTO PARA GENERAR LA VENTA?</label>
                        <br>
                        <button id="open_discount" type="button" class="btn btn-primary waves-blue">Aplicar
                            Descuentos
                        </button>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <!-- <div class="" id="view_discount" hidden>
                                    <b>Descuento Bs</b>
                                    <input class="form-control" type="number" step="any"
                                           id="sale_discount"
                                           style="background-color: rgba(255,23,19,0.25);text-align: center"
                                           name="sale_discount" placeholder="0.00" value="0.00"
                                           onkeyup="return view_calculate_total_reception_deliver()">
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="btn_generate_note_sale"  class="btn btn-success waves-effect"><i
                                    class="fa fa-save"></i> Generar Venta
                        </button>
                        <a type="button"
                                class="btn btn-danger waves-effect" href="<?= site_url('reception')?>"><i class="fa fa-times"></i> Cancelar
                        </a>
                    </div>


                </div>

            </form>
        </div>
    </div>
</div>
<script>
    var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    var change_type = <?php echo json_encode($change_type) ?>;
    if(cash_id==false || cash_aperture_id==0){
        console.log(cash_id);
        console.log(cash_aperture_id);
    }

    $(document).ready(function () {
        $('#open_discount').click(function () {
            open_verify();
        });

        $('#btn_generate_note_sale').click(function (event) {

            var monto_efectivo = parseFloat($("#monto_efectivo").val());
            // console.log(monto_efectivo);
            var sale_total = parseFloat($("#reception_payment").val());
            event.preventDefault()
            if(monto_efectivo >= sale_total){
                swal({
                    title: "Esta seguro de generar la venta?",
                    text: "Se registrará una nota de venta",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#009a37",
                    cancelButtonColor: "#ff321c",
                    confirmButtonText: "Si, generar venta!",
                    cancelButtonText: "No, cancelar",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var order_work_id = $('#order_work_id').val();

                        var reception_discount = $('#sale_discount').val();
                        if (reception_discount == '') {
                            reception_discount = 0;
                        }

                        var reception_discount_old = $('#reception_discount_old').val();
                        if (reception_discount_old == '') {
                            reception_discount_old = 0;
                        }
                        reception_discount = parseFloat(reception_discount) + parseFloat(reception_discount_old);

                        var reception_id = $('#reception_id').val();
                        ajaxStart('Registrando datos...');
                        $.ajax({
                            url: siteurl('sale/generate_sale_by_order_work_id'),
                            type: 'post',
                            data: {
                                order_work_id: order_work_id,
                                reception_id: reception_id,
                                code_reception: $('#code_reception').val(),
                                reception_total: $('#reception_total').val(),
                                reception_discount: $('#reception_discount').val(),
                                reception_total_payment: $('#reception_total_payment').val(),
                                reception_payment: $('#reception_payment').val(),
                                reception_balance: $('#reception_balance').val(),
                                reception_payment_observation: $('#reception_payment_observation').val(),
                                sale_discount: reception_discount,
                                id_reception: reception_id,
                                monto_efectivo: $('#monto_efectivo').val(),
                                monto_bs: $('#monto_bs').val(),
                                monto_sus: $('#monto_sus').val(),
                                monto_cheque: $('#monto_cheque').val(),
                                monto_tarjeta: $('#monto_tarjeta').val(),
                            },
                            dataType: 'json',
                            success: function (response) {
                                ajaxStop();
                                if (response.success === true) {
                                    if (response.sale === true) {
                                        var id = response.sale_id;
                                        var url_sale = response.url_impression;
                                        print(id, url_sale);
                                        setTimeout(function () {
                                            location.href = site_url + "reception";
                                        }, 1500);
                                    } else {
                                        swal('DATOS CORRECTOS', response.messages, 'success');
                                        update_data_table($("#reception_list"));
                                        $('#close_modal_state').click();
                                    }
                                } else if(response.cash === true){
                                    swal({
                                        title: "Caja Cerrada",
                                        text: "Caja cerrada, aperture nuevamente la caja",
                                        type: "warning",
                                        showCancelButton: false,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "Ok!",
                                        closeOnConfirm: false,
                                        closeOnCancel: true
                                    },
                                    function (isConfirm) {
                                        $.redirect(siteurl("reception"));
                                    });
                                }else if (response.login === true) {
                                    login_session();
                                } else {
                                    swal('DATOS INCORRECTOS', response.messages, 'error');
                                }
                            },
                            error: function (error) {
                                ajaxStop();
                                console.log(error.responseText);
                                // **alert('error; ' + eval(error));**
                                swal('Error', 'Error al registrar los datos.', 'error');
                            }
                        });
                    }
                    /*else {
                     swal("Cancelado", "Accion cancelada.", "error");
                     }*/
                });
            }else{
                swal(
                    'Error',
                    'Datos incorrectos, debe elegir una forma de pago, monto pago debe ser mayor o igual al saldo total', 
                    'error'
                )
            }
           
        });

    $('#monto_sus').hide();
    $('#monto_tarjeta').hide();
    $('#monto_cheque').hide();
    $('#monto_bs').hide();

    $('#check_contado').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_bs").val('');
            $('#monto_bs').show();

        }else{
            $('#monto_bs').hide();
            $("#monto_bs").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });
    $('#check_dolares').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_sus").val('');
            $('#monto_sus').show();

          
        }else{
            $('#monto_sus').hide();
            $("#monto_sus").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });

    $('#check_tarjeta').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_tarjeta").val('');
            $('#monto_tarjeta').show();

            
        }else{
            $('#monto_tarjeta').hide();
            $("#monto_tarjeta").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });
    $('#check_cheque').click(function(e) {
        var value_check=$(this).is(':checked');
        if(value_check){
            $("#monto_cheque").val('');
            $('#monto_cheque').show();

          
        }else{
            $('#monto_cheque').hide();
            $("#monto_cheque").val(0);
            // clear_efective_amount();
            calcular_total_venta_forma_pago();
        }
    });

    $("#monto_sus").keyup(function(e){
       
        calcular_total_venta_forma_pago();
    });

    $("#monto_bs").keyup(function(e){
       
        calcular_total_venta_forma_pago();
    });

    $("#monto_tarjeta").keyup(function(e){
       
        calcular_total_venta_forma_pago();
    });
    $("#monto_cheque").keyup(function(e){
        
        calcular_total_venta_forma_pago();
    });



    });

    function view_calculate_total_reception_deliver() {

        var reception_total = $('#reception_total').val();
        var discount_current = 0;

        var reception_discount = $('#sale_discount').val();
        if (reception_discount == '') {
            reception_discount = 0;
        }
        discount_current=reception_discount;
        $('#reception_discount').val(reception_discount);

        var reception_discount_old = $('#reception_discount_old').val();
        if (reception_discount_old == '') {
            reception_discount_old = 0;
        }
        reception_discount = parseFloat(reception_discount) + parseFloat(reception_discount_old);

        var reception_total_payment = reception_total - reception_discount;

        var total_payment_old = $('#reception_payment_old').val();
        if (total_payment_old == '') {
            total_payment_old = 0;
        }

        var total_balance = parseFloat(reception_total_payment) - parseFloat(total_payment_old);

        $('#reception_total_payment').val(reception_total_payment);
        $('#reception_payment').val(total_balance);
        $('#reception_balance').val(total_balance);
        $('#reception_discount').val(discount_current);

        $('#reception_total_payment').val(reception_total_payment.toFixed(2));

        $('#reception_payment').val(total_balance.toFixed(2));
        $('#reception_balance').val(total_balance.toFixed(2));
    }

    //////////////////////////////////////////////////////////
    function calcular_total_venta_forma_pago(){
        var amount_cheque= parseFloat($("#monto_cheque").val());
        var amount_tarjeta = parseFloat($("#monto_tarjeta").val());
        var amount_bs= parseFloat($("#monto_bs").val());
        var amount_sus = parseFloat($("#monto_sus").val());
        var efectivo_sus = amount_sus*parseFloat(change_type.monto_cambio_venta);

        var monto_venta = parseFloat($("#reception_payment").val());

        var total_efectivo=amount_cheque+amount_tarjeta+amount_bs+efectivo_sus;
        

        $("#monto_efectivo").val(total_efectivo.toFixed(2));
        var total_cambio=total_efectivo-monto_venta;

        $("#monto_cambio").val(total_cambio.toFixed(2));

    }

</script>