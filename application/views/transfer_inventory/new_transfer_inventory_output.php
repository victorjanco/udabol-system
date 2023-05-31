<?php
/**
 *  * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 7/5/2018
 * Time: 7:15 PM
 */
?>
<div class="row clearfix">
    <input type="text" name="type_output_id" id="type_output_id" value="1" hidden>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h2 class="panel-title titulo_frm">Nuevo Traspaso Salida de Inventario</h2>
            </div>
            <div class="body">
                <div class="header">
                    <h2><strong>Seleccione la Sucursal y Almacen especifico que desea transferir
                            inventario:</strong>
                    </h2>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
                        <b><label for="branch_office_transfer">Sucursal Destino para Transferir:</label></b>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="branch_office_transfer" id="branch_office_transfer"
                                        class="form-control">
                                    <option value="0">Seleccione Sucursal Destino</option>
                                    <?php foreach ($list_branch_office as $row_branch_office): ?>
                                        <option value="<?= $row_branch_office->id ?>"><?= $row_branch_office->nombre_comercial ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
                        <b><label for="warehouse_transfer">Almacen Destino para Transferir:</label></b>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="warehouse_transfer" id="warehouse_transfer"
                                        class="form-control">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
                        <strong><label for="observation">Observacion del Traspaso de
                                Inventario:</label></strong>
                        <div class="form-group">
                            <div class="form-line">
                                            <textarea class="form-control no-resize"
                                                      placeholder="Ingrese una glosa por favor..." name="observation"
                                                      id="observation"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="frm_add_row_inventory_output" method="post">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>Seleccione los productos que desea tranferir:</h4>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="col-lg-12">
                                    <label for="branch_office">Sucursal Origen:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" readonly
                                                   value="<?= $branch_office; ?>" id="branch_office"
                                                   name="branch_office"
                                                   autofocus disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="col-lg-12">
                                    <label for="warehouse">Almacen Origen:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="warehouse" id="warehouse" required class="form-control"
                                                    autofocus>
                                                <?php foreach ($list_warehouse as $row_warehouse): ?>
                                                    <option value="<?= $row_warehouse->id ?>"><?= $row_warehouse->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
                                <div class="col-lg-12">
                                    <label for="type_product">Tipo de producto:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="type_product" id="type_product" class="form-control">
                                                <?php foreach ($list_type_product as $row_type_product): ?>
                                                    <option value="<?= $row_type_product->id ?>"><?= $row_type_product->nombre ?></option>
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
                                            <input name="code_product_output" id="code_product_output" type="text"
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
                                            <input name="product_output" id="product_output" type="text"
                                                   class="form-control"
                                                   title="Cod. Producto/ Nombre Producto/ Nro. Lote/ Stock Disponible">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </form>
                <form id="frm_register_inventory_output" method="post"
                      action="<?= site_url('inventory/register_inventory_output') ?>">
                    <div class="header">
                        <h2><strong>Detalle:</strong></h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" hidden>
                                    <input id="warehouse_origin_id" name="warehouse_origin_id" value="">
                                    <input id="warehouse_transfer_id" name="warehouse_transfer_id" value="">
                                    <input id="branch_office_transfer_id" name="branch_office_transfer_id" value="">
                                    <input id="description" name="description" value="">
                                    <b><label for="type_exit_inventory">Tipo Salida de Inventario:</label></b>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="type_exit_inventory" id="type_exit_inventory"
                                                    class="form-control">
                                                <?php foreach ($list_type_exit_inventory as $row_type_exit_inventory):
                                                    if ($row_type_exit_inventory->id == 3) {
                                                        ?>
                                                        <option value="<?= $row_type_exit_inventory->id ?>"><?= $row_type_exit_inventory->nombre ?></option>
                                                    <?php } endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table id="lista_detalle" class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                                <th class="text-center" style="width: 30%"><b>Producto</b></th>
                                                <th class="text-center" style="width: 10%"><b>P. costo.</b></th>
                                                <th class="text-center" style="width: 10%"><b>P. venta.</b></th>
                                                <th class="text-center" style="width: 10%"><b>Lote.</b></th>
                                                <!-- <th class="text-center" style="width: 15%"><b>F. vencimiento.</b></th> -->
                                                <th class="text-center" style="width: 10%"><b>Cantidad </b></th>
                                                <th class="text-center" style="width: 15%"><b>Almacen</b></th>
                                                <th class="text-center"><b>Opciones</b></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" align="center">
                        <button type="submit" id="btn_register_device"
                                class="btn btn-success waves-effect no-modal"><i class="fa fa-save"></i> Guardar
                        </button>

                        <a href="<?= site_url('transfer_inventory/transfer_inventory_output') ?>" type="button"
                           class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>
                            Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_product_output" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_inventory_output_array" method="post">
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
                            <table id="list_inventory" class="table table-bordered ">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                    <th class="text-center" style="width: 30%"><b>Producto</b></th>
                                    <th class="text-center" style="width: 10%"><b>Stock</b></th>
                                    <th class="text-center" style="width: 10%"><b>Cantidad</b></th>
                                    <th class="text-center" style="width: 10%"><b>P. Costo.</b></th>
                                    <th class="text-center" style="width: 10%"><b>P. Venta.</b></th>
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
                    <button id="close_modal_add_row_output" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('jsystem/transfer_inventory.js'); ?>"></script>
