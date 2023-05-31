<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 21/01/2020
 * Time: 14:35 PM
 */

?>

<div class="block-header" id="container_buttons">

</div>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Lista de Colores</h2>
			</div>
			<div class="body table-responsive">
				<table id="table_color" class="table table-striped table-bordered ">
					<thead>
					<th class="text-center">ID</th>
					<th class="text-center"><b>Nombre</b></th>
					<th class="text-center"><b>Descripcion</b></th>
					<th class="text-center"><b>Estado</b></th>
					<th class="text-center">Opciones</th>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_new_color" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header cabecera_modal">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
						style="color: white;font-size: 15pt">
				</button>
				<h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Color</h4>
			</div>
			<form id="frm_new_color" action="<?= site_url('color/register_color') ?>"
				  method="post">
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">(*) Nombre:</label>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input type="text" class="form-control" id="add_name" name="add_name"
										   placeholder="Ingrese nombre del color" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Descripcion:</label>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input type="text" class="form-control" id="add_description"
										   name="add_description"
										   placeholder="Ingrese la descripcion del color"
										   value="">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
					</button>
					<button id="close_modal_new_color" type="button" class="btn btn-danger waves-effect"
							data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_edit_color" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header cabecera_modal">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
						style="color: white;font-size: 15pt">&times;
				</button>
				<h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar color</h4>
			</div>
			<form id="frm_edit_color" action="<?= site_url('color/modify_color') ?>"
				  method="post">
				<div class="modal-body">
					<div class="row clearfix">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">(*) Nombre:</label>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="color_id" name="color_id" hidden>
									<input type="text" class="form-control" id="edit_name" name="edit_name"
										   placeholder="Ingrese nombre del color">
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Descripcion:</label>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div class="form-line">
									<input type="text" class="form-control" id="edit_description"
										   name="edit_description"
										   placeholder="Ingrese la descripcion del color">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
					</button>
					<button id="close_modal_edit_color" type="button" class="btn btn-danger waves-effect"
							data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="modal_view_color" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header cabecera_modal">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"
						style="color: white;font-size: 15pt">&times;
				</button>
				<h4 class="modal-title titulo_modal" id="defaultModalLabel">Ver color</h4>
			</div>
			<div class="modal-body">
				<div class="row clearfix">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<label class="form-control-label">Nombre:</label>
					</div>
					<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
						<div class="form-group">
							<div class="form-line">
								<input type="text" class="form-control" id="view_name" name="view_name" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
						<label class="form-control-label">Descripcion:</label>
					</div>
					<div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
						<div class="form-group">
							<div class="form-line">
								<input type="text" class="form-control" id="view_description"
									   name="view_description" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="close_modal_view_color" type="button" class="btn btn-danger waves-effect"
						data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>
<script>
	var options = <?php echo $option ?>;
</script>
<script src="<?= base_url('jsystem/color.js'); ?>"></script>
