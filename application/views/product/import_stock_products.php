<?php
/**
 *
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato
 * Date: 26/06/2019
 * Time: 05:37 PM
 */

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="card">
		<div class="panel-heading cabecera_frm ">
			<div style="">
				<h2 class="panel-title titulo_frm">IMPORTAR STOCK PRODUCTOS EXCEL</h2>
			</div>
			<div><label style="text-align: right; color: white;font-size: 12pt">CANTIDAD</label>
			</div>
		</div>
		<form enctype="multipart/form-data" id="frm_import_stock_products" method="post">

			<div class="panel">
				<div class="panel-body">
					<div class="card-content">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="alert alert-success alert-dismissible" style="font-size: 12pt">
									<h4><i class="icon fa fa-info"></i> AVISO IMPORTANTE!!</h4>
									Asegurarse que que el archivo .excel tenga las siguientes condiciones: <br>
									* Los registros tienen que estar en letras mayusculas. <br>
									* Los registros NO tienen que estar con tildes. <br>
									* Asegurarse que no haya error ortográfico. <br>
									* Evitar en usar letras o caracteres especiales.<br>
									<h4>PASOS </h4>
									* Exportar productos.<br>
									* Modificar el excel exportado, adicionar la columna de cantidad.<br>
									* Seleccionar el Excel moficado.
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Almacen:</label>
										<div class="form-line">
											<select style="width: 100%" id="warehouse" name="warehouse"
													class="form-control select2">
												<option value="0">Todos</option>
												<?php foreach ($list_warehouse as $warehouse_list) : ?>
													<option value="<?= $warehouse_list->id; ?>"><?= $warehouse_list->nombre; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Importar: </label>
										<input type="file" class="form-control" id="excel" name="excel" required/>
										<br>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 ol-xs-12" align="center">
								<button type="submit" class="btn btn-success waves-effect"><i
										class="fa fa-save"></i> Importar y Guardar
								</button>
								<a href="<?= site_url('product/index') ?>" class="btn btn-danger waves-effect"
								   type="submit"><i
										class="fa fa-times"></i> Cancelar y
									Salir</a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</form>
	</div>
</div>

<script>
	$(document).ready(function () {

		$('#frm_import_stock_products').on('submit', function (event) {
			event.preventDefault();
			ajaxStart('Importando archivos, espere por favor...');
			$.ajax({
				url: siteurl('product/save_import_stock_products_warehouse'),
				method: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					ajaxStop();
					$('#file_excel').val('');
					if (data === '1') {
						swal('Correcto', 'Se importo los datos correctamente!!', 'success');
					} else {
						swal('Error', 'NO se pudo importar los datos!!', 'error');
					}
				}
			});
		});
	});

</script>
