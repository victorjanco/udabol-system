<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/02/2020
 * Time: 09:02 AM
 */ ?>

<div class="block-header" id="container_buttons">
    <button type="button" id="btn_new_inventory_output_type" class="btn btn-success waves-block"><i class="fa fa-plus"></i> Nueva Tipo Salida Inventario
    </button>    
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-red">
                <h2 style="font-weight: bold">TIPO SALIDA DE INVENTARIO</h2>
            </div>

			<div class="panel">
				<div class="panel-body">
					<div class="card-content">
						<fieldset>
							<legend>Lista de tipos de salida de inventario:</legend>
							<div class="row clearfix">
								<div class="body table-responsive">
									<table id="list_inventory_output_type" class="table table-striped table-bordered ">
										<thead>
										<th style="text-align: center; vertical-align: middle;">ID</th>
										<th style="text-align: center; vertical-align: middle;">NOMBRE</th>
										<th style="text-align: center; vertical-align: middle;">DESCRIPCIÓN</th>
										<th style="text-align: center; vertical-align: middle;">ESTADO</th>
										<th style="text-align: center; vertical-align: middle;">OPCIONES</th>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_new_inventory_output_type" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">REGISTRAR TIPO SALIDA INVENTARIO</h4>
            </div>
            <form id="frm_new_inventory_output_type"
                  action="<?= site_url('inventory_output_type/register_inventory_output_type') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                            <label class="form-control-label">Nombre: (*)</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="add_name"
                                           name="add_name"
                                           placeholder="Ingrese el nombre del nuevo tipo salida inventario..."
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                            <label class="form-control-label">Descripción: (*)</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="add_description"
                                           name="add_description"
                                           placeholder="Ingrese la descripción del nuevo tipo salida inventario... "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer align-center">
                    <button type="submit" class="btn btn-success waves-effect" data-toggle="tooltip"
							data-placement="left" title="GUARDA EL REGISTRO TIPO SALIDA INVENTARIO"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_inventory_output_type" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal" data-toggle="tooltip" data-placement="right"
							TITLE="CANCELA LA TRANSACCIÓN Y VUELVE A LISTA DE TIPO SALIDA INVENTARIO"><i class="fa fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit_inventory_output_type" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">EDITAR TIPO SALIDA INVENTARIO</h4>
            </div>
            <form id="frm_edit_inventory_output_type" action="<?= site_url('inventory_output_type/modify_inventory_output_type') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                            <label class="form-control-label">Nombre: (*)</label>
                        </div>

                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
									<input type="text" id="edit_id_inventory_output_type" name="edit_id_inventory_output_type" hidden>
                                    <input type="text" class="form-control" id="edit_name"
                                           name="edit_name"
                                           placeholder="Ingrese el nombre del nuevo tipo salida inventario... "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                            <label class="form-control-label">Descripción: (*)</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="edit_description"
                                           name="edit_description"
                                           placeholder="Ingrese la descripción del nuevo tipo salida inventario... "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer align-center">
                    <button type="submit" class="btn btn-success waves-effect" data-toggle="tooltip"
							data-placement="left" title="ACTUALIZA EL REGISTRO TIPO SALIDA INVENTARIO"><i class="fa fa-save"></i> Actualizar
                    </button>
                    <button id="close_modal_edit_inventory_output_type" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal" data-toggle="tooltip" data-placement="right"
							TITLE="CANCELA LA TRANSACCIÓN Y VUELVE A LISTA DE TIPO SALIDA INVENTARIO"><i class="fa fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_inventory_output_type" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">VER TIPO SALIDA INVENTARIO</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                        <label class="form-control-label">Nombre:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
						<input type="text" class="form-control" style="background-color: rgb(235, 235, 228);"
							   id="view_name" name="view_name" readonly>
                    </div>
                </div>
				<br>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="text-align: right">
                        <label class="form-control-label">Descripción:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
						<input type="text" class="form-control" style="background-color: rgb(235, 235, 228);"
							   id="view_description" name="view_description" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer align-center">
                <button id="close_modal_view_inventory_output_type" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal" data-toggle="tooltip" data-placement="right"
						TITLE="VOLVER A LISTA DE TIPO SALIDA INVENTARIO"><i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<script>


	$(document).ready(function () {

		$('[data-toggle="tooltip"]').tooltip();

		// var buttons=[{name:'NUEVO', id:'btn_new_inventory_output_type', label:' Nuevo Tipo Salida Inventario', type:'modal'}];
		// if (typeof options !== 'undefined') {
			// container_buttons(buttons, options);
			get_inventory_output_type_list();
		// }

	});

</script>
<script src="<?= base_url('jsystem/inventory_output_type.js'); ?>"></script>
