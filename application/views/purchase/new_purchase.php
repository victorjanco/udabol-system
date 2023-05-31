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
$purchase_subtotal = "0.00";
$purchase_off1 = "0.00";
$purchase_off2 = "0.00";
$purchase_off3 = "0.00";
$purchase_total = "0.00";
$purchase_detail = array();

$title = "NUEVO REGISTRO DE COMPRA";
$url_action = site_url('purchase/register_purchase');

if (isset($purchase)) {
    $title = "EDITAR COMPRA";
    $url_action = site_url('purchase/update_purchase');

    $purchase_id = $purchase["id"];
    $purchase_number = $purchase["nro_compra"];
    $purchase_type = $purchase["tipo_compra"];
    if (isset($provider)) {
        $purchase_provider_id = $provider["id"];
        $purchase_provider_name = $provider["nombre"];
        $purchase_provider_nit = $provider["nit"];
    }
    $purchase_description = $purchase["observacion"];
    $purchase_subtotal = $purchase["monto_subtotal"];
    $purchase_off1 = $purchase["descuento_uno"];
    $purchase_off2 = $purchase["descuento_dos"];
    $purchase_off3 = $purchase["descuento_tres"];
    $purchase_total = $purchase["monto_total"];
}

if (isset($detail))
    $purchase_detail = $detail;

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <!--<div class="header bg-orange">
                <h2><?= $title ?></h2>
            </div>-->
            <div class="panel-heading cabecera_frm ">
                <div style="">
                    <h2 class="panel-title titulo_frm"><?= $title ?></h2>
                </div>

            </div>


            <form id="frm_register_purchase" name="frm_register_purchase"
                  action="<?= $url_action ?>"
                  method="post">

                <div class="body">
                    <!--<legend align="left">Datos de la compra</legend>-->
                    <input type="hidden" id="purchase_id" name="purchase_id" value="<?= $purchase_id ?>">
                    <div class="row clearfix">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>N&uacute;mero de la compra</b>
                                    <input type="number" name="purchase_number" id="purchase_number"
                                           class="form-control" value="<?= $purchase_number ?>"
                                           placeholder="Ingrese el nro. de la compra">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Tipo de la compra</b>
                                    <select name="purchase_type" id="purchase_type" class="form-control show-tick">
                                        <option value="0">-- Seleccione --</option>
                                        <option value="1" <?= $purchase_type == '1' ? "selected" : "" ?>>Con factura</option>
                                        <option value="2" <?= $purchase_type == '2' ? "selected" : "" ?>>Sin factura</option>
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
                                <div class="form-line">
                                    <b>Nit proveedor</b>
                                    <input type="number" name="purchase_provider_nit" id="purchase_provider_nit"
                                           class="form-control search_provider search_provider_nit"
                                           placeholder="Ingrese el nit del proveedor"
                                           value="<?= $purchase_provider_nit ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Nombre proveedor</b>
                                    <input type="text" class="form-control search_provider search_provider_name"
                                           name="purchase_provider_name" id="purchase_provider_name"
                                           placeholder="Ingrese el nombre del proveedor"
                                           value="<?= $purchase_provider_name ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <b>Almacen</b>
                                <div class="form-line">
                                    <select name="warehouse_id" id="warehouse_id" class="form-control" required autofocus>
                                        <option value="">Seleccione un almacen...</option>
                                        <?php foreach ($list_warehouse as $row_warehouse): ?>
                                            <option value="<?= $row_warehouse->id ?>"><?= $row_warehouse->nombre ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Glosa</b>
                                    <textarea rows="2" class="form-control no-resize" name="purchase_description"
                                              id="purchase_description"
                                              placeholder="Ingrese su glosa"><?= $purchase_description ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row clearfix">
                        <div class="col-sm-8"><h3 class="lead">Detalle de la compra</h3></div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <button type="button" class="btn btn-block btn-lg btn-success waves-effect"
                                        data-toggle="modal" data-target="#modal_add_product">
                                    <i class="material-icons">add</i>
                                    <span>AGREGAR PRODUCTO</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table_detail_purchase" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Codigo</th>
                                <th>Nombre producto</th>
                                <th>P. unitario.</th>
                                <th>P. Costo</th>
                                <th>P. venta</th>
                                <th>Cantidad</th>
                                <th>Monto</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $number_row = 0;
                            foreach ($purchase_detail as $item) {
                                $number_row++;
                                $product_id = $item["producto_id"];
                                $product_code = $item["producto_codigo"];
                                $product_name = $item["producto_nombre"];

                                $unit_price = $item["precio_unitario"];
                                $quantity = $item["cantidad"];
                                $total = $unit_price * $quantity;
                                $additional_cost = $item["costo_adicional"];
                                $storage_cost = $item["costo_almacen"];
                                $cost_price = $item["precio_costo"];
                                $sale_price = $item["precio_venta"];
                                $markup = ""; // $item["markup"];
                                $wholesale_price = ""; // $item["wholesale_price"];

                                $html = '<tr ';
                                $html .= 'data-product_id="' . $product_id . '" ';
                                $html .= 'data-unit_price="' . $unit_price . '" ';
                                $html .= 'data-quantity="' . $quantity . '" ';
                                $html .= 'data-total="' . $total . '" ';
                                $html .= 'data-additional_cost="' . $additional_cost . '" ';
                                $html .= 'data-storage_cost="' . $storage_cost . '" ';
                                $html .= 'data-cost_price="' . $cost_price . '" ';
                                $html .= 'data-markup="' . $markup . '" ';
                                $html .= 'data-sale_price="' . $sale_price . '" ';
                                $html .= 'data-wholesale_price="' . $wholesale_price . '" ';
                                $html .= '>';
                                $html .= '<td>' . $number_row . '</td>';
                                $html .= '<td>' . $product_code . '</td>';
                                $html .= '<td>' . $product_name . '</td>';
                                $html .= '<td>' . $unit_price . '</td>';
                                $html .= '<td>' . $cost_price . '</td>';
                                $html .= '<td>' . $sale_price . '</td>';
                                $html .= '<td>' . $quantity . '</td>';
                                $html .= '<td>' . $total . '</td>';
                                $html .= '<td style="text-align:center">' .
                                    '<button type="button" class="btn btn-danger" onclick="delete_row(' . $number_row . ')">' .
                                    '<i class="fa fa-minus"></i>&nbsp;Eliminar</button></td>';
                                $html .= '</tr>';

                                echo $html;
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right"><b>SUB TOTAL</b></td>
                                <td colspan="2"><input type="number" step="any" id="purchase_subtotal"
                                                       style="background-color: rgb(235, 235, 228);"
                                                       name="purchase_subtotal" value="<?= $purchase_subtotal ?>"
                                                       readonly></td>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right"><b>DESCUENTO 1</b></td>
                                <td colspan="2"><input type="number" step="any" id="purchase_off1" name="purchase_off1"
                                                       value="<?= $purchase_off1 ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right"><b>DESCUENTO 2</b></td>
                                <td colspan="2"><input type="number" step="any" id="purchase_off2" name="purchase_off2"
                                                       value="<?= $purchase_off2 ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right"><b>DESCUENTO 3</b></td>
                                <td colspan="2"><input type="number" step="any" id="purchase_off3" name="purchase_off3"
                                                       value="<?= $purchase_off3 ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right"><b>TOTAL</b></td>
                                <td colspan="2"><input type="number" step="any" id="purchase_total"
                                                       style="background-color: rgb(235, 235, 228);"
                                                       name="purchase_total" value="<?= $purchase_total ?>"
                                                       readonly></td>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="body">
                    <button id="btn_accion_user" class="btn btn-success waves-effect no-modal" type="submit">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <a href="<?= site_url('purchase/index') ?>" class="btn btn-danger waves-effect" type="submit">
                        <i class="fa fa-times"></i> Cancelar </a>
                </div>

            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para agregar producto-->
<div class="modal fade" id="modal_add_product" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_product">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">
                    </button>
                    <h4 class="modal-title" id="defaultModalLabel">AGREGAR PRODUCTO</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="product_id" class="search_product_id" hidden>
                                    <input type="text" id="product_code" class="search_product_code" hidden>
                                    <b>Producto</b>
                                    <input type="text" class="form-control search_product search_product_name"
                                           id="product_name"
                                           placeholder="Ingrese el c&oacute;digo o nombre del producto" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio unitario</b>
                                    <input type="number" step="any" class="form-control" value="0" id="unit_price"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Cantidad</b>
                                    <input type="number" step="any" class="form-control" value="0" id="quantity"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Monto total</b>
                                    <input type="number" step="any" class="form-control" value="0" id="total" required
                                           readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Costo adicional</b>
                                    <input type="number" step="any" class="form-control" value="0" id="additional_cost"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Costo almac&eacute;n</b>
                                    <input type="number" step="any" class="form-control" value="0" id="storage_cost"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio costo</b>
                                    <input type="number" step="any" class="form-control search_product_cost_price"
                                           value="0"
                                           id="cost_price" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Margen</b>
                                    <input type="number" step="any" class="form-control" value="0" id="markup" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio venta</b>
                                    <input type="number" step="any" class="form-control search_product_sale_price"
                                           value="0" id="sale_price" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio venta por mayor</b>
                                    <input type="number" step="any" class="form-control search_product_wholesale_price"
                                           value="0" id="wholesale_price" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect" id="btn_modal_add_row">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                    <button id="close_modal_new_provider" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?= base_url('jsystem/purchase.js') ?>"></script>
