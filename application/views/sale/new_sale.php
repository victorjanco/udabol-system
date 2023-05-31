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
            <div>
                <h2 class="panel-title titulo_frm"><?= $title ?></h2>
            </div>
            <div><label style="text-align: right; color: white;font-size: 12pt">Atajos</label>
            </div>
        </div>
        <form id="frm_register_sale" name="frm_register_sale"
              action="<?= $url_action ?>"
              method="post">
            <input type="text" id="sale_type" name="sale_type" value="" hidden>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" id="id_customer" name="id_customer" hidden>

                                <b>CI. Cliente</b>
                                <input type="text" name="nit" id="nit"
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
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <b>Fecha</b>
                                <input type="date" class="form-control"
                                       name="date_expiration" id="date_expiration"
                                       value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" >
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
                    <!-- <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                       <div class="form-group">
                            <button type="button" class="btn btn-block btn-info waves-effect" data-toggle="modal"
                                    data-target="#modal_new_customer" id="new_customer">
                                <i class="material-icons">group_add</i>
                                <span>NUEVO CLIENTE</span>
                            </button>
                        </div>
                    </div> -->
                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <b>Glosa</b>
                                <input type="text" class="form-control"
                                       name="glosa" id="glosa"
                                       placeholder="Glosa"
                                       value="">
                            </div>
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
                            <td colspan="1"><input class="form-control" type="text" step="any"
                                                   id="sale_discount"
                                                   style="background-color: rgb(235, 235, 228);text-align: right;"
                                                   name="sale_discount" placeholder="0" value=""></td>
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
                <div align="center">
                    <div class="table-responsive" style="width:50%;">
                        <table class="table table-striped table-hover table-bordered table-responsive">
                            <thead>
                            <tr>
                                <th width="30%">FORMA PAGO</th>
                                <th width="70%">MONTOS</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> <input type="checkbox" id="check_efectivo" name="check_efectivo"
                                                class="filled-in"  value="1"/>
                                        <label for="check_efectivo">EFECTIVO</label>
                                    </td>
                                    <td>
                                        <label for="check_cheque">Total Pagar Bs</label>
                                        <input class="form-control" type="number" step="any"
                                        id="monto_efectivo" name="monto_efectivo"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                        placeholder="0" value=""
                                        >
<!--                                        <label for="check_cheque">Cambio Bs</label>-->
<!--                                        <input class="form-control" type="number" step="any"-->
<!--                                        id="monto_cambio" name="monto_cambio"-->
<!--                                        style="background-color: rgb(235, 235, 228);text-align: right;"-->
<!--                                         value="0"-->
<!--                                        >-->
                                    </td>
                                </tr>
                                <!--<tr>
                                    <td>  <input type="checkbox" id="check_contado" name="check_contado"
                                                class="filled-in"  value="1"/>
                                        <label for="check_contado">Bolivianos Bs</label>
                                    </td>
                                    <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                        id="monto_bs" name="monto_bs"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                         value="0"  
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td>  <input type="checkbox" id="check_dolares" name="check_dolares"
                                                class="filled-in"  value="1"/>
                                        <label for="check_dolares">DOLARES $</label>
                                    </td>
                                    <td> <label for="check_cheque">Tipo Cambio <?= $change_type->monto_cambio_venta ?></label>
                                        <input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                        id="monto_sus" name="monto_sus"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                         value="0"
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td> <input type="checkbox" id="check_tarjeta" name="check_tarjeta"
                                                class="filled-in"  value="1"/>
                                        <label for="check_tarjeta">TARJETA</label>
                                    </td>
                                    <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                        id="monto_tarjeta" name="monto_tarjeta"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                         value="0"
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" id="check_cheque" name="check_cheque"
                                                class="filled-in"  value="1"/>
                                        <label for="check_cheque">TRANSFERENCIA</label>
                                    </td>
                                    <td><input class="form-control" type="text" step="any" onkeypress="onKeyPressAmount(event)"
                                        id="monto_cheque" name="monto_cheque"
                                        style="background-color: rgb(235, 235, 228);text-align: right;"
                                         value="0"
                                        >
                                    </td>
                                </tr>-->
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="panel-footer" align="center">
                <button id="btn_new_note_sale" class="btn btn-success waves-effect no-modal" type="submit">
                    <i class="fa fa-save"></i> Nota Venta
                </button>
<!--                <button id="btn_new_invoice_sale" class="btn btn-primary waves-effect no-modal" type="submit" >-->
<!--                    <i class="fa fa-save"></i> Facturar-->
<!--                </button>-->
<!--                <button id="btn_new_credit_sale" class="btn btn-warning waves-effect no-modal" type="submit" hidden>-->
<!--                    <i class="fa fa-save"></i> Venta Credito-->
<!--                </button>-->
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
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" hidden>
                            <div class="form-group">
                                <b>GRUPO</b>
                                <div class="form-line">
                                    <select id="subgroup_sale" name="subgroup_sale" class="form-control select2" style="width: 100%">
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
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Codigo de Producto</b>
                                    <input type="text" id="product_code_sale"
                                           class="form-control search_product search_code_product_sale"
                                           placeholder="Ingrese c&oacute;digo del producto" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Nombre del Producto</b>
                                    <input type="text" class="form-control search_name_product_sale"
                                           id="product_name_sale" name="product_name_sale"
                                           placeholder="Ingrese nombre del producto" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Stock Disponible</b>
                                    <input type="number" id="product_stock_sale" class="form-control" align="center"
                                           disabled>
                                </div>
                            </div>
                        </div>                   
                    </div>
                    <div class="row clearfix">
                       
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Cantidad</b>
                                    <input type="number" min="1" id="product_quantity_sale" name="product_quantity_sale" class="form-control"
                                           align="center">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio</b>
                                    <input type="text" id="product_price_sale" name="product_price_sale" class="form-control" align="right" readonly>
                                    <!-- <input type="text" id="product_price_sale_view" name="product_price_sale_view" class="form-control" align="right" disabled> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Descuento</b>
                                    <input type="text" id="price_discount" name="price_discount" class="form-control" align="right" value="0" onkeypress="onKeyPressAmount(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Precio Con Descuento</b>
                                    <input type="text" id="product_price_discount" name="product_price_discount" class="form-control" align="right" onkeypress="onKeyPressAmount(event)" readonly>
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
                    <h3>Datos para su Cliente</h3>
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
                                        <option value="0">Cliente Diario</option>
                                        <option value="1">Cliente por Mayor</option>
                                        <!-- <option value="2">Cliente Express</option> -->
                                        <!-- <option value="3">Cliente Laboratorio</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" hidden>
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
<script>
    var cash_id = <?php echo json_encode($cash_id) ?>;
    var cash_aperture_id = <?php echo json_encode($cash_aperture_id) ?>;
    var change_type = <?php echo json_encode($change_type) ?>;
    // if(cash_id==false || cash_aperture_id==0){
    //     console.log(cash_id);
    //     console.log(cash_aperture_id);
    //     // alert('CAJA NO APERTURADA');
    //     swal({
    //         title: "Caja No aperturada",
    //         text: "La caja  no fue aperturada",
    //         type: "warning",
    //         showCancelButton: false,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Ok!",
    //         closeOnConfirm: false,
//         closeOnCancel: true
    //     },
    //     function (isConfirm) {
    //         $.redirect('/home');
    //     });
    //     // $.redirect('/home');
    // }else{
    //     // console.log(bank_account_cash_apertures);
    // }
</script>
<script src="<?= base_url('jsystem/sale.js') ?>"></script>
