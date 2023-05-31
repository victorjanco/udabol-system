<?php
/**
 * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 25/07/2017
 * Time: 05:38 PM
 */


$title = "NUEVA VENTA";
$url_action = site_url('sale/register_sale');
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm ">
            <div style="">
                <h2 class="panel-title titulo_frm"><?= $title ?></h2>
            </div>
            <div><label style="text-align: right; color: white;font-size: 12pt">Atajos</label>
            </div>
        </div>
        <form id="frm_register_sale" name="frm_register_sale"
              action="<?= $url_action ?>"
              method="post">
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" id="id_customer" name="id_customer" hidden>
                                <b>Nit Cliente</b>
                                <input type="number" name="nit" id="nit"
                                       class="form-control search_nit"
                                       placeholder="Ingrese el nit del cliente"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <b>Nombre Cliente</b>
                                <input type="text" class="form-control search_nombre_factura"
                                       name="nombre_factura" id="nombre_factura"
                                       placeholder="Ingrese el nombre del Cliente"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <b>Tipo Cliente</b>
                                <input type="number" id="type_customer" name="type_customer" value="0" hidden>
                                <input type="text" name="type_customer_sale" id="type_customer_sale"
                                       class="form-control"
                                       value="" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-block btn-info waves-effect" data-toggle="modal"
                                    data-target="#modal_new_customer" id="new_customer">
                                <i class="material-icons">group_add</i>
                                <span>NUEVO CLIENTE</span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label style="font-size: 20pt;">Detalle de la Venta</label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-block btn-success waves-effect"
                                    data-toggle="modal" data-target="#modal_add_product_sale">
                                <i class="material-icons">add_shopping_cart</i>
                                <span>AGREGAR PRODUCTO</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table_detail_sale"
                           class="table table-striped table-hover table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th width="5%">Nro.</th>
                            <th width="20%">Codigo</th>
                            <th width="25%">Nombre Producto</th>
                            <th width="10%">P. Unit.</th>
                            <th width="10%">Cantidad</th>
                            <th width="15%">Monto</th>
                            <th width="5%">Opcion</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="1" style="text-align: right"><b>SUB TOTAL</b></td>
                            <td colspan="1"><input class="form-control" type="number" step="any"
                                                   id="sale_subtotal"
                                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                                   name="sale_subtotal" value=""
                                                   readonly></td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="1" style="text-align: right"><b>DESCUENTO</b></td>
                            <td colspan="1"><input class="form-control" type="number" step="any"
                                                   id="sale_discount"
                                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                                   name="sale_discount" value="0" readonly></td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="1" style="text-align: right"><b>TOTAL</b></td>
                            <td colspan="1"><input class="form-control" type="number" step="any" id="sale_total"
                                                   style="background-color: rgb(235, 235, 228); text-align: right;"
                                                   name="sale_total" value=""
                                                   readonly></td>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="panel-footer" align="center">
                <button id="btn_accion_user" class="btn btn-success waves-effect no-modal" type="submit">
                    <i class="fa fa-save"></i> Guardar
                </button>
                <a id="cancel_sale" href="<?= site_url('sale') ?>" class="btn btn-danger waves-effect" type="submit">
                    <i class="fa fa-times"></i> Cancelar </a>
            </div>

        </form>
    </div>
</div>

