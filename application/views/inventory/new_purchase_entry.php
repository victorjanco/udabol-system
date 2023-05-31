<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */

$purchase_id = "-1";
$purchase_number = "";
$purchase_type = "0";
$purchase_provider_id = "";
$purchase_provider_name = "";
$purchase_provider_nit = "";
$purchase_description = "";
$purchase_detail = array();
$purchase_observacion = "";
$purchase_fecha_registro = "";

if (isset($purchase)) {
    $title = "INGRESO DE INVENVATARIO POR COMPRA";
    $url_action = site_url('inventory/register_inventory_purchase');

    $purchase_id = $purchase["id"];
    $purchase_number = $purchase["nro_compra"];
    $purchase_type = $purchase["tipo_compra"];
    if (isset($provider)) {
        $purchase_provider_id = $provider["id"];
        $purchase_provider_name = $provider["nombre"];
        $purchase_provider_nit = $provider["nit"];
        $purchase_observacion = $purchase["observacion"];
    }
    $purchase_description = $purchase["observacion"];
}

if (isset($detail))
    $purchase_detail = $detail;

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2><b><?= $title?></b></h2>
            </div>
            <form id="frm_new_entry_purchase" name="frm_new_entry_purchase" action="<?= $url_action ?>" method="post">
                <div class="body">
                    <div class="row clearfix">
                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                                <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                    <label class="form-control-label" for="nombre">Glosa*:</label>
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="nombre" name="nombre" value="" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label" for="fecha_ingreso">Fecha de ingreso*:</label>
                                </div>
                                <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">date_range</i>
                                                </span>
                                        <div class="form-line">
                                            <input type="date" class="form-control date" placeholder="Ex: 30/07/2016"
                                                   name="fecha_ingreso" id="fecha_ingreso" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="header">
                    <h2><b><?= 'Datos de la compra' ?></b></h2>
                </div>
                <div class="body">
                    <!--<legend align="left">Datos de la compra</legend>-->
                    <input type="hidden" id="purchase_id" name="purchase_id" value="<?= $purchase_id ?>">
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="">
                                    <b>N&uacute;mero de la compra</b>
                                    <input name="purchase_number" id="purchase_number"
                                           class="form-control " value="<?= $purchase_number ?>"
                                           placeholder="Ingrese el nro. de la compra" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="">
                                    <b>Tipo de la compra</b>
                                    <select name="purchase_type" id="purchase_type" class="form-control show-tick" disabled>
                                        <option value="0">-- Seleccione --</option>
                                        <option value="Con factura" <?= $purchase_type == 'Con factura' ? "selected" : "" ?>>Con factura</option>
                                        <option value="Sin factura" <?= $purchase_type == 'Sin factura' ? "selected" : "" ?>>Sin factura</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <input type="hidden" id="purchase_provider_id" name="purchase_provider_id"
                               class="search_provider_id" value="<?= $purchase_provider_id ?>" hidden>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="">
                                    <b>Nit proveedor</b>
                                    <input type="number" name="purchase_provider_nit" id="purchase_provider_nit"
                                           class="form-control search_provider search_provider_nit"
                                           placeholder="Ingrese el nit del proveedor"
                                           value="<?= $purchase_provider_nit ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="">
                                    <b>Nombre proveedor</b>
                                    <input type="text" class="form-control search_provider search_provider_name"
                                           name="purchase_provider_name" id="purchase_provider_name"
                                           placeholder="Ingrese el nombre del proveedor"
                                           value="<?= $purchase_provider_name ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="">
                                    <b>Glosa</b>
                                    <textarea rows="2" class="form-control no-resize" name="purchase_description"
                                              id="purchase_description"
                                              placeholder="Ingrese su glosa"
                                              readonly><?= $purchase_description ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row clearfix">
                        <div class="col-sm-8"><h3 class="lead">Detalle de la compra</h3></div>
                    </div>

                    <div class="table-responsive">
                        <input type="text" value="<?= $type_inventory_entry_id; ?>" hidden
                               name="type_inventory_entry_id" id="type_inventory_entry_id">
                        <table id="lista_detalle" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 10%">Codigo</th>
                                <th class="text-center" style="width: 25%">Producto</th>
                                <th class="text-center" style="width: 10%">P. venta</th>
                                <th class="text-center" style="width: 10%">P. compra</th>
                                <th class="text-center" style="width: 10%">Cantidad disponible</th>
                                <th class="text-center" style="width: 10%">cantidad</th>
                                <th class="text-center" style="width: 15%">Almacen</th>
                                <th class="text-center" style="width: 10%">Nro lote</th>
                               <!-- <th class="text-center" style="width: 10%">proveedor</th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $number_row = 0;

                            foreach ($purchase_detail as $item) {
                                $html_warehouse = '<select required class="form-control" name="almacen[]">';
                                $html_warehouse .= '<option value=""> Seleccione almacen</option>';
                                foreach ($warehouse as $row_warehouse):
                                    $html_warehouse .= '<option value="' . $row_warehouse->id . '">' . $row_warehouse->nombre . '</option>';
                                endforeach;
                                $html_warehouse .= '</select>';

                                $html_nro_lote = '<input type="text" class="form-control" required name="codigo_nro_lote[]">';
                                $number_row++;
                                $product_id = $item["producto_id"];
                                $product_code = $item["producto_codigo"];
                                $product_name = $item["producto_nombre"];

                                $unit_price = $item["precio_unitario"];
                                $quantity = $item["cantidad_correcta"];
                                $quantity_available = $item["cantidad_correcta"]-$item["cantidad_ingresada_inventario"];
                                $total = $unit_price * $quantity;
                                $additional_cost = $item["costo_adicional"];
                                $storage_cost = $item["costo_almacen"];
                                $cost_price = $item["precio_costo"];
                                $sale_price = $item["precio_venta"];
                                $markup = "";
                                $wholesale_price = "";
                                //if($quantity_available>0){
                                    $html = '<tr>';
                                    $html .= '<td align="center"><input type="text" value="' . $product_name . '" name="descripcion[]" hidden/><input type="text" name="producto_id[]" value="' . $product_id . '" hidden>' . $product_code . '</td>';
                                    $html .= '<td align="center"><input type="text" name="detalle_compra_id[]" value="'.$item["id"].'" hidden>' . $product_name . '</td>';
                                    $html .= '<td align="right"><input  value="' . $sale_price . '"  name="precio_venta[]" hidden/>' . $sale_price . '</td>';
                                    $html .= '<td align="right"><input  value="' . $cost_price . '" name="precio_compra[]" hidden/>' . $cost_price . '</td>';
                                    $html .= '<td align="right">' . $quantity_available . '</td>';
                                    $html .= '<td align="right"><input type="number" value="" name="cantidad[]" required min="1" max="'.$quantity_available.'" class="form-control"/></td>';
                                    $html .= '<td>' . $html_warehouse . '</td>';
                                    $html .= '<td align="right">' . $html_nro_lote . '</td>';
                                    /*$html .= '<td><input type="text" value="' . $purchase_provider_id . '" name="proveedor[]" hidden/>' . $purchase_provider_name . '</td>';*/
                                    $html .= '</tr>';

                                    echo $html;
                                //}
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btn_accion_user" class="btn btn-success waves-effect no-modal" type="submit">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <a href="<?= site_url('inventory') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Cancelar </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/inventory.js') ?>"></script>
