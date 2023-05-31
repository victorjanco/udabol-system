<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 27/05/2019
 * Time: 11:50 AM
 */

$url_action = 'transit/register_transit_output';
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"> PRESTAMO DE PIEZA</h4>
            </div>
            <form id="frm_register_transit_output" name="frm_register_transit_output"
                  action="<?= site_url($url_action) ?>" method="post">
                <div class="body">
                    <fieldset>
                        <legend align="left"><b>Datos&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden >
                                <div class="col-lg-12">
                                    <label for="destination_branch_office_name">Sucursal Destino:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" value="<?= get_branch_id_in_session() ?>" id="destination_branch_office_id" name="destination_branch_office_id" hidden>
                                            <input type="text" class="form-control" value="<?= get_branch_office_name_in_session() ?>"
                                                   id="destination_branch_office_name" name="destination_branch_office_name" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"  >
                                <div class="col-lg-12">
                                    <label>Almacen Destino:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" value="<?= isset($destination_warehouse->id)? $destination_warehouse->id:''; ?>" id="destination_warehouse_id" name="destination_warehouse_id" hidden>
                                            <input type="text" class="form-control" value="<?= isset($destination_warehouse->nombre)? $destination_warehouse->nombre:''; ?>"
                                                   id="destination_warehouse_name" name="destination_warehouse_name" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Fecha: *</label>
                                        <input width="100%" type="date" class="form-control" id="date_output"
                                               name="date_output" value="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Usuario Solicitante: *</label>
                                        <select style="width: 100%" id="applicant_user" name="applicant_user"
                                                class="form-control select2">
                                            <option value="">Seleccione usuario</option>
                                            <?php foreach ($applicant_users as $user) : ?>
                                                <option value="<?= $user->id; ?>"><?= $user->usuario; ?>
                                                    - <?= $user->nombre ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Usuario Entregador: </label>
                                        <input type="text" id="delivery_user_id" name="delivery_user_id" value="<?= $delivery->id; ?>" hidden>
                                        <input width="100%" type="text" class="form-control" id="delivery_user_name"
                                               name="delivery_user_name" value="<?= $delivery->usuario.' - '.$delivery->nombre; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 95%; float: left">
                                        <label class="control-label">Observacion: </label>
                                        <input width="100%" type="text" class="form-control" id="observation"
                                               name="observation" placeholder="Escribe alguna observacion...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Motivo: *</label>
                                        <select style="width: 100%" id="reason" name="reason"
                                                class="form-control select2">
                                            <?php foreach ($list_type_reason as $row_list_type_reason) : ?>
                                                <option value="<?= $row_list_type_reason->id; ?>"><?= $row_list_type_reason->nombre; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <br>
                    <div id="div_order_work">
                        <legend align="left"><b>Orden de Trabajo&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label" title="Codigo Recepcion/ Nombre Cliente/ CI"> Nro de O.T.: * <i
                                                    class="fa fa-info-circle"
                                                    title="Codigo Recepcion/ Nombre Cliente/ CI"></i></label>
                                        <input type="text" id="order_work_id" name="order_work_id" hidden>
                                        <input width="100%" type="text" class="form-control" id="code_work"
                                               name="code_work" placeholder="Escriba un nro de O.T." title="Codigo Recepcion/ Nombre Cliente/ CI">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> Fecha Recepcion: </label><br>
                                        <label id="date_reception"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> CI: </label><br>
                                        <label id="customer_ci"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> Nombre Cliente: </label><br>
                                        <label id="customer_name"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> Marca: </label><br>
                                        <label id="brand"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> Modelo: </label><br>
                                        <label id="model"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div style="width: 93%; float: left">
                                        <label class="control-label"> IMEI: </label><br>
                                        <label id="imei"></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <br>
                    <legend align="left"><b>Repuesto&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden >
                            <div class="col-lg-12">
                                <label for="branch_office">Sucursal Origen:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" value="<?= get_branch_id_in_session() ?>" id="origin_branch_office_id" name="origin_branch_office_id" hidden>
                                        <input type="text" class="form-control" value="<?= get_branch_office_name_in_session() ?>"
                                               id="origin_branch_office_name" name="origin_branch_office_name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="origin_warehouse_id">Almacen Origen:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="origin_warehouse_id" id="origin_warehouse_id"
                                                class="form-control">
                                            <?php foreach ($origin_warehouse as $row): ?>
                                                <option value="<?= $row->id ?>"><?= $row->nombre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="code_product_output"><b>Codigo de Producto:</b></label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="code_product_transit" id="code_product_transit" type="text"
                                               class="form-control" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="product_output"
                                       title="Cod. Producto/ Nombre Producto/ Nro. Lote/ Stock Disponible"><b>Producto:<i
                                                class="fa fa-info-circle"
                                                title="Cod. Producto/ Nombre Producto/ Nro. Lote/ Stock Disponible"></i></b></label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="product_name_transit" id="product_name_transit" type="text"
                                               class="form-control"
                                               title="Cod. Producto/ Nombre Producto/ Nro. Lote/ Stock Disponible">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="lista_detalle_transit_output" class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                        <th class="text-center" style="width: 30%"><b>Producto</b></th>
<!--                                        <th class="text-center" style="width: 10%"><b>P. costo.</b></th>-->
<!--                                        <th class="text-center" style="width: 10%"><b>P. venta.</b></th>-->
<!--                                        <th class="text-center" style="width: 10%"><b>Lote.</b></th>-->
<!--                                        <th class="text-center" style="width: 15%"><b>F. vencimiento.</b></th>-->
                                        <th class="text-center" style="width: 10%"><b>Cantidad </b></th>
<!--                                        <th class="text-center" style="width: 15%"><b>Almacen Origen</b></th>-->
                                        <th class="text-center" style="width: 10%"><b>Opciones</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div align="center">
                        <button id="btn_save_transit_output" class="btn btn-success waves-effect no-modal"
                                type="submit">
                            <i class="fa fa-save"></i>
                            Guardar
                        </button>
                        <a href="<?= site_url('transit/output') ?>" class="btn btn-danger waves-effect" type="submit">
                            <i class="fa fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_product_transit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_inventory_transit_array" method="post">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">
                    </button>
                    <h4 class="modal-title" id="defaultModalLabel">AGREGAR PRODUCTO</h4>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <div id="table_inventory">

                            </div>
                            <table id="list_inventory_transit" class="table table-bordered ">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                    <th class="text-center" style="width: 30%"><b>Producto</b></th>
                                    <th class="text-center" style="width: 10%"><b>Stock</b></th>
                                    <th class="text-center" style="width: 10%"><b>Cantidad</b></th>
<!--                                    <th class="text-center" style="width: 10%"><b>P. Costo.</b></th>-->
<!--                                    <th class="text-center" style="width: 10%"><b>P. Venta.</b></th>-->
                                    <th class="text-center" style="width: 10%"><b>Lote.</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect"><i
                                class="material-icons">add_circle</i><span>Agregar</span>
                    </button>
                    <button id="close_modal_add_product_transit" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('jsystem/transit_output.js'); ?>"></script>