<!--Formulario Modal para agregar producto-->
<div class="modal fade" id="modal_add_product_sale" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_product_sale">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">
                    </button>
                    <h4 class="modal-title" id="defaultModalLabel">AGREGAR PRODUCTO</h4>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Sucursal</b>
                                    <select id="branch_office_sale" name="branch_office_sale" class="form-control">
                                        <option value="<?= get_branch_id_in_session(); ?>"><?= get_branch_office_name_in_session(); ?></option>
                                        <?php foreach ($branch_office_for_add as $branch_office_list) :
                                            if (get_branch_id_in_session() != $branch_office_list->id) {
                                                ?>
                                                <option value="<?= $branch_office_list->id; ?>"><?= $branch_office_list->nombre_comercial; ?></option>
                                            <?php } endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Almacen</b>
                                    <select id="warehouse_sale" name="warehouse_sale" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Tipo Producto</b>
                                    <select id="subgroup_sale" name="subgroup_sale" class="form-control">
                                        <option value="0">Todos</option>
                                        <?php foreach ($subgroup_for_add as $subgroup_list) : ?>
                                            <option value="<?= $subgroup_list->id; ?>"><?= $subgroup_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row clearfix">
                        <input type="text" id="product_id_sale" name="product_id_sale" class="search_product_id" hidden>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Codigo de Producto</b>
                                    <input type="text" id="product_code_sale"
                                           class="form-control search_product search_code_product_sale"
                                           placeholder="Ingrese c&oacute;digo del producto">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Nombre del Producto</b>
                                    <input type="text" class="form-control search_name_product_sale"
                                           id="product_name_sale" name="product_name_sale"
                                           placeholder="Ingrese nombre del producto" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Stock Disponible</b>
                                    <input type="number" id="product_stock_sale" class="form-control" align="center"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Cantidad</b>
                                    <input type="number" min="1" id="product_quantity_sale" name="product_quantity_sale" class="form-control"
                                           align="center">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio</b>
                                    <input type="text" id="product_price_sale" name="product_price_sale" class="form-control" align="right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect" id="btn_modal_add_row_sale">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                    <button id="close_modal_add_row_sale" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!--Formulario Modal para Registrar Nuevos Clientes-->
<div class="modal fade" id="modal_new_customer" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="frm_add_customer" action="<?= site_url('customer/register_customer') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">
                    </button>
                    <h4 class="modal-title" id="defaultModalLabel">Nuevo Cliente</h4>
                </div>
                <div class="modal-body">
                    <h3>Datos para su Factura</h3>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="nit">NIT </label>
                                    <input type="text" class="form-control" id="nit" name="nit"
                                           placeholder="Nit para facturar"
                                           title="Nit para facturar">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="nombre_factura">Nombre factura</label>
                                    <input type="text" class="form-control" id="nombre_factura" name="nombre_factura"
                                           placeholder="Nombre o Razon Social para facturar"
                                           title="Nombre Completo para la factura">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Datos para su Servicio</h3>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="ci">CI</label>
                                    <input type="text" class="form-control" id="ci" name="ci"

                                           placeholder="Carnet de Identidad del Cliente"
                                           title="Carnet de Identidad del Cliente">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           placeholder="Nombre Complero del Cliente"
                                           title="Nombre Complero del Cliente">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="telefono">Telefono 1</label>
                                    <input type="text" class="form-control" id="telefono1" name="telefono1"
                                           placeholder="Telefono del Cliente" title="Telefono del Cliente">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">

                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="telefono">Telefono 2</label>
                                    <input type="text" class="form-control" id="telefono2" name="telefono2"
                                           placeholder="Telefono del Cliente" title="Telefono del Cliente">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">

                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                           placeholder="Correo Electronico del Cliente"
                                           title="Correo Electronico del Cliente">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">

                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label" for="nombre">Direccion</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                           placeholder="Direccion del Cliente" title="Direccion del Cliente">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label">Tipo Cliente</label>
                                    <select id="tipo" name="tipo" class="form-control">
                                        <option value="0">Cliente Normal</option>
                                        <option value="1">Cliente por Mayor</option>
                                        <option value="2">Cliente Express</option>
                                        <option value="3">Cliente Laboratorio</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="form-control-label">Departamento</label>
                                    <select id="ciudad" name="ciudad" class="form-control">
                                        <option value="Santa Cruz">Santa Cruz</option>
                                        <option value="Cochabamba">Cochabamba</option>
                                        <option value="La Paz">La Paz</option>
                                        <option value="Tarija">Tarija</option>
                                        <option value="Beni">Beni</option>
                                        <option value="Chuquisaca">Chuquisaca</option>
                                        <option value="Pando">Pando</option>
                                        <option value="Oruro">Oruro</option>
                                        <option value="Potosi">Potosi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect" id="btn_modal_register_customer">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                    <button id="close_modal_new_customer" type="button"
                            class="btn btn-danger waves-effect close_modal"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="<?= base_url('jsystem/sale.js') ?>"></script>
