<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 24/7/2017
 * Time: 10:25 AM
 */ ?>

<div class="block-header">
    <button class="btn btn-success" type="button" id="btn_new_warehouse"><i class="fa fa-plus"></i> Nuevo Almacen
    </button>
    <button class="btn bg-warning" type="button" id="btn_new_type_warehouse"><i class="fa fa-plus"></i> Nuevo Tipo
        Almacen
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Almacenes</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_warehouse" data-toggle="tab">Lista de
                            Almacenes</a></li>
                    <li role="presentation"><a href="#tab_type_warehouse" data-toggle="tab">Lista de Tipos de
                            Almacen</a></li>
                </ul>
                <!-- Tab panes -->
                <!--Lista de Almacen-->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="tab_warehouse">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table id="list_warehouse" class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th><b>SUCURSAL_ID</b></th>
                                <th style="width: 25%"><b>SUCURSAL</b></th>
                                <th><b>TIPO_ID</b></th>
                                <th style="width: 15%"><b> TIPO ALMACEN</b></th>
                                <th style="width: 20%"><b>NOMBRE</b></th>
                                <th><b>DESCRIPCIÓN</b></th>
                                <th style="width: 15%"><b>DIRECCIÓN</b></th>
                                <th style="width: 10%"><b>ESTADO</b></th>
                                <th style="width: 15%"><b> OPCIONES</b></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Lista de Tipo Almacen-->
                    <div role="tabpanel" class="tab-pane fade" id="tab_type_warehouse">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table style="width: 100%" id="list_type_warehouse"
                                   class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th><b>NOMBRE</b></th>
                                <th><b>DESCRIPCIÓN</b></th>
                                <th><b>ESTADO</b></th>
                                <th><b>OPCIONES</b></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de modicacion de almacen-->
<div class="modal fade" id="modal_new_warehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Almacen</h4>
            </div>
            <form id="frm_new_warehouse" action="<?= site_url('warehouse/register_warehouse') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="sucursal_almacen" name="sucursal_almacen" class="form-control">
                                        <option value="">--Seleccione Sucursal--</option>
                                        <?php foreach ($offices_for_new as $office_list) : ?>
                                            <option value="<?= $office_list->id; ?>"><?= $office_list->nombre_comercial; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo Almacen</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="tipo_almacen_almacen" name="tipo_almacen_almacen" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_almacen" name="nombre_almacen"
                                           placeholder="Ingrese el nombre del nuevo almacen"
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
                                    <input type="text" class="form-control" id="descripcion_almacen"
                                           name="descripcion_almacen"
                                           placeholder="Ingrese la descripcion del nuevo almacen"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="direccion_almacen"
                                           name="direccion_almacen"
                                           placeholder="Ingrese la direccion del nuevo almacen"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_warehouse" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de nuevo almacen-->
<div class="modal fade" id="modal_edit_warehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Almacen</h4>
            </div>
            <form id="frm_edit_warehouse" action="<?= site_url('warehouse/modify_warehouse') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <input type="text" id="id_almacen_edit" name="id_almacen_edit" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="sucursal_almacen_edit" name="sucursal_almacen_edit"
                                            class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo Almacen</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="tipo_almacen_almacen_edit" name="tipo_almacen_almacen_edit"
                                            class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_almacen_edit"
                                           name="nombre_almacen_edit"
                                           placeholder="Ingrese el nombre del nuevo almacen"
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
                                    <input type="text" class="form-control" id="descripcion_almacen_edit"
                                           name="descripcion_almacen_edit"
                                           placeholder="Ingrese la descripcion del nuevo almacen"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="direccion_almacen_edit"
                                           name="direccion_almacen_edit"
                                           placeholder="Ingrese la direccion del nuevo almacen"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_warehouse" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de nuevo tipo almacen-->
<div class="modal fade" id="modal_new_type_warehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Tipo de Almacen</h4>
            </div>
            <form id="frm_new_type_warehouse"
                  action="<?= site_url('warehouse/register_type_warehouse') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_tipo_almacen"
                                           name="nombre_tipo_almacen"
                                           placeholder="Ingrese el nombre del nuevo tipo almacen "
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
                                    <input type="text" class="form-control" id="descripcion_tipo_almacen"
                                           name="descripcion_tipo_almacen"
                                           placeholder="Ingrese la descripcion del nuevo tipo de almacen "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_type_warehouse" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de modicacion de tipo  de almacen-->
<div class="modal fade" id="modal_edit_type_warehouse" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Tipo de Almacen</h4>
            </div>
            <form id="frm_edit_type_warehouse" action="<?= site_url('warehouse/modify_type_warehouse') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <input type="text" id="id_tipo_almacen_edit" name="id_tipo_almacen_edit" hidden>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_tipo_almacen_edit"
                                           name="nombre_tipo_almacen_edit"
                                           placeholder="Ingrese el nombre del nuevo tipo almacen "
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
                                    <input type="text" class="form-control" id="descripcion_tipo_almacen_edit"
                                           name="descripcion_tipo_almacen_edit"
                                           placeholder="Ingrese la descripcion del nuevo tipo de almacen "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_type_warehouse" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/type_warehouse.js'); ?>"></script>
<script src="<?= base_url('jsystem/warehouse.js'); ?>"></script>
