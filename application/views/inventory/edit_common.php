<?php
/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros Leon
 * Date: 18/08/2017
 * Time: 8:25 PM
 */
$this->load->model('warehouse_model');
$this->load->model('product_model');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2><strong>Datos del ingreso de inventario</strong></h2>
            </div>
            <div class="body">
                <form id="frm_registro_venta_otros" class="form-horizontal" role="form"
                      action="javascript: addRow1();">
                    <div class="header">
                        <h2><strong>Datos del ingreso de inventario:</strong></h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Glosa:</label>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                               value="<?= $inventory_entry->nombre ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Nro Comprobante:</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="nro_comprobante" name="nro_comprobante"
                                                   value="<?= $inventory_entry->nro_comprobante ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Fecha de ingreso:</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">date_range</i>
                                            </span>
                                        <div class="form-line">
                                            <input type="date" class="form-control date" placeholder="Ex: 30/07/2016"
                                                   name="fecha_ingreso" id="fecha_ingreso"
                                                   value="<?= $inventory_entry->fecha_ingreso ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Tipo de Ingreso:</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="tipo_ingreso"
                                                   name="tipo_ingreso" value="<?= $tipo_ingreso_inventario->nombre ?>"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Usuario:</label>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="usuario" name="usuario"
                                                   value="<?= $usuario->nombre ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="header">
                        <h2><strong>Detalle:</strong></h2>
                    </div>
                </form>
                <form id="frm_edit" method="post" action="<?= site_url('inventory/register_edit_common') ?>">
                    <div class="row">
                        <input type="text" value="<?= $inventory_entry->id; ?>" name="" id="ingreso_inventario_id"
                               hidden>
                        <br>
                        <div class="col-lg-12 col-md-9 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="lista_detalle" class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%"><b>Nro.</b></th>
                                        <th class="text-center" style="width: 10%"><b>Codigo</b></th>
                                        <th class="text-center" style="width: 35%"><b>Producto</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. venta.</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. compra.</b></th>
                                        <th class="text-center" style="width: 10%"><b>Cantidad </b></th>
                                        <th class="text-center" style="width: 15%"><b>Almacen</b></th>
                                        <!--<th class="text-center"><b>Opciones</b></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $num = 1;
                                    foreach ($inventory_detail as $row) {
                                        $almacen = $this->warehouse_model->get_warehouse_id($row->almacen_id);
                                        $producto = $this->product_model->get_product_entity_by_id($row->producto_id);
                                        $proveedor = $this->provider_model->get_provider_by_id_inventory(1);
                                        $fila = '<tr id="' . $num . '">';
                                        $fila .= '<td align="center">' . $num. '</td>';
                                        $fila .= '<td align="center">' . $row->codigo_producto . '</td>';
                                        $fila .= '<td align="center"><input type="text" value="' . $producto->id . '" name="producto_id[]" hidden/><input type="text" value="' . $producto->nombre_comercial . '" id="descripcion" name="descripcion[]" hidden/>' . $producto->nombre_comercial . '</td>';
                                        $fila .= '<td align="right"><input  value="' . $row->precio_venta . '"  name="precio_venta[]" hidden/>' . $row->precio_venta . '</td>';
                                        $fila .= '<td align="right"><input  value="' . $row->precio_compra . '" name="precio_compra[]" hidden/>' . $row->precio_compra . '</td>';
                                        $fila .= '<td align="right"><input type="text" value="' . $row->cantidad_ingresada . '" name="cantidad[]" hidden/>' . $row->cantidad_ingresada . '</td>';
                                        $fila .= '<td><input type="text" value="' . $row->almacen_id . '" name="almacen[]" hidden/><input name="codigo_nro_lote[]" value="' . $almacen->nombre . '" hidden/>' . $almacen->nombre . '</td>';
                                        /*$fila .= '<td class="text-center"><a class="elimina btn-danger btn" onclick="delete_row_table_edit('.$num.')">Eliminar</a></td></tr>';*/
                                        $num++;
                                        echo $fila;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" align="center">
                        <a href="<?= site_url('inventory') ?>" type="button" class="btn btn-danger waves-effect"><i
                                    class="fa fa-times"></i> Cerrar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/inventory.js'); ?>"></script>
