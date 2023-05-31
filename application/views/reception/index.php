<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 04:54 PM
 */
?>
<div class="block-header">
    <a class="btn btn-success" href="<?= site_url('reception/new_reception') ?>">
        <i class="fa fa-plus"></i> Nueva
        Recepcion</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Recepciones</h2>
            </div>
            <br>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Datos:</label>
                                    <select class="form-control" id="filter_date" name="filter_date" required>
                                        <option value="1">Todos</option>
                                        <option value="2">3+ dias</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Marca:</label>
                                    <select class="form-control" id="filter_reception_brand"
                                            name="filter_reception_brand">
                                        <option value="">Todos</option>
                                        <?php foreach ($brand_for_reception as $brand_list) : ?>
                                            <option value="<?= $brand_list->nombre; ?>"><?= $brand_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Estado Recepcion:</label>
                                    <select class="form-control" id="filter_reception_state"
                                            name="filter_reception_state">
                                        <option value="">--Todos--</option>
                                        <option value="<?= RECEPCIONADO ?>"><?= get_work_order_states(RECEPCIONADO) ?></option>
                                        <option value="<?= REPARADO ?>"><?= get_work_order_states(REPARADO) ?></option>
                                        <!-- <option value="<?= APROBADO ?>"><?= get_work_order_states(APROBADO) ?></option>
                                        <option value="<?= EN_PROCESO ?>"><?= get_work_order_states(EN_PROCESO) ?></option>
                                        <option value="<?= CONCLUIDO ?>"><?= get_work_order_states(CONCLUIDO) ?></option> -->
                                        <option value="<?= ENTREGADO ?>"><?= get_work_order_states(ENTREGADO) ?></option>
                                        <!-- <option value="<?= EN_MORA ?>"><?= get_work_order_states(EN_MORA) ?></option> -->
                                        <!-- <option value="<?= ESPERA_STOCK ?>"><?= get_work_order_states(ESPERA_STOCK) ?></option> -->
                                        <!-- <option value="<?= ENTREGADO_ESPERA_STOCK ?>"><?= get_work_order_states(ENTREGADO_ESPERA_STOCK) ?></option> -->
                                        <!-- <option value="<?= NO_APROBADO ?>"><?= get_work_order_states(NO_APROBADO) ?></option> -->
                                        <!-- <option value="<?= SIN_SOLUCION ?>"><?= get_work_order_states(SIN_SOLUCION) ?></option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label for="filter_reception_code" class="control-label">Codigo de
                                        recepcion:</label>
                                    <input type="text" class="form-control" name="filter_reception_code"
                                           id="filter_reception_code">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label for="filter_date_start_reception" class="control-label">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="filter_date_start_reception"
                                           id="filter_date_start_reception">
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Fin:</label>
                                    <input for="filter_date_end_reception" type="date" class="form-control"
                                           name="filter_date_end_reception"
                                           id="filter_date_end_reception">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="table-responsive">
                    <table id="reception_list" class="table table-striped table-bordered table-responsive">
                        <thead>
                        <th>ID</th>
                        <th class="text-center" width="5%"><b>Codigo</b></th>
                        <th class="text-center" width="10%"><b>Cliente</b></th>
                        <th class="text-center" width="5%"><b>Marca</b></th>
                        <th class="text-center" width="5%"><b>Modelo</b></th>
                        <th class="text-center" width="10%"><b>Imei</b></th>
                        <th class="text-center" width="20%"><b>F. Recepcion</b></th>
                        <th class="text-center" width="10%"><b>Monto Total</b></th>
                        <!-- <th class="text-center" width="10%"><b>Garantia</b></th> -->
                        <th class="text-center" width="5%"><b>Estado</b></th>
                        <th class="text-center" width="10%"><b>Usuario Recepcion</b></th>
                        <!-- <th class="text-center" width="10%"><b>Usuario Perito</b></th> -->
                        <th class="text-center" width="5%"><b>Opciones</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- <dv class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"
                         style="background-color: #00CC00;text-align: center;"><b>Menos de 24 Horas</b></div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"
                         style="background-color: yellow;text-align: center;"><b>Menos de 48 Horas</b></div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"
                         style="background-color: #dd4b39;text-align: center;"><b>Menos de 72 Horas</b></div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"
                         style="background-color: #999999;text-align: center;"><b>Mayor a 72 Horas</b></div>
                </dv> -->
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="modal_view_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel" align="center">INFORMACION DE LA
                    RECEPCION</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_sale" name="id_sale" hidden>
                <div class="row clearfix" id="reception_data">
                </div>
            </div>
			<div class="row clearfix" id="generate_totals_view">
			</div>
            <div class="modal-footer">
                <button id="close_modal_edit_service" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_generate_sale_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="defaultModalLabel" align="center">INFORMACION DE LA
                    RECEPCION</h4>
            </div>
            <form id="frm_generate_sale_for_reception " method="post">
                <div class="modal-body" style="overflow-y:auto ;height: 200px">
                    <input type="text" id="order_work_id" name="order_work_id" hidden>
                    <input type="text" id="reception_id" name="reception_id" hidden>
                    <input type="text" id="code_reception" name="code_reception" hidden>
                    <input type="text" id="reception_payment_observation" name="reception_payment_observation" hidden>
                    <div class="row clearfix" id="generate_data">
                    </div>
                </div>
				<div class="row clearfix" id="generate_totals">
				</div>

                <?php if (get_profile(get_user_id_in_session())->cargo_id != 4 or get_profile(get_user_id_in_session())->cargo_id != 5) { ?>
                    <div class="row clearfix" align="center">
                        <br>
                        <br>
                        <label>DESEA HACER UN DESCUENTO PARA GENERAR LA VENTA?</label>
                        <br>
                        <button id="open_discount" type="button" class="btn btn-primary waves-blue">Aplicar
                            Descuentos
                        </button>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-offset-4 col-lg-4 col-md-offset-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="" id="view_discount" hidden>
                                    <b>Descuento Bs</b>
                                    <input class="form-control" type="number" step="any"
                                           id="sale_discount"
                                           style="background-color: rgba(255,23,19,0.25);text-align: center"
                                           name="sale_discount" placeholder="0.00" value="0.00"
                                           onkeyup="return calculate_total_reception_deliver()">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="text-center">
                    <button id="btn_generate_note_sale"  class="btn btn-success waves-effect"><i
                                class="fa fa-save"></i> Generar Venta
                    </button>
                    <button id="close_modal_generate_note_sale" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cancelar
                    </button>
                </div>
                <br>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_reception_reason" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Motivo</h4>
            </div>
            <form id="frm_new_reception_reason" action="<?= site_url('reception/register_reception_reason') ?>"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Motivo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="reception_reason_id" name="reception_reason_id" hidden>
                                    <select class="form-control" id="type_reason" name="type_reason">
                                        <option value="">::Seleccione una opcion</option>
                                        <?php foreach ($type_reason_for_reception as $row) : ?>
                                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Observaciones</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="observations" name="observations"
                                           placeholder="Ingrese una observacion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <fieldset>
                        <div class="row clearfix" hidden>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="brand_product">Almacen*: </label>
                                        <div class="form-line">
                                            <select style="width: 100%" class="form-control select2" id="warehouse_id"
                                                    name="warehouse_id">
                                                <option value="">Seleccione una opcion</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="producto_order_work">Repuesto*:</label>
                                        <input type="text" name="reason_product_selected" id="reason_product_selected"
                                               hidden>
                                        <input type="text" class="form-control" name="reason_producto_order_work"
                                               id="reason_producto_order_work" placeholder="Nombre del producto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_reception_reason" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->view('reception/modal_states') ?>

<script src="<?= base_url('jsystem/reception.js') ?>"></script>
