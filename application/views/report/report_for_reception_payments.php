<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 20/09/2019
 * Time: 12:00 PM
 */

?>

<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Reporte Pagos Recepcion</h2>
			</div>
			<div class="body">
				<div class="card-content">
					<div class="row clearfix">
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Nro. Recepcion:</label>
								<div class="form-line">
									<input type="number" id="number_reception" name="number_reception"
										   class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Usuario:</label>
								<div class="form-line">
									<select style="width: 100%" id="user" name="user"
											class="form-control select2">
										<?php /*if (get_profile(get_user_id_in_session())->cargo_id == 3 or get_profile(get_user_id_in_session())->cargo_id == 4 or get_profile(get_user_id_in_session())->cargo_id == 5) { */?><!--
											<option
												value="<?/*= get_user_id_in_session() */?>"><?/*= get_user_name_in_session() */?></option>
										--><?php /*} else { */?>
											<option value="0">Todos</option>
											<?php foreach ($users as $user_list) : ?>
												<option
													value="<?= $user_list->id; ?>"><?= $user_list->usuario; ?></option>
											<?php endforeach; ?>
										<?php /*} */?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Fecha Inicio:</label>
								<div class="form-line">
									<input style="width: 100%" type="date" class="form-control" name="start_date"
										   id="start_date">
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<label>Fecha Fin:</label>
								<div class="form-line">
									<input style="width: 100%" type="date" class="form-control" name="end_date"
										   id="end_date">
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<a class="btn btn-primary btn-block" id="btn_search"><i
										class="material-icons">search</i><span>Consultar</span></a>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<a class="btn btn-success btn-block" id="btn_export_excel"><i
										class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<a class="btn btn-danger btn-block" id="btn_export_pdf"><i
										class="material-icons">format_indent_increase</i><span>Exportar PDF</span></a>
							</div>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table id="list_reception_payments" class="table table-striped table-bordered dataTable ">
						<thead>
						<th class="text-center"><b>USUARIO</b></th>
						<th class="text-center"><b>FECHA</b></th>
						<th class="text-center"><b>NRO. RECEPCION</b></th>
						<th class="text-center"><b>OBSERVACION</b></th>
						<th class="text-center"><b>PAGO</b></th>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= base_url('jsystem/report_reception_payments.js'); ?>"></script>

