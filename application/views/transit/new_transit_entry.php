<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 27/05/2019
 * Time: 11:50 AM
 */

$url_action = 'transit/register_transit_entry';
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"> DEVOLUCION NRO. <?= $transit->nro_prestamo ?></h4>
            </div>
            <form id="frm_register_transit_entry" name="frm_register_transit_entry"
                  action="<?= site_url($url_action) ?>" method="post">
                <div class="body">
                    <fieldset>
                        <legend align="left"><b>Datos&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"  hidden>
                                <div class="col-lg-12">
                                    <label for="destination_branch_office_name">Sucursal Destino:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" value="<?= $transit->id ?>" id="transit_id" name="transit_id" hidden>
                                            <input type="text" value="<?= get_branch_id_in_session() ?>" id="destination_branch_office_id" name="destination_branch_office_id" hidden>
                                            <input type="text" class="form-control" value="<?= get_branch_office_name_in_session() ?>"
                                                   id="destination_branch_office_name" name="destination_branch_office_name" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
                                <div class="col-lg-12">
                                    <label for="destination_warehouse_id">Almacen Destino:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="destination_warehouse_id" id="destination_warehouse_id"
                                                    class="form-control">
                                                <?php foreach ($destination_warehouse as $row): ?>
                                                    <option value="<?= $row->id ?>"><?= $row->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Fecha de Devolucion: *</label>
                                        <input width="100%" type="date" class="form-control" id="date_output"
                                               name="date_output" value="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Usuario que esta devolviendo: </label>
                                        <input type="text" id="delivery_user_id" name="delivery_user_id" value="<?= $transit->usuario_solicitante_id_prestamo; ?>" hidden>
                                        <input width="100%" type="text" class="form-control" id="delivery_user_name"
                                               name="delivery_user_name" value="<?= $transit->usuario_solicitante_prestamo; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 93%; float: left">
                                        <label class="control-label">Usuario al que le esta entregando: *</label>
                                        <select style="width: 100%" id="applicant_user" name="applicant_user"
                                                class="form-control select2">
                                                <option value="<?= get_user_id_in_session(); ?>"><?= get_user_name_in_session(); ?></option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 95%; float: left">
                                        <label class="control-label">Observacion del prestamo: </label>
                                        <input width="100%" type="text" class="form-control" value="<?=$transit->observacion_prestamo?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line" style="width: 95%; float: left">
                                        <label class="control-label">Observacion de la devolucion: </label>
                                        <input width="100%" type="text" class="form-control" id="observation"
                                               name="observation" placeholder="Escribe alguna observacion...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <br>
                    <div id="div_order_work" hidden>
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
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
                            <div class="col-lg-12">
                                <label for="origin_warehouse_id">Almacen Origen:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="origin_warehouse_id" id="origin_warehouse_id"
                                                class="form-control">
                                                <option value="<?= $transit->almacen_destino_id_prestamo ?>"><?= $transit->almacen_destino_prestamo ?></option>
                                        </select>
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
<!--                                        <th class="text-center" style="width: 10%"><b>Opciones</b></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($detail as $detail_product){?>
                                    <tr>
                                        <td><?= $detail_product->codigo_producto?>  </td>
                                        <td><input type="text" value="<?= $detail_product->id ?>" name="inventory_id[]" hidden/>
                                            <input type="text" value="<?= $detail_product->producto_id ?>" name="product_id[]" hidden/>
                                            <input type="text" value="<?= $detail_product->nombre_comercial ?>" name="product_name[]" hidden/><?= $detail_product->nombre_comercial ?></td>
                                        <td align="right" hidden><input value="<?= $detail_product->precio_compra_producto ?>"  name="price_cost[]" hidden/><?=$detail_product->precio_compra_producto?></td>
                                        <td align="right" hidden><input value="<?= $detail_product->precio_venta_producto ?>" name="price_sale[]" hidden/><?= $detail_product->precio_venta_producto ?></td>
                                        <td align="right" hidden><input type="text" value="<?= $detail_product->codigo ?>" name="codigo_lote[]" hidden/><?= $detail_product->codigo ?></td>
                                        <td align="right" hidden><input type="text" value="<?= $detail_product->almacen_origen_id ?>" name="warehouse_origin_detail_id[]" /></td>
                                        <td align="right" hidden><input type="text" value="" name="date_validity[]" hidden/><?= $detail_product->fecha_vencimiento ?></td>
                                        <td><input type="text" value=" <?= $detail_product->cantidad ?>" name="quantity_ouput[]" hidden/><?= $detail_product->cantidad ?></td>
                                        <?php }?>
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


<script src="<?= base_url('jsystem/transit_output.js'); ?>"></script>
