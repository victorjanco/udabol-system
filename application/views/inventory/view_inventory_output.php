<?php
/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 02/05/2018
 * Time: 09:30 AM
 */

/* */

$this->load->model('Inventory_model');
$this->load->model('Product_model');
$this->load->model('Warehouse_model');
?>

<div class="row clearfix">
    <input type="text" name="type_output_id" id="type_output_id" value="1" hidden>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h2 class="panel-title titulo_frm">Datos de salida de inventario</h2>
            </div>
            <form id="frm_register_inventory_output" method="post">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <strong><label>Observacion:</label></strong>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                        <textarea rows="2" class="form-control no-resize" name="description"
                                                  id="description"
                                                  readonly><?= $inventory_output->observacion ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha de Registro:</label>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">date_range</i>
                                            </span>
                                    <div class="form-line">
                                        <input type="date" class="form-control date"
                                               name="fecha_regi" id="fecha_regi"
                                               value="<?= $inventory_output->fecha_modificacion?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <strong><label>Tipo de salida:</label></strong>
                            </div>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="tipo_salida" name="tipo_salida"
                                           value="<?= $type_inventory_output->nombre ?>" readonly>
                                </div>
                            </div>
                        </div>


                    </div>

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
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php
                            $num = 1;
                            foreach ($inventory_output_detail as $row) {
                                $inventory = $this->inventory_model->get_inventory_new_by_id($row->inventario_id);
                                $producto = $this->product_model->get_product_entity_by_id($inventory->producto_id);
                                $almacen = $this->warehouse_model->get_warehouse_id($inventory->almacen_id);

                                $fila = '<tr id="' . $num . '">';
                                $fila .= '<td align="center">' . $producto->codigo . '</td>';
                                $fila .= '<td align="right"><input type="text" value="' . $producto->id . '" name="producto_id[]" hidden/>
                                                      <input type="text" value="' . $producto->nombre_comercial . ' - '. $producto->nombre_generico . '" id="descripcion" name="descripcion[]" hidden/>' . $producto->nombre_comercial . ' - '. $producto->nombre_generico .'</td>';
                                $fila .= '<td align="center"><input  value="' . $row->precio_costo . '" name="precio_costo[]" hidden/>' . $row->precio_costo . '</td>';
                                $fila .= '<td align="center"><input  value="' . $row->precio_venta . '"  name="precio_venta[]" hidden/>' . $row->precio_venta . '</td>';
                                $fila .= '<td align="center">' . $inventory->codigo . '</td>';
                                // $fila .= '<td align="center"><input  value="' . $inventory->fecha_vencimiento . '"  name="fecha_vencimiento[]" hidden/>' . $inventory->fecha_vencimiento . '</td>';
                                $fila .= '<td align="center"><input type="text" value="' . $row->cantidad . '" name="cantidad[]" hidden/>' . $row->cantidad . '</td>';
                                $fila .= '<td><input type="text" value="' . $almacen->id . '" name="almacen[]" hidden/><input name="codigo_nro_lote[]" value="' . $almacen->nombre . '" hidden/>' . $almacen->nombre . '</td>';
                                $num++;
                                echo $fila;
                            }
                            ?>

                        </table>
                    </div>
                </div>

                <div class="panel-footer" align="center">
                    <a href="<?= site_url('inventory/inventory_output') ?>" type="button"
                       class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

