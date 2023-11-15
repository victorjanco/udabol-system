<?php
/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros Leon
 * Date: 18/08/2017
 * Time: 8:25 PM
 */
$title = "Nuevo registro Ingreso de inventario";
$url_action = site_url('purchase/r');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="panel-heading cabecera_frm">
                <div style="">
                    <h2 class="panel-title titulo_frm"><?= $title ?></h2>
                </div>
            </div>

            <div class="body">

                <div id="div_titulo_datos" class="header">
                    <h2>
                        <strong>Datos del ingreso de inventario:</strong>
                    </h2>
                </div>

                <div id="div_datos" class="row clearfix">

                    <div class="col-lg-5 col-md-4 col-sm-6 col-xs-12">
                        <div class="col-lg-12">
                            <label class="form-control-label" for="nombre">Glosa*:</label>
                        </div>
                        <div class="col-lg-12">
                            <div id="reg_nombre" class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           placeholder="Ingrese una glosa por favor...">
                                </div>
                            </div>
                            <label id="pre_glosa" hidden></label>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div class="col-lg-12">
                            <label class="form-control-label" for="fecha_ingreso">Fecha de ingreso*:</label>
                        </div>
                        <div class="col-lg-12">
                            <div id="reg_fecha_ingreso" class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </span>
                                <div class="form-line">
                                    <input type="date" class="form-control date" placeholder="Ex: 30/07/2016"
                                           name="fecha_ingreso" id="fecha_ingreso" value="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <label id="pre_fecha_ingreso"></label>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="col-lg-12">
                            <label class="form-control-label" for="nro_comprobante">Nro. Comprobante*:</label>
                        </div>
                        <div class="col-lg-12">
                            <div id="reg_nro_comprobante" class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nro_comprobante" name="nro_comprobante"
                                           placeholder="Nro. Comprobante" onkeyup="event_nro_comprobante()">
                                </div>
                            </div>
                            <label id="pre_nro_comprobante"></label>
                        </div>
                    </div>

                </div>

                <div id="div_titulo_detalle" class="header">
                    <h2>
                        <strong>Detalle:</strong>
                    </h2>
                </div>

                <form id="frm_registro_venta_otros" class="form-horizontal" role="form" action="javascript: addRow1();">

                    <div id="div_detalle" class="body">
                        <input type="text" id="contador" name="contador" hidden/>
                        <input type="text" id="id_producto" name="id_producto" hidden>

                        <div class="row clearfix">

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="almacen">Almacen*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <select name="almacen" id="almacen" class="form-control select2">
                                                <?php foreach ($warehouse_list as $warehouse_row): ?>
                                                    <option value="<?= $warehouse_row->id ?>"><?= $warehouse_row->nombre ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="codigo_barra">Codigo*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input class="form-control" name="codigo_barra"
                                                   id="codigo_barra" placeholder="Codigo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="text" id="product_id" name="product_id" hidden>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="producto">Nombre del Producto*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input class="form-control" name="producto"
                                                   id="producto" placeholder="Ingrese el Nombre del Producto">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="precio_compra">Precio Compra*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="precio_compra"
                                                   name="precio_compra" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<div class="col-lg-12">
									<label class="form-control-label" for="precio_venta">Precio Venta*:</label>
								</div>
								<div class="col-lg-12">
									<div class="input-group">
										<div class="form-line">
											<input type="text" class="form-control" id="precio_venta"
												   name="precio_venta" placeholder="0.00">
										</div>
									</div>
								</div>
							</div>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="proveedor">Proveedor*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <select name="proveedor" id="proveedor" class="form-control select2">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="nro_lote">Nro. de lote*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="nro_lote" name="nro_lote" value="1"
                                                   placeholder="0.00" onkeypress="return numbers_letters(event);">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <div class="col-lg-12">
                                    <label class="form-control-label" for="cantidad_producto">Cantidad*:</label>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="cantidad_producto"
                                                   name="cantidad_producto"
                                                   placeholder="Ingrese cantidad del producto Selecccionado"
                                                   onkeypress="return numbers_point(event)">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 col-lg-offset-10 col-md-2 col-md-offset-10 col-sm-offset-6 col-sm-6">
                                <button type="submit" class="btn btn-primary waves-effect btn-block">
                                    <i class="material-icons">add_circle</i><span>Agregar</span>
                                </button>
                            </div>


                        </div>
                    </div>
                </form>

                <form id="frm_new" method="post" action="<?= site_url('inventory/register_common') ?>">

                    <div id="div_agregar" class="row">
                        <input type="text" value="<?= $type_inventory_entry_id; ?>"
                               name="type_inventory_entry_id" id="type_inventory_entry_id" hidden>
                        <br>
                        <div class="col-lg-12 col-md-9 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="lista_detalle" class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%"><b>Nro.</b></th>
                                        <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                        <th class="text-center" style="width: 25%"><b>Producto</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. Venta</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. Compra</b></th>
                                        <th class="text-center" style="width: 10%"><b>Cantidad </b></th>
                                        <th class="text-center" style="width: 15%" hidden><b>Almacen</b></th>
                                        <th class="text-center" style="width: 30%"><b>Proveedor</b></th>
                                        <th class="text-center"><b>Opciones</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer" align="center">

                        <button type="submit" id="btn_register_device"
                                class="btn btn-success waves-effect no-modal"><i class="fa fa-save"></i> Guardar
                        </button>

                        <a id="btn_previsualizar" type="button" class="btn btn-primary waves-effect no-modal"><i
                                    class="fa fa-eye"></i>
                            Pre-visualizar
                        </a>

                        <a id="btn_cancelar" href="<?= site_url('inventory') ?>" type="button"
                           class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>
                            Cancelar
                        </a>

                        <a id="btn_volver_atras" type="button"
                           class="btn btn-warning waves-effect no-modal"><i class="fa fa-arrow-left"></i>
                            Volver atras
                        </a>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/inventory.js'); ?>"></script>
