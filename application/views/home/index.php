<div class="card">
	<!--para dosificaciones-->
	<!-- <div class="header">
		<h2>DOSIFICACIONES.</h2>
		<button type="button" id="updated_branch_office_inventory_all" hidden>
			Acutalizar inventario
		</button>
	</div> -->
	<div class="body">
		<div class="row" hidden>
			<!--para dosificaciones por dias-->
			<?php
			if (isset($activas) && !empty($activas)) {
				foreach ($activas as $dosage_active_list) :
					ini_set("date.timezone", "America/La_Paz");
					$fecha_actual = date('Y-m-d');
					$current_date = new DateTime($fecha_actual);
					$limit_date = new DateTime($dosage_active_list->fecha_limite);
					$date = $current_date->diff($limit_date);
					?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<!--  <div class="info-box info-box bg-light-green hover-expand-effect hover-expand-effect">-->
						<div
							class="info-box <?= ($date->format('%a') == 0) ? 'info-box info-box bg-orange hover-expand-effect hover-expand-effect' : 'info-box info-box bg-light-green hover-expand-effect hover-expand-effect' ?> hover-expand-effect">
							<div class="icon">
								<i class="material-icons">beenhere</i>
							</div>
							<div class="content">
								<div
									class="text"><?= $dosage_active_list->nombre_actividad . ' - ' . $dosage_active_list->marca . '/' . $dosage_active_list->serial ?></div>
								<div class="number">
									<?= 'Le queda ' . (int)$date->format('%a') . ' Dias de Dosificacion' ?>
								</div>
							</div>
						</div>

					</div>
				<?php endforeach;
			} ?>
			<!--para dosificaciones vencidas-->
			<?php
			if (isset($caducadas) && !empty($caducadas)) {
				foreach ($caducadas as $dosage_expired_list) :

					$number = $dosage_expired_list->vencidas;
					?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div
							class="info-box <?= ($number >= 0) ? 'info-box info-box bg-orange hover-expand-effect hover-expand-effect' : 'info-box info-box bg-light-green hover-expand-effect hover-expand-effect' ?> hover-expand-effect">
							<div class="icon">
								<i class="material-icons">notifications</i>
							</div>
							<div class="content">
								<div class="text"></div>
								<div class="number">
									<?= 'Tiene ' . $dosage_expired_list->vencidas . ' Dosificaciones vencidas'; ?>
								</div>
							</div>
						</div>

					</div>
				<?php endforeach;
			} ?>
			<!--para dosificaciones inactivas-->
			<?php
			if (isset($inactivas) && !empty($inactivas)) {
				foreach ($inactivas as $dosage_disable_list) :

					$number = $dosage_disable_list->dosage_inactivas;
					?>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div
							class="info-box <?= ($number >= 0) ? 'info-box info-box bg-orange hover-expand-effect hover-expand-effect' : 'info-box info-box bg-light-green hover-expand-effect hover-expand-effect' ?> hover-expand-effect">
							<div class="icon">
								<i class="material-icons">trending_down</i>
							</div>
							<div class="content">
								<div class="text"></div>
								<div class="number">
									<?= 'Tiene ' . $dosage_disable_list->dosage_inactivas . ' Dosificaciones Inactivas'; ?>
								</div>
							</div>
						</div>

					</div>
				<?php endforeach;
			} ?>
		</div>
	</div>

	<!--para dosificaciones Vencidas-->
	<?php
	if (isset($inactivas) && empty($inactivas)) {
		foreach ($inactivas as $dosage_list) : ?>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div
					class="info-box <?= ($dosage_list->fecha_limite < $current_date) ? 'info-box bg-orange hover-expand-effect' : 'info-box info-box bg-light-green hover-expand-effect hover-expand-effect' ?> hover-expand-effect">
					<div class="icon">
						<i class="material-icons">forum</i>
					</div>
					<div class="content">
						<div class="text">DOSIFICACIONES POR VENCER</div>
						<div class="number">
							<?= $dosage_list->fecha_limite; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach;
	} ?>

	<!--ordenes de trabajo-->
	
	<!--inventario-->
	<div class="header">
		<h2>INVENTARIO.</h2>
	</div>
	<div class="body">
		<div class="row ">
			<!--para stock minimos-->
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div
					class="info-box <?= ($minimum_stock->stock_minimo > 0) ? 'bg-red' : 'bg-green' ?> hover-expand-effect">
					<div class="icon">
						<i class="material-icons">assignment_returned</i>
					</div>
					<div class="content">
						<div class="text">PRODUCTO STOCK MINIMO</div>
						<div class="number countTo" data-from="0" data-to="1000" data-speed="1000"
							 data-fresh-interval="20">
							<?= $minimum_stock->stock_minimo ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<!-- <div class="header">
         <h2>DEUDAS.</h2>
     </div> -->
     <div class="body" hidden>
         <div class="row">
		 	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div
					class="info-box <?= ($ventas_credito_vencidas[0]->vencidas > 0) ? 'bg-red' : 'bg-green' ?> hover-expand-effect">
					<div class="icon">
						<i class="material-icons">assignment_returned</i>
					</div>
					<div class="content">
						<div class="text">VENTAS CREDITO VENCIDAS</div>
						<div class="number countTo" data-from="0" data-to="1000" data-speed="1000"
							 data-fresh-interval="20">
							<?= $ventas_credito_vencidas[0]->vencidas ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div
					class="info-box <?= ($ventas_credito_por_expirar[0]->vencidas > 0) ? 'bg-orange' : 'bg-green' ?> hover-expand-effect">
					<div class="icon">
						<i class="material-icons">assignment_returned</i>
					</div>
					<div class="content">
						<div class="text">VENTAS CREDITO QUE EXPIRAN EN 10 DIAS</div>
						<div class="number countTo" data-from="0" data-to="1000" data-speed="1000"
							 data-fresh-interval="20">
							<?= $ventas_credito_por_expirar[0]->vencidas ?>
						</div>
					</div>
				</div>
			</div>
         </div>
     </div>

	<div class="modal fade" id="modal_printer_invoice" role="dialog">
		<div class="modal-dialog modal-small" role="document">
			<div class="modal-content">
				<div class="modal-header cabecera_modal">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
							style="color: white;font-size: 15pt">&times;
					</button>
					<h4 class="modal-title titulo_modal" id="defaultModalLabel">Impresoras para la Venta</h4>
				</div>
				<form id="frm_printer_invoice">
					<div class="modal-body">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="form-control-label">Seleccione Impresora</label>
									<select id="printer_id" name="printer_id" class="form-control">
										<?php
										if (isset($list_printer)) {
											foreach ($list_printer as $row_printer) { ?>
												<option
													value="<?= $row_printer->impresora_id; ?>"><?= $row_printer->marca . ' / ' . $row_printer->serial; ?></option>
											<?php }
										} else { ?>
											<option value="0">no existe_impresora para facturar</option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div align="center">
						<button type="submit" id="btn_printer" class="btn btn-success waves-effect"><i
								class="fa fa-check-circle"></i>
							Guardar
						</button>
						<button id="close_modal_printer" type="button" class="btn btn-danger waves-effect"
								data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
						</button>
						<br>
						<br>

					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- <div class="modal fade" id="modal_select_cash" role="dialog">
        <div class="modal-dialog modal-small" role="document">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">&times;
                    </button>
                    <h4 class="modal-title titulo_modal" id="defaultModalLabel">Apertura de Caja</h4>
                </div>
                <form id="frm_select_cash" action="<?= site_url('cash/check_cash') ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-success alert-dismissible" style="text-align: center">
                                    <h4><i class="icon fa fa-info"></i>Aviso!</h4>
                                    Seleccione la caja que desea utilizar para realizar ventas.
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-control-label">Seleccione Caja</label>
                                    <select id="cash_id" name="cash_id" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div align="center">
                        <button type="submit" id="btn_select_cash" class="btn btn-success waves-effect"><i
                                    class="fa fa-check-circle"></i>
                            Guardar
                        </button>
                        <button id="close_modal_select_cash" type="button" class="btn btn-danger waves-effect"
                                data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
                        </button>
                        <br>
                        <br>

                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- <div class="modal fade" id="modal_cash_aperture" role="dialog">
        <div class="modal-dialog modal-small" role="document">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">&times;
                    </button>
                    <h4 class="modal-title titulo_modal" id="defaultModalLabel">Apertura de Caja</h4>
                </div>
                <form id="frm_cash_aperture" action="<?= site_url('cash/cash_aperture') ?>" method="post">
                    <div class="modal-body">
                        <div style="overflow-y: scroll; height: 100%; width: 100%;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="alert alert-success alert-dismissible" style="text-align: center">
                                        <h4><i class="icon fa fa-info"></i>Aviso!</h4>
                                        Aperturar la caja con un monto.
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Caja: </label>
                                        <div class="form-line">
                                            <input type="text" id="aperture_cash_id" name="aperture_cash_id" hidden>
                                            <input type="text" class="form-control" id="aperture_name_cash"
                                                   name="aperture_name_cash" title="Caja de venta" disabled/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg6 col-md-6 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label>Monto Apertura Bs: </label>
                                        <div class="form-line">
                                            <input type="text" step="any" class="form-control" id="aperture_amount_bs"
                                                   name="aperture_amount_bs" value="0.00" title="Monto de apertura"
                                                   onkeypress="return numbers_point(event)" required/>
                                        </div>
                                    </div>
								</div>
								<div class="col-lg6 col-md-6 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label>Monto Apertura Sus: </label>
                                        <div class="form-line">
                                            <input type="text" step="any" class="form-control" id="aperture_amount_sus"
                                                   name="aperture_amount_sus" value="0.00" title="Monto de apertura"
                                                   onkeypress="return numbers_point(event)" required/>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                           
                        </div>
                    </div>
                    <div align="center">
                        <button type="submit" id="btn_cash_aperture" class="btn btn-success waves-effect"><i
                                    class="fa fa-check-circle"></i>
                            Guardar
                        </button>
                        <button id="close_modal_cash_aperture" type="button" class="btn btn-danger waves-effect"
                                data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
                        </button>
                        <br>
                        <br>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

</div>
<script>
	$(document).ready(function () {
		// verify_printer();

		$('#btn_printer').click(function (event) {
			event.preventDefault();
			select_printer();
		});
		
		$('#updated_branch_office_inventory_all').click(function (event) {
			event.preventDefault();
			
			updated_branch_office_inventory_stock_global();
		});

		
		open_printer();
		// get_cash_enable();
	})

	function updated_branch_office_inventory_stock_global(){
		ajaxStart('Actualizando el inventario, por favor espere');
		$.ajax({
			dataType: 'json',
			url: siteurl('inventory/updated_branch_office_inventory_stock_global'),
			async: false,
			type: 'post',
			data: {},
			success: function (response) {
				ajaxStop();
				console.log(response);
			},
			error: function (error) {
				ajaxStop();
				console.log(error.responseText);

				swal('Error', 'Error al consultar los datos.', 'error');
			}
		});
	}
	

	function open_printer() {
		if (number_printer>0){
			if (printer_invoice==''){
				$('#frm_printer_invoice')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
				$(".modal-error").empty();
				$('#modal_printer_invoice').modal({
					show: true,
					backdrop: 'static'
				});
			}
		}
	}

	
</script>




