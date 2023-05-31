<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */
$url_action = 'reception/register_reception';
$titulo = "Nuevo Registro de Recepcion";
if(isset($reception)){
    $url_action = 'reception/edit_reception';
    $titulo = "DATOS DE LA RECEPCION : ".$reception->codigo_recepcion;
}
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"><?= $titulo?></h4>
            </div>
            <form id="frm_register_recepcion" name="frm_register_recepcion" action="<?= site_url($url_action) ?>"
                  method="post">
                <input type="text"  name="id_reception" id="id_reception" value="<?= isset($reception) ? $reception->id : '' ?>" hidden>
                <input type="text"  name="reference_id" id="reference_id" value="<?= isset($reception) ? $reception->referencia_id : '' ?>" hidden>
                <?php $this->view('reception/form_content') ?>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="register_device" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Registro de Nuevo Dispositivo</h4>
            </div>
            <form id="frm_register_devices" class="frm_modal" action="<?= site_url('customer/register_device_customer') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca 
                                <a data-toggle="modal" data-target="#register_brand_reception" class="btn btn-info btn-xs"
                                title="Nuevo Marca"><i class="fa fa-plus"></i></a>
                                <a style="height: 100%" class="btn btn-success btn-xs" id="btn_refresh_select_brand"
						            title="Actualizar"><i class="material-icons">cached</i></a>
                            </label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select style="width: 100%" class="form-control select2" id="brand" name="brand">
                                        <option value="0">Seleccione una marca</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Modelo
                            <!-- <a data-toggle="modal" data-target="#register_model_reception" class="btn btn-info btn-xs"
                                title="Nuevo Marca"><i class="fa fa-plus"></i></a> -->
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" id="btn_new_model_reception" >
                                <i class="fa fa-plus"></i>
                                </button>
                            </label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select style="width: 100%" class="form-control select2" id="model" name="model">
                                        <option value="0">Seleccione un modelo</option>
                                    </select>
                                </div>
                                <!--<span><em><b>Modelo separado por (|) del producto o dispositivo</b></em></span>-->
                            </div>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Imei1</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="imei_phone" name="imei_phone"
                                           value=""
                                           placeholder="Ingrese el IMEI del dispotivo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Imei2</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="imei2" name="imei2"
                                           value=""
                                           placeholder="Ingrese el IMEI del dispotivo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Contraseña</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="number_phone" name="number_phone"
                                           value=""
                                           placeholder="Contraseña">
                                </div>
                                <!--<span><em><b>Modelo separado por (|) del producto o dispositivo</b></em></span>-->
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Fecha Compra</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="date" class="form-control" id="date_buy" name="date_buy">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_register_device" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Formulario Modal para registro de nueva solucion-->
<div class="modal fade" id="register_solution" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Nueva Solucion</h4>
            </div>
            <form id="frm_new_solution" class="frm_modal" action="<?= site_url('solution/register_solution') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           placeholder="Ingrese el nombre de una nueva solucion para el servicio"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion" name="descripcion"
                                           placeholder="Descripcion de la solucion que esta por crear"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_solution" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Formulario Modal para registro de nueva Falla-->
<div class="modal fade" id="register_failure" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Registro de Nueva Falla</h4>
            </div>
            <form id="frm_new_failure" class="frm_modal" action="<?= site_url('failure/register_failure') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                            placeholder="Ingrese el nombre de una nueva falla para el servicio"
                                            value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion" name="descripcion"
                                            placeholder="Descripcion de la falla que esta por crear"
                                            value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_register_failure" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_new_failure" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="register_brand_reception" tabindex="-1"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Registro Marca</h4>
            </div>
            <form id="frm_new_brand_reception" class="frm-modal" action="<?= site_url('brand_reception/register_brand_reception') ?>"
                  method="post">
                <div class="modal-body">
                    
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="name_brand" name="name_brand" placeholder="Ingrese el nombre"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="description_brand" name="description_brand" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_brand_reception" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_registro_model_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Regristo de Modelo</h4>
            </div>
            <form id="frm_new_model_reception" action="<?= site_url('model_reception/register_model_reception') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="brand_reception_model" name="brand_reception_model" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="name_model" name="name_model" placeholder="Ingrese el nombre"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix" hidden>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="description_model" name="description_model" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_model_reception" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- <div class="modal fade" id="register_reference" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Registro de referencia:</h4>
            </div>
            <form id="frm_register_reference" class="frm_modal" action="<?= site_url('reference/register_reference') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre:</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="name_reference" name="name_reference" placeholder="Ingrese un nombre">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono:</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control"  id="phone_reference" name="phone_reference" placeholder="Numero de telefono">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion:</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="address_reference" name="address_reference"
                                           placeholder="Direccion de la referencia">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <a type="button" class="btn btn-danger waves-effect close_modal" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</a>
                </div>
            </form>
        </div>
    </div>
</div> -->
<!-- //</editor-fold> -->


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

                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" hidden>

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
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" hidden>
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

<div class="modal fade" id="add_new_item" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_product">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Agregar repuesto </h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
							<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<div class="form-line">
										<label class="control-label" for="brand_product">Almacen*: </label>
										<div class="form-line">
											<select style="width: 100%" class="form-control select2" id="warehouse_id" name="warehouse_id">
												<option value="">Seleccione una opcion</option>
												<?php if (isset($list_warehouse)) {
													foreach ($list_warehouse as $row_warehouse):
														$html_option = '';
														$html_option .= '<option value="' . $row_warehouse->id . '">' . $row_warehouse->nombre . '</option>';
														echo $html_option;
													endforeach;
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="brand_product">Marca*: </label>
                                        <select style="width: 100%" class="form-control select2" id="brand_product" name="brand_product">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($list_brand)) {
                                                foreach ($list_brand as $row_brand):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_brand->id . '">' . $row_brand->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="model_product">Modelo*: </label>
                                        <select style="width: 100%" class="form-control select2" id="model_product" name="model_product">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="producto_order_work">Repuesto*:</label>
                                        <input type="text" name="product_selected" id="product_selected" hidden>
                                        <input type="text" class="form-control" name="producto_order_work"
                                               id="producto_order_work" placeholder="Nombre del producto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio costo*:</label>
                                        <input type="text" class="form-control" name="price_product" id="price_product"
                                               placeholder="precio unitario" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio venta*:</label>
                                        <input type="text" class="form-control" name="price_sale" id="price_sale"
                                               placeholder="precio venta" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="quantity_product">Cantidad*:</label>
                                        <input type="text" class="form-control" name="quantity_product"
                                               id="quantity_product" placeholder="ingrese cantidad"
                                               onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" ><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?= base_url('jsystem/reception.js') ?>"></script>
<script src="<?= base_url('jsystem/failure.js') ?>"></script>
<script src="<?= base_url('jsystem/solution.js') ?>"></script>
<script src="<?= base_url('jsystem/brand_reception.js') ?>"></script>
<script src="<?= base_url('jsystem/model_reception.js') ?>"></script>

