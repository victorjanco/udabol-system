<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */
?>
<div class="body">
	<fieldset>
		<legend align="left" class="legend-button">Datos del Cliente &nbsp;&nbsp;&nbsp;&nbsp; <a
				href="#modal_new_customer"
				data-toggle="modal" class="btn btn-primary"><i class="material-icons">add</i><span
					class="icon-name">Nuevo cliente</span></a><br></legend>
		<div class="row clearfix">
			<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="search_telf1" hidden>
						<input type="text" class="search_telf2" hidden>
						<input type="text" class="search_id_customer" name="customer_id" value="" hidden="">
						<input type="text" id="id_customer" name="id_customer" class="search_id_customer"
							   value="<?= isset($reception) ? $reception->cliente_id : '' ?>" hidden>

						<input type="text" id="equipo_cliente_id" name="equipo_cliente_id"
							   value="<?= isset($reception) ? $reception->equipo_cliente_id : '0' ?>" hidden>

						<input type="text" id="monto_total_reception" name="monto_total_reception"
							   value="<?= isset($reception) ? $reception->monto_total : 0 ?>" hidden>
						<input type="text" class="form-control" id="ci_customer" name="ci_customer"
							   value="<?= isset($reception) ? $reception->ci : '' ?>"
							   placeholder="Busque por CI" onkeypress=" return numbers_letters(event);">

					</div>
				</div>
			</div>
			<div class="col-md-5 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control" id="name_customer" name="name_customer"
							   value="<?= isset($reception) ? $reception->nombre : '' ?>"
							   placeholder="Busque por nombre de cliente">
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control search_telf1" id="telefono1_customer"
							   name="telefono1_customer"
							   value="<?= isset($reception) ? $reception->telefono1 : '' ?>" readonly
							   placeholder="Telefono principal">
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control search_telf2" id="telefono2_customer"
							   name="telefono2_customer"
							   value="<?= isset($reception) ? $reception->contacto : '' ?>"
							   placeholder="Contacto">
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend align="left">Datos del Dispositivo</legend>
		<div class="row clearfix">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line" style="width: 93%; float: left">
						<label class="control-label">Dispositivo *</label>
						<select class="form-control" id="devices_select" name="devices_select" required>
							<option value="">Seleccione dispositivo</option>
						</select>
					</div>
					<div style="padding-top: 5%; width: 5%;float: right">
						<a data-toggle="modal" data-target="#register_device" class="btn btn-info btn-xs"
						   title="Nuevo dispositivo"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12" hidden>
				<div class="form-group form-float">
					<div class="form-line">
						<label class="control-label">Codigo Seguridad *</label>
						<input type="text" class="form-control" id="code_segurity" name="code_segurity"
							   value="<?= isset($reception) ? $reception->codigo_seguridad : '' ?>"
							   placeholder="Escriba el codigo o patron de seguridad">
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12" hidden>
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Garantia *</label>
						<select class="form-control" id="warranty_select" name="warranty_select" required
								onchange="return onchange_select_garantia()">
							<!-- <option value="">Seleccione...</option> -->
							<option
								value="0" <?php if (isset($reception)): if ($reception->garantia == 0): echo 'selected'; endif; endif; ?>>
								Sin garantia
							</option>
							<option hidden
								value="1" <?php if (isset($reception)): if ($reception->garantia == 1): echo 'selected'; endif; endif; ?>>
								Con garantia
							</option>
							<option hidden
								value="2" <?php if (isset($reception)): if ($reception->garantia == 2): echo 'selected'; endif; endif; ?>>
								Por Verificar
							</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-5 col-sm-6 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Accesorios </label>
						<input type="text" class="form-control" id="accessories_select" name="accessories_select"
							   placeholder="Detalle los accesorios dejados"
							   value="<?= isset($reception) ? $reception->accesorio_dispositivo : '' ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<p>
						<b>Fallas Declaradas *</b>
						<a data-toggle="modal" data-target="#register_failure" class="btn btn-info btn-xs"
						   title="Nuevo Falla"><i class="fa fa-plus"></i></a>
					</p>
					<div class="form-line" style="width: 93%; float: left">
						<select style="width: 100%" class="form-control show-tick select2" id="failure_select"
								name="failure_select" multiple="multiple"
								data-placeholder="Seleccione una o varias fallas">
						</select>
					</div>
					<div style="width: 5%;float: right;">
						<a style="height: 100%" class="btn btn-success btn-xs" id="btn_refresh_select"
						   title="Actualizar"><i class="material-icons">cached</i></a>
					</div>
				</div>
			</div>
			<div id="div_color" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" hidden>
				<div class="form-group">
					<p>
						<b>Color *</b>
					</p>
					<div class="form-line" style="width: 100%; float: left">
						<select style="width: 100%" class="form-control select2" id="color_select"
								name="color_select"
								data-placeholder="Seleccione un color">
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<p>
						<b>Posible Reparacion *</b>
						<a data-toggle="modal" data-target="#register_solution" class="btn btn-info btn-xs"
						   title="Nuevo Solucion"><i class="fa fa-plus"></i></a>
					</p>
					<div class="form-line" style="width: 93%; float: left">
						<select style="width: 100%" class="form-control show-tick select2" id="solution_select"
								name="solution_select" multiple="multiple"
								data-placeholder="Seleccione una o varias soluciones">
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-9 col-sm-8 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Observacion </label>
						<input type="text" class="form-control" id="observation_select" name="observation_select"
							   placeholder="Escriba informacion adicional que concidere necesaria"
							   value="<?= isset($reception) ? $reception->observacion_recepcion : '' ?>">
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12" hidden>
				<div class="form-group">
					<p>
						<b>Referencia de la recepcion</b>
					</p>
					<div class="form-line" style="width: 93%; float: left">
						<select style="width: 100%" class="form-control select2" id="reference_select"
								name="reference_select">
						</select>
					</div>
					<div style="width: 5%;float: right;">
						<!-- <a style="height: 100%" class="btn btn-info btn-xs" id="add_reference"
						   title="Registrar referencia"><i class="material-icons">add</i></a> -->
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset hidden>
		<legend align="left">Datos del Servicio</legend>
		<div class="row clearfix" hidden>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Prioridad </label>
						<select class="form-control" id="priority" name="priority">
							<option value="1">Baja</option>
							<option value="2">Media</option>
							<option value="3">Alta</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Operador </label>
						<select class="form-control" id="operator" name="operator">
							<option value="1">Viva</option>
							<option value="2">Tigo</option>
							<option value="3">Entel</option>
							<option value="3">Otro</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12" hidden>
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Numero Ticket *</label>
						<input type="text" id="ticket" name="ticket" class="form-control"
							   value="<?= isset($reception) ? $reception->numero_ticket : '' ?>"
							   placeholder="Numero de ticket del cliente">
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Usuario Adm *</label>
						<select name="asigned_user" id="asigned_user" class="form-control select2">
							<option value="<?=get_user_id_in_session()?>"><?=get_user_name_in_session()?></option>

						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-md-4 col-sm-4 col-xs-12" >
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Tipo Servicio *</label>
						<select class="form-control" id="service_type" name="service_type">
							<option value="0">Seleccione una opcion</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Servicio *</label>
						<select class="form-control" id="service" name="service">
							<option value="0">Seleccione una opcion</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<div class="form-group">
					<div class="form-line">
						<label class="control-label">Precio [Bs.] *</label>
						<input class="form-control" id="price" name="price"
							   placeholder="Precio de venta" onkeypress="return numbers_point(event);">
						<input type="text" id="price_cost" name="price_cost" hidden>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-left: 0.5%;">
				<label><br></label>
				<button type="button" onclick="add_row()" class="btn btn-primary" id="btn_add_table"
						name="btn_add_table"><i class="fa fa-plus-square"></i> Agregar
				</button>
			</div>
		</div>
	</fieldset>
	<fieldset hidden>
		<legend align="left">Lista de Servicios Seleccionados</legend>
		<div class="row clearfix">
			<div class="col-lg-12 col-xs-12">

				<table width="100%" id="table_services" class="table-bordered">
					<thead>
					<th style="width: 30%" class="text-center">Tipo de Servicio</th>
					<th style="width: 40%" class="text-center">Servicio</th>
					<th style="width: 20%" class="text-center">Precio</th>
					<th style="width: 10%" class="text-center">Opcion</th>
					</thead>
					<tbody>
					<?php
					$total_amount_service = 0;
					if (isset($reception)) {
						$index_delete = 1;
						foreach ($detail_service as $row_detail):
							$html = '<tr id="' . $index_delete . '" data-id="' . $row_detail->servicio_id . '" data-cost="' . $row_detail->precio_servicio . '" data-price="' . $row_detail->precio_servicio . '" >';
							$html .= '<td style="padding-left: 2%; text-align: left">' . $row_detail->nombre_tipo_servicio . '</td>';
							$html .= '<td style="padding-left: 2%; text-align: left">' . $row_detail->nombre_servicio . '</td>';
							$html .= '<td style="padding-right: 8%; text-align: right">' . $row_detail->precio_servicio . '</td>';
							$html .= '<td style="text-align: center"><button type="button" class="btn btn-danger eliminar"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></button></td></tr>';
							echo $html;
							$index_delete++;
							$total_amount_service = $total_amount_service + $row_detail->precio_servicio;
						endforeach;
					}
					?>
					</tbody>
					<tfoot>
					<?php if (isset($reception)) {
						$html_foot_string = '<tr> ';
						$html_foot_string .= '<td style="text-align: right" colspan="2"><b>Total Bs.:</b></td> ';
						$html_foot_string .= '<td style="padding: 10px; text-align: right"><b>' . number_format($total_amount_service, CANTIDAD_MONTO_DECIMAL, '.', '') . '</b></td> ';
						$html_foot_string .= '</tr>';
						echo $html_foot_string;
					}
					?>
					<input type="text" value="<?=$total_amount_service?>" hidden id="total_amount_service" name="total_amount_service">
					</tfoot>
				</table>
			</div>
		</div>
		<legend align="left">Repuestos o accesorios necesarios <a data-toggle="modal" data-target="#add_new_item"
																  class="btn btn-info btn-xs" id="btn_new_item"
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
					<?php
					$total_amount_product = 0;
					if (isset($reception)) {
						$index_delete = 1;
						foreach ($detail_product as $row_detail):
							$html = '<tr data-price_product="' . $row_detail->precio_venta . '" data-price_sale="' . $row_detail->precio_venta . '" data-quantity="' . $row_detail->cantidad . '" data-product_id="' . $row_detail->producto_id . '" data-price="' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . ' id="' . $index_delete .  '" >';
							$html .= '<td>' . $row_detail->nombre_modelo . '</td>';
							$html .= '<td> <input value="' . $row_detail->producto_id . '"  name="product_id[]" hidden/>' . $row_detail->nombre_producto . '</td>';
							$html .= '<td align="right"><input value="' . $row_detail->cantidad . '" name="quantity_product[]" hidden/>' . number_format($row_detail->cantidad, '2', '.', '') . '</td>';
							$html .= '<td align="right"><input value="' . $row_detail->precio_costo . '" name="price_product[]" hidden/><input value="' . $row_detail->precio_venta . '" name="price_sale[]" hidden/>' . number_format($row_detail->precio_venta, 2, '.', '') . '</td>';
							$html .= '<td align="right">' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . '</td>';
							$html .= '<td style="text-align: center"><button type="button" class="btn btn-danger eliminar"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></button></td>';
							$html .= '</tr>';
							echo $html;
							$index_delete++;
							$total_amount_product = $total_amount_product + ($row_detail->precio_venta * $row_detail->cantidad);
						endforeach;
					}
					$total_reception=$total_amount_service+$total_amount_product;
					?>
					</tbody>
					<tfoot>
					<?php if (isset($reception)) {
						$html_foot_string = '<tr> ';
						$html_foot_string .= '<td style="text-align: right" colspan="4"><b>Total Bs.:</b></td> ';
						$html_foot_string .= '<td style="padding: 10px; text-align: right"><b>' . number_format($total_amount_product, CANTIDAD_MONTO_DECIMAL, '.', '') . '</b></td> ';
						$html_foot_string .= '</tr>';
						echo $html_foot_string;
					}
					?>
					<input type="text" id="total_amount_product" value="<?=$total_amount_product?>" name="total_amount_product" hidden>
					</tfoot>
				</table>
			</div>
		</div>
	</fieldset>
	<br>
	<fieldset hidden>
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
						   >
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
						   style="background-color: rgb(235, 235, 228);text-align: right;"
						   >
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
				class="fa fa-times"></i> Cancelar y
			Salir</a>
	</div>
</div>
<script>
	$(document).ready(function () {
		setTimeout(function () {
			calculate_total_reception();
		}, 500);
	});
</script>

<?php
if (!isset($reception)) {
	?>
	<script>
		$(function () {
			$('#modal_search_customer').modal({
				show: true,
				backdrop: 'static'
			});
		})
	</script>
<?php } ?>
