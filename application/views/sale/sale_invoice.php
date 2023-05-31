<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Lista de Facturas</h2>
			</div>
			<div class="body">
				<div class="card-content">
					<div class="row clearfix">

						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<div class="form-line" style="width: 93%; float: left">
									<label class="control-label">Fecha Inicio:</label>
									<input type="date" class="form-control" name="filter_date_start"
										   id="filter_date_start">
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<div class="form-line" style="width: 93%; float: left">
									<label class="control-label">Fecha Fin:</label>
									<input type="date" class="form-control" name="filter_date_end"
										   id="filter_date_end">
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-4 col-xs-12">
							<div class="form-group">
								<div class="form-line" style="width: 93%; float: left">
									<label class="control-label">Nro. de Factura:</label>
									<input type="number" class="form-control" name="filter_sale_number"
										   id="filter_sale_number" placeholder="Nro de factura. ingresar solo numeros">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table id="list_sale_invoice" class="table table-striped table-bordered dataTable ">
						<thead>
						<th class="text-center">ID</th>
						<th class="text-center"><b>Usuario</b></th>
						<th class="text-center"><b>Fecha</b></th>
						<th class="text-center"><b>Nro Factura</b></th>
						<th class="text-center"><b>Nit</b></th>
						<th class="text-center"><b>Cliente</b></th>
						<th class="text-center"><b>Monto Venta</b></th>
						<th class="text-center"><b>Descuento</b></th>
						<th class="text-center"><b>Total</b></th>
						<th class="text-center"><b>Opciones</b></th>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Formulario Modal para ver la venta antes de facturar-->
<div class="modal fade" id="modal_view_for_invoice" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header cabecera_modal">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
						style="color: white;font-size: 15pt">&times;
				</button>
				<h4 class="modal-title titulo_modal" id="defaultModalLabel">Verificacion de Datos de Venta</h4>
			</div>
			<div class="modal-body">
				<input type="text" id="id_sale" name="id_sale" hidden>
				<div class="row clearfix" id="sale_data">
				</div>
				<div class="row clearfix" align="center">
					<label>DATOS DE SU CLIENTE PARA FACTURAR</label>
				</div>
				<div class="row clearfix">
					<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="form-line">
								<input type="text" id="id_customer" name="id_customer" hidden>

								<b>Nit Cliente</b>
								<input type="number" name="nit" id="nit"
									   class="form-control"
									   placeholder="Ingrese el nit del cliente"
									   value="">
							</div>
						</div>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="form-line">
								<b>Nombre Cliente</b>
								<input type="text" class="form-control "
									   name="nombre_factura" id="nombre_factura"
									   placeholder="Ingrese el nombre del Cliente"
									   value="">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a type="btn" id="btn_generate_invoice" class="btn btn-success waves-effect"><i
						class="fa fa-file-text"></i> Facturar
				</a>
				<button id="close_modal_edit_service" type="button" class="btn btn-danger waves-effect"
						data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>

<!--Formulario Modal para ver la venta antes de facturar-->
<div class="modal fade" id="modal_view_sale" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header cabecera_modal">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
						style="color: white;font-size: 15pt">&times;
				</button>
				<h4 class="modal-title titulo_modal" id="defaultModalLabel" align="center">Informacion de Venta</h4>
			</div>
			<div class="modal-body">
				<input type="text" id="id_sale" name="id_sale" hidden>
				<div class="row clearfix" id="sale_view_data"></div>
				<div class="modal-footer">
					<button id="close_modal_edit_service" type="button" class="btn btn-danger waves-effect"
							data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url('jsystem/sale.js'); ?>"></script>
<script>
	$(document).ready(function () {
		get_sale_invoice_list();

		/*  Evento change de filtro por fecha de rececpion  */
		$("#filter_date_start").on('change', function () {
			console.log($("#filter_date_start").val());
			get_sale_invoice_list();
		});

		$("#filter_date_end").on('change', function () {
			get_sale_invoice_list();
		});

		/*  Evento change de filtro por codigo de recepcion  */
		$("#filter_sale_number").keyup(function () {
			get_sale_invoice_list();
		});
	});
</script>
