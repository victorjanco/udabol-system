<?php
/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros Leon
 * Date: 18/08/2017
 * Time: 8:25 PM
 */
?>
<div class="row clearfix">
    <input type="text" name="type_output_id" id="type_output_id" value="1" hidden>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h2 class="panel-title titulo_frm">Nuevo registro Salida de inventario</h2>
            </div>
            <div class="body">
                <div class="header">
                    <h2><strong>Datos:</strong></h2>
                </div>
                <form id="frm_add_row_inventory_output" method="post">
                    <div class="row clearfix">

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="branch_office">Sucursal:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control"
                                               value="<?= $branch_office; ?>" id="branch_office" name="branch_office"
                                                disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="warehouse">Almacen:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="warehouse" id="warehouse" required class="form-control" autofocus>
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
                                <label for="barcode_output">Codigo de producto:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="barcode_output" id="barcode_output" type="text"
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

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="quantity_available">Cantidad disponible:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="quantity_available" id="quantity_available" type="text"
                                               class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="quantity">Cantidad:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="quantity" id="quantity" type="text" class="form-control" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="price_cost">Precio de costo:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="price_cost" id="price_cost" type="text" class="form-control"
                                               autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="col-lg-12">
                                <label for="price_sale">Precio de venta:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="price_sale" id="price_sale" type="text" class="form-control"
                                               autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
                            <div class="col-lg-12">
                                <label for="nro_lote">Lote:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" hidden value="" id="inventory_id" name="inventory_id">
                                        <input name="nro_lote" id="nro_lote" type="text" class="form-control"
                                                disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
                            <div class="col-lg-12">
                                <label for="date_expired">F. de vencimiento:</label>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input name="date_expired" id="date_expired" type="date" class="form-control"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-2 col-lg-offset-10 col-md-2 col-md-offset-10 col-sm-offset-6 col-sm-6">
                        <button type="submit" id="btn_add_detail" class="btn btn-primary waves-effect btn-block"><i
                                    class="material-icons">add_circle</i><span>Agregar</span>
                        </button>
                    </div>
                    <br>
                    <br>
                </form>
                <form id="frm_register_inventory_output" method="post"
                      action="<?= site_url('inventory/register_inventory_output') ?>">
                    <div class="header">
                        <h2><strong>Detalle:</strong></h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                    <b><label for="type_exit_inventory">Tipo Salida de Inventario:</label></b>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select name="type_exit_inventory" id="type_exit_inventory"
                                                    class="form-control">
                                                <?php foreach ($list_type_exit_inventory as $row_type_exit_inventory): ?>
                                                    <option value="<?= $row_type_exit_inventory->id ?>"><?= $row_type_exit_inventory->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                    <strong><label for="description">Observacion:</label></strong>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea class="form-control no-resize"
                                                      placeholder="Ingrese una glosa por favor..." name="description"
                                                      id="description"></textarea>
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
                                                <th class="text-center" style="width: 10%"><b>P. Costo</b></th>
                                                <th class="text-center" style="width: 10%"><b>P. Venta</b></th>
<!--                                                <th class="text-center" style="width: 10%"><b>Lote.</b></th>-->
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
                        <button type="submit" id="btn_register_output"
                                class="btn btn-success waves-effect no-modal"><i class="fa fa-save"></i> Guardar
                        </button>

                        <a href="<?= site_url('inventory/inventory_output') ?>" type="button"
                           class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>
                            Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/inventory.js'); ?>"></script>
