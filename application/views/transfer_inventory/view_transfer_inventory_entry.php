<?php
$this->load->model('Inventory_model');
$this->load->model('Product_model');
$this->load->model('Warehouse_model');
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h2 class="panel-title titulo_frm">Datos del Traspaso Ingreso de Inventario</h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="nro">Nro. Ingreso Traspaso:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="nro" id="nro"
                                       value="<?= $transfer_inventory_output->nro ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="tipo">Tipo Salida:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="tipo" id="tipo"
                                       value="<?= $transfer_inventory_output->nombre_tipo_ingreso ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="origen">Sucursal Origen:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="origen" id="origen"
                                       value="<?= $transfer_inventory_output->sucursal_origen ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="destino">Sucursal Destino:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="destino" id="destino"
                                       value="<?= $transfer_inventory_output->sucursal_destino ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="almacen">Almacen Destino:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="almacen" id="almacen"
                                       value="<?= $transfer_inventory_output->almacen_destino ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="date">Fecha Salida:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="date" id="date"
                                       value="<?= $transfer_inventory_output->fecha_modificacion ?>"
                                       readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="description">Observacion:</label></strong>
                            <div class="form-group">
                                <div class="form-line">
                                            <textarea rows="2" class="form-control no-resize" name="description"
                                                      id="description"
                                                      readonly><?= $transfer_inventory_output->observacion ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h4 align="center">DETALLE DEL TRASPASO DE INGRESO DE INVENTARIO</h4>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        $inventory = $this->inventory_model->get_inventory_new_by_id($row->id);
                                        $producto = $this->product_model->get_product_entity_by_id($inventory->producto_id);
                                        $almacen = $this->warehouse_model->get_warehouse_id($inventory->almacen_id);

                                        $fila = '<tr id="' . $num . '">';
                                        $fila .= '<td align="center">' . $producto->codigo . '</td>';
                                        $fila .= '<td align="left">' . $producto->nombre_comercial . '</td>';
                                        $fila .= '<td align="center">' . $row->precio_costo . '</td>';
                                        $fila .= '<td align="center">' . $row->precio_venta . '</td>';
                                        $fila .= '<td align="center">' . $inventory->codigo . '</td>';
                                        // $fila .= '<td align="center">' . $inventory->fecha_vencimiento . '</td>';
                                        $fila .= '<td align="center">' . $row->cantidad_ingresada . '</td>';
                                        $fila .= '<td>'.$almacen->nombre . '</td>';
                                        $num++;
                                        echo $fila;
                                    }
                                    ?>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" align="center">
                <a href="<?= site_url('transfer_inventory/transfer_inventory_entry') ?>" type="button"
                   class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>Cancelar</a>
            </div>
        </div>
    </div>
</div>

