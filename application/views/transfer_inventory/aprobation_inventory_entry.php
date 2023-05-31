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
                <input type="hidden" class="form-control " name="inventory_entry_id" id="inventory_entry_id"
                                       value="<?= $transfer_inventory_entry->id ?>">
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="nro">Nro. Ingreso Traspaso:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="nro" id="nro"
                                       value="<?= $transfer_inventory_entry->nro ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="tipo">Tipo Salida:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="tipo" id="tipo"
                                       value="<?= $transfer_inventory_entry->nombre_tipo_ingreso ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="origen">Sucursal Origen:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="origen" id="origen"
                                       value="<?= $transfer_inventory_entry->sucursal_origen ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="destino">Sucursal Destino:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="destino" id="destino"
                                       value="<?= $transfer_inventory_entry->sucursal_destino ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="almacen">Almacen Destino:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="almacen" id="almacen"
                                       value="<?= $transfer_inventory_entry->almacen_destino ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="date">Fecha Salida:</label></strong>
                            <div class="form-group">
                                <input type="text" class="form-control " name="date" id="date"
                                       value="<?= $transfer_inventory_entry->fecha_modificacion ?>"
                                       readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <strong><label for="description">Observacion:</label></strong>
                            <div class="form-group">
                                <div class="form-line">
                                            <textarea rows="2" class="form-control no-resize" name="description"
                                                      id="description"
                                                      readonly><?= $transfer_inventory_entry->observacion ?></textarea>
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
                                        <th class="text-center" style="width: 15%"><b>Codigo</b></th>
                                        <th class="text-center" style="width: 35%"><b>Producto</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. costo.</b></th>
                                        <th class="text-center" style="width: 10%"><b>P. venta.</b></th>
                                        <!-- <th class="text-center" style="width: 10%"><b>Lote.</b></th> -->
                                        <!-- <th class="text-center" style="width: 15%"><b>F. vencimiento.</b></th> -->
                                        <th class="text-center" style="width: 10%"><b>Cantidad </b></th>
                                        <th class="text-center" style="width: 20%"><b>Almacen</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <?php
                                    $num = 1;
                                    foreach ($inventory_entry_detail as $row) {
                                        $inventory = $this->inventory_model->get_inventory_new_by_id($row->id);
                                        $producto = $this->product_model->get_product_entity_by_id($inventory->producto_id);
                                        $almacen = $this->warehouse_model->get_warehouse_id($inventory->almacen_id);

                                        $fila = '<tr id="' . $num . '">';
                                        $fila .= '<td align="center">' . $row->codigo_producto . '</td>';
                                        $fila .= '<td align="left">' . $producto->nombre_comercial . '</td>';
                                        $fila .= '<td align="center">' . $row->precio_costo . '</td>';
                                        $fila .= '<td align="center">' . $row->precio_venta . '</td>';
                                        // $fila .= '<td align="center">' . $inventory->codigo . '</td>';
                                        // $fila .= '<td align="center">' . $inventory->fecha_vencimiento . '</td>';
                                        $fila .= '<td align="center">' . $row->cantidad . '</td>';
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
                <a type="button" id="btn_aprobation" class="btn btn-success waves-effect"><i class="fa fa-thumbs-up"></i> Aprobar </a>
                <a type="button" id="btn_no_aprobation" class="btn btn-info waves-effect"><i class="fa fa-thumbs-down"></i> Descartar </a>
                <a href="<?= site_url('transfer_inventory/transfer_inventory_entry') ?>" type="button"
                   class="btn btn-danger waves-effect no-modal"><i class="fa fa-times"></i>Salir</a>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/transfer_inventory.js'); ?>"></script>

