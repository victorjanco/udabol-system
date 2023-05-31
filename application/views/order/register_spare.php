<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */
$url_action = 'order_work/register_spare';
$titulo = "AGREGAR REPUESTOS: " . $reception->codigo_trabajo;
$hidden = '';
/*if (isset($order_work)) {
    if (1 == 1) {
        $hidden = 'readonly disabled';
    }else{
        $hidden = 'required';
    }
}*/
$service_total_amount=0;
$producto_total_amount=0;
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"><?= $titulo ?></h4>
            </div>
            <form id="frm_register_spare" name="frm_register_spare" action="<?= site_url($url_action) ?>"
                  method="post">
                <input type="text" name="id_reception" id="id_reception"
                       value="<?= isset($reception) ? $reception->id : '' ?>" hidden>
                <input type="text" name="id_order_work" id="id_order_work"
                       value="<?= isset($reception) ? $reception->orden_trabajo_id : '' ?>" hidden>
                <input type="text" id="diagnosed_state"
                       value="<?= isset($order_work) ? 1 : '' ?>" hidden>
                <input type="text" id="reception_state"
                       value="<?= isset($order_work) ? $order_work->observacion : '' ?>" hidden>

                <div class="body">
                    <fieldset>
                        <legend align="left">Datos del cliente &nbsp;&nbsp;&nbsp;&nbsp;</legend>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="id_customer" name="id_customer"
                                               value="<?= isset($reception) ? $reception->cliente_id : '' ?>" hidden>

                                        <input type="text" id="equipo_cliente_id" name="equipo_cliente_id"
                                               value="<?= isset($reception) ? $reception->equipo_cliente_id : '0' ?>"
                                               hidden>

                                        <input type="text" id="monto_total_reception" name="monto_total_reception"
                                               value="<?= isset($reception) ? $reception->monto_total : 0 ?>" hidden>
                                        <input type="text" class="form-control" id="ci_customer" name="ci_customer"
                                               value="<?= isset($reception) ? $reception->ci : '' ?>"
                                               placeholder="Busque por CI" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="name_customer" name="name_customer"
                                               value="<?= isset($reception) ? $reception->nombre : '' ?>" disabled
                                               placeholder="Busque por nombre de cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="telefono1_customer"
                                               name="telefono1_customer"
                                               value="<?= isset($reception) ? $reception->telefono1 : '' ?>" disabled
                                               placeholder="Telefono principal">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="telefono2_customer"
                                               name="telefono2_customer"
                                               value="<?= isset($reception) ? $reception->telefono2 : '' ?>" disabled
                                               placeholder="Telefono secundario">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend align="left">Datos del peritaje</legend>

                        <div class="row clearfix">
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Dispositivo *</label>
                                        <select class="form-control" id="devices_select" name="devices_select" readonly disabled>
                                            <option value="0">Seleccione dispositivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <label class="control-label">Codigo Seguridad *</label>
                                        <input type="text" class="form-control" id="code_segurity" name="code_segurity"
                                               value="<?= isset($reception) ? $reception->codigo_seguridad : '' ?>"
                                               placeholder="Escriba el codigo o patron de seguridad" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" >
                                        <label class="control-label">Garantia *</label>
                                        <select class="form-control" id="warranty_select" name="warranty_select" readonly disabled>
                                            <?php if ($reception->garantia == 1) { ?>
                                                <option value="1" >Con garantia</option>
                                                <option value="0">Sin garantia</option>
                                            <?php } else { ?>
                                                <option value="0">Sin garantia</option>
                                                <option value="1">Con garantia</option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label">Accesorios </label>
                                        <input type="text" class="form-control" id="accessories_select"
                                               name="accessories_select"
                                               placeholder="Detalle los accesorios dejados"
                                               value="<?= isset($reception) ? $reception->accesorio_dispositivo : '' ?>" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <p>
                                        <b>Fallas Declaradas en Recepcion *</b>
                                    </p>
                                    <select style="width: 100%" class="form-control show-tick select2" id="failure_select_reception"
                                            name="failure_select_reception[]" multiple="multiple"
                                            data-placeholder="Seleccione una o varias fallas" disabled>
                                    </select>
                                </div>
                            </div>
                            <div id="div_color" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" hidden>
                                <div class="form-group">
                                    <p>
                                        <b>Color *</b>
                                    </p>
                                    <div class="form-line" style="width: 100%; float: left">
                                        <select style="width: 100%" class="form-control select2" id="color_select"
                                                name="color_select" disabled>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <p>
                                        <b>Posible Reparacion en Recepcion *</b>
                                    </p>
                                    <select style="width: 100%" class="form-control show-tick select2" id="solution_select_reception"
                                            name="solution_select_reception[]" multiple="multiple"
                                            data-placeholder="Seleccione una o varias soluciones" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <p>
                                        <b>Fallas Detectadas por el Tecnico *</b>
                                    </p>
                                    <select style="width: 100%" class="form-control show-tick select2"
                                            id="failure_select"
                                            name="failure_select[]" multiple="multiple"
                                            data-placeholder="Seleccione una o varias fallas" readonly disabled>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <p>
                                        <b>Reparacion por el Tecnico*</b>
                                    </p>
                                    <select style="width: 100%" class="form-control show-tick select2"
                                            id="solution_select"
                                            name="solution_select[]" multiple="multiple"
                                            data-placeholder="Seleccione una o varias soluciones" readonly disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label">Observacion </label>
                                        <textarea type="text" class="form-control" id="observation_select"
                                               name="observation_select"
                                                  placeholder="Escriba informacion adicional que concidere necesaria" readonly disabled><?= isset($order_work) ? $order_work->observacion : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label">Usuario*: </label>
                                        <select name="user" id="user" class="form-control select2" readonly disabled>
                                            <?php if (isset($list_users)) {

                                                foreach ($list_users as $row_user):
                                                    $select_user = "";
                                                    if (isset($order_work)) {
                                                        if ($order_work->asignado_usuario_id == $row_user->id) {
                                                            $select_user = "selected";
                                                        }

                                                    }
                                                    $html = '<option value="' . $row_user->id . '" ' . $select_user . '>' . $row_user->usuario . ' - ' . $row_user->nombre . '</option>';
                                                    echo $html;
                                                endforeach;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend align="left">Servicios de manos de obra <a data-toggle="modal"
                                                                           data-target="#add_type_service"
                                                                           class="btn btn-info btn-xs"
                                                                           title="Nuevo dispositivo"><i
                                        class="fa fa-plus"></i></a></legend>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-xs-12">
                                <table width="100%" id="table_services_work" class="table-bordered">
                                    <thead>
                                    <th style="width: 30%" class="text-center">Tipo de servicio</th>
                                    <th style="width: 40%" class="text-center">Servicio</th>
                                    <th style="width: 20%" class="text-center">Precio</th>
                                    <th style="width: 10%" class="text-center">Opciones</th>
                                    </thead>
                                    <tbody>
                                    <?php if (isset($reception)) {
                                        $index_delete = 1;
                                        foreach ($detail_service as $row_detail):
                                            $html = '<tr id="' . $index_delete . '" data-id="' . $row_detail->servicio_id . '" data-cost="' . $row_detail->precio_servicio . '" data-price="' . $row_detail->precio_servicio . '" >';
                                            $html .= '<td style="padding-left: 2%; text-align: left">' . $row_detail->nombre_tipo_servicio . '</td>';
                                            $html .= '<td style="padding-left: 2%; text-align: left"><input  value="' . $row_detail->servicio_id . '" name="serviceid[]" hidden/>' . $row_detail->nombre_servicio . '</td>';
                                            $html .= '<td class="text-right"><input  value="' . $row_detail->precio_servicio . '" name="serviceprice[]" hidden/>' . $row_detail->precio_servicio . '</td>';
                                            $html .= '<td style="text-align: center"><input type="text" name="serviceobservation[]" value="" hidden><a type="button" class="btn btn-danger elimina_service"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td></tr>';
                                            echo $html;
                                            $index_delete++;
                                            $service_total_amount = $service_total_amount + $row_detail->precio_servicio;
                                        endforeach;
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td style="text-align: right" colspan="2"><b>Total Bs.:</b></td>
                                        <td style="padding: 10px; text-align: right"><b><?php if (isset($reception)) {
                                                    echo number_format($service_total_amount, CANTIDAD_MONTO_DECIMAL, '.', '');
                                                } else {
                                                    echo '0.00';
                                                } ?></b></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend align="left">Repuestos o accesorios necesarios <a data-toggle="modal"
                                                                                  data-target="#add_new_item"
                                                                                  class="btn btn-info btn-xs"
                                                                                  title="Nuevo dispositivo"><i
                                        class="fa fa-plus"></i></a></legend>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-xs-12">
                                <table width="100%" id="table_services_product" class="table-bordered ">
                                    <thead>
                                    <th style="width: 20%" class="text-center">Modelo</th>
                                    <th style="width: 20%" class="text-center">Repuesto</th>
                                    <th style="width: 20%" class="text-center">Cantidad</th>
                                    <th style="width: 15%" class="text-center">Precio</th>
                                    <th style="width: 15%" class="text-center">Total</th>
                                    <th style="width: 10%" class="text-center">Opciones</th>
                                    </thead>
                                    <tbody>
                                    <?php if (isset($reception)) {
                                        $index_delete = 1;
                                        foreach ($detail_product as $row_detail):
                                            $html = '<tr data-price="' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . ' " id="' . $index_delete . '" data-price_product="' . $row_detail->precio_venta . '" data-quantity="' . $row_detail->cantidad . '" data-product_id="' . $row_detail->producto_id . '" >';
                                            $html .= '<td>' . $row_detail->nombre_modelo . '</td>';
                                            $html .= '<td><input value="' . $row_detail->producto_id . '"  name="product_id[]" hidden/>' . $row_detail->nombre_producto . '</td>';
                                            $html .= '<td align="right"><input value="' . $row_detail->cantidad . '" name="quantity_product[]" hidden/>' . number_format($row_detail->cantidad, '2', '.', '') . '</td>';
                                            $html .= '<td align="right"><input value="' . $row_detail->precio_costo . '" name="price_product[]" hidden/><input value="' . $row_detail->precio_venta . '" name="price_sale[]" hidden/>' . number_format($row_detail->precio_venta, 2, '.', '') . '</td>';
                                            $html .= '<td align="right">' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . '</td>';
                                            $html .= '<td style="text-align: center"><a class="elimina btn-danger btn"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td>';
                                            $html .= '</tr>';
                                            echo $html;
                                            $index_delete++;
                                            $producto_total_amount = $producto_total_amount + ($row_detail->precio_venta * $row_detail->cantidad);
                                        endforeach;

										$total_reception=$service_total_amount+$producto_total_amount;
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td style="text-align: right" colspan="4"><b>Total Bs.:</b></td>
                                        <td style="padding: 10px; text-align: right"><b><?php if (isset($reception)) {
                                                    echo number_format($producto_total_amount, CANTIDAD_MONTO_DECIMAL, '.', '');
                                                } else {
                                                    echo '0.00';
                                                } ?></b></td>
                                    </tr>
                                    </tfoot>
									<input type="text" id="total_amount_service" name="total_amount_service" value="<?=$service_total_amount?>" hidden>
									<input type="text" id="total_amount_product" name="total_amount_product" value="<?=$producto_total_amount?>" hidden>
                                </table>
                            </div>
                        </div>
                    </fieldset>
					<br>
					<fieldset>
						<div id="div_pagos_anticipados">
                            <!-- <legend align="left">Pagos anticipados</legend> -->
                            <legend align="left"> </legend>
							<div class="row clearfix">
								<div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
									<label class="control-label">Total Recepcion: </label>
								</div>
								<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
									<input type="number" step="any" class="form-control" id="reception_total"
										   name="reception_total" onkeyup="calculate_total_reception()" min="0"
										   placeholder="0.00"
										   value="<?=isset($reception)?$total_reception:'0.00'?>"
										   style="background-color: rgb(235, 235, 228);text-align: right;"
										   readonly>
								</div>
							</div>
							<div class="row clearfix">
								<div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
									<label class="control-label">Descuento: </label>
								</div>
								<div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
									<input type="number" step="any" class="form-control" id="reception_discount"
										   name="reception_discount" onkeyup="calculate_total_reception()"
										   placeholder="0.00"
										   value="<?=isset($reception_payments)?$reception_payments->total_descuentos:''?>"
										   style="background-color: rgb(235, 235, 228);text-align: right;"
										   readonly>
								</div>
							</div>
							<div class="row clearfix">
								<div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
									<label class="control-label">Total a pagar: </label>
								</div>
								<div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
									<input type="number" step="any" class="form-control" id="reception_total_payment"
										   name="reception_total_payment"
										   placeholder="0.00"
										   value="0.00"
										   style="background-color: rgb(235, 235, 228);text-align: right;"
										   readonly>
								</div>
							</div>
							<div class="row clearfix" hidden>
								<div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
									<label class="control-label">Pago anticipado: </label>
								</div>
								<div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
									<input type="number" step="any" class="form-control" id="reception_payment"
										   name="reception_payment" onkeyup="calculate_total_reception()" min="0"
										   placeholder="0.00"
										   value="<?=isset($reception_payments)?$reception_payments->total_pagados:''?>"
										   style="background-color: rgb(235, 235, 228);text-align: right; "
										   readonly>
								</div>
							</div>
							<div class="row clearfix" hidden>
								<div class="col-lg-offset-4 col-lg-2 col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-4 col-xs-12">
									<label class="control-label">Saldo: </label>
								</div>
								<div class=" col-lg-3 col-md-4 col-sm-4 col-xs-12">
									<input type="number" step="any" class="form-control" id="reception_balance"
										   name="reception_balance"
										   placeholder="0.00"
										   value="0.00"
										   style="background-color: rgb(235, 235, 228);text-align: right;"
										   readonly>
								</div>

							</div>
						</div>
					</fieldset>
                    <div class="modal-footer">
                        <button id="btn_accion_user" class="btn btn-success waves-effect no-modal" type="submit"><i
                                    class="fa fa-save"></i> Guardar
                        </button>
                        <a href="<?= site_url('reception') ?>" class="btn btn-danger waves-effect" type="submit"><i
                                    class="fa fa-times"></i> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- //<editor-fold desc=" MODALS: Buscar Cliente, agregar mano de obra"> -->
<div class="modal fade" id="add_type_service" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_service">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Datos del Servicio de Mano de Obra</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="service_work">Servicio </label>
                                        <select style="width: 100%" class="form-control select2" id="service_work" name="service_work">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($services)) {
                                                foreach ($services as $row_service):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_service->id . '">' . $row_service->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="gama_work">Gama*: </label>
                                        <select style="width: 100%" class="form-control select2" id="gama_work" name="gama_work">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_work">Precio*:</label>
                                        <input type="text" class="form-control" name="price_work" id="price_work"
                                               placeholder="precio del servicio"
                                               onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="order_work_observation">Observacion *</label>
                                        <input type="text" class="form-control" name="order_work_observation"
                                               id="order_work_observation" placeholder="ingrese una descripcion"
                                               onkeypress="return alphabets_numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_add_table"
                            name="btn_add_table"><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="add_new_item" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_product">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Agregar repuesto </h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
							<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<div class="form-line">
										<label class="control-label" for="brand_product">Almacen*: </label>
										<div class="form-line">
											<select style="width: 100%" class="form-control select2" id="warehouse_id" name="warehouse_id">
												<?php if (isset($list_warehouse)) {
													foreach ($list_warehouse as $row_warehouse):
														$html_option = '';
														$html_option .= '<option value="' . $row_warehouse->id . '">' . $row_warehouse->nombre . '</option>';
														echo $html_option;
													endforeach;
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="brand_product">Marca*: </label>
                                        <div class="form-line">
                                        <select style="width: 100%" class="form-control select2" id="brand_product" name="brand_product">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($list_brand)) {
                                                foreach ($list_brand as $row_brand):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_brand->id . '">' . $row_brand->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="model_product">Modelo*: </label>
                                        <select style="width: 100%" class="form-control select2" id="model_product" name="model_product">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="producto_order_work">Repuesto*:</label>
                                        <input type="text" name="product_selected" id="product_selected" hidden>
                                        <input type="text" class="form-control" name="producto_order_work"
                                               id="producto_order_work" placeholder="Nombre del producto"
                                        <!--onkeypress="return alphabets_numbers_point(event)-->">
                                        <!--    <input id="prueba">-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio costo*:</label>
                                        <input type="text" class="form-control" name="price_product" id="price_product"
                                               placeholder="precio unitario" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio venta*:</label>
                                        <input type="text" class="form-control" name="price_sale" id="price_sale"
                                               placeholder="precio venta" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="quantity_product">Cantidad*:</label>
                                        <input type="text" class="form-control" name="quantity_product"
                                               id="quantity_product" placeholder="ingrese cantidad"
                                               onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
	$(document).ready(function () {
		setTimeout(function () {
			calculate_total_reception();
		}, 500);
	});
</script>
<script type="text/javascript">

</script>
<script src="<?= base_url('jsystem/order.js') ?>"></script>

