<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 31/7/2017
 * Time: 5:46 PM
 */ ?>
<div class="block-header">
    <button type="button" id="btn_new_reception_notification" class="btn btn-success waves-effect"><i
                class="fa fa-plus"></i> Nueva Notificacion
    </button>
    <a type="button" href="<?= site_url('reception') ?>" class="btn btn-danger waves-effect"><i class="fa fa-arrow-left"></i> Volver
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Historial de Notificaciones de la Recepcion</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_specific_reception" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th>NOTIFICACION ID</th>
                        <th style="width: 10%"><b>TIPO NOTIFICACION</b></th>
                        <th style="width: 10%"><b>CODIGO RECEP.</b></th>
                        <th style="width: 10%"><b>CLIENTE</b></th>
                        <th style="width: 10%"><b>DESCRIPCION</b></th>
                        <th style="width: 10%"><b>FECHA</b></th>
                        <th style="width: 10%"><b>ESTADO</b></th>
                        <th style="width: 10%"><b> OPCIONES</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de nueva notificacion para recepcion-->
<div class="modal fade" id="modal_new_notification" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva Notificacion de Recepcion</h4>
            </div>
            <form id="frm_new_notification"
                  action="<?= site_url('reception_notification/register_reception_notification') ?> "
                  method="post">
                <input id="id_reception" name="id_reception" value="<?= isset($reception) ? $reception : "" ?>" hidden>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo de Recepcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="codigo_notificacion"
                                           value="<?= isset($reception_for_new)? $reception_for_new->codigo_recepcion: "No existe la recepcion";?>" title="Informacion de Codigo de Recepcion campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="cliente_notificacion"
                                           value="<?= isset($reception_for_new)? $reception_for_new->nombre: "No existe el cliente de esta recepcion";?>" title="Nombre del Cliente de la Recepcion campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Equipo del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="equipo_notificacion"
                                           value="<?= isset($reception_for_new)? 'Marca: '.$reception_for_new->nombre_marca.' / '.'Modelo: '.$reception_for_new->nombre_comercial.' / '.'Imei: '.$reception_for_new->imei: "No existe el equipo del Cliente";?>" title="Nombre del Dispositivo Recepcionado campo solo de lectura"disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" id="tipo_notificacion" name="tipo_notificacion" title="Seleccione el Tipo de Notificacion">
                                        <option value="0">Selecciones Tipo</option>
                                        <?php foreach ($type_notification_for_new as $type_notification_list) : ?>
                                            <option value="<?= $type_notification_list->id; ?>"><?= $type_notification_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Fecha de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <input type="date" class="form-control" id="fecha_notificacion"
                                           name="fecha_notificacion"
                                           value="<?= date("Y-m-d"); ?>" title="Seleccione la Fecha que se Realizo la  Notificacion">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="glosa_notificacion"
                                           name="glosa_notificacion"
                                           placeholder="Ingrese la descripcion de la notificacion al cliente "
                                           value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_notification" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de modicacion de tipo  de notificacion para recepcion-->
<div class="modal fade" id="modal_edit_notification" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Notificacion de Recepcion</h4>
            </div>
            <form id="frm_edit_notification"
                  action="<?= site_url('reception_notification/modify_reception_notification') ?> "
                  method="post">
                <input id="id_reception_edit" name="id_reception_edit" value="<?= isset($reception) ? $reception : "" ?>" hidden>
                <input id="id_notification_edit" name="id_notification_edit" value="" hidden>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo de Recepcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="codigo_notificacion"
                                           value="<?= isset($reception_for_new)? $reception_for_new->codigo_recepcion: "No existe la recepcion";?>" title="Informacion de Codigo de Recepcion campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="cliente_notificacion"
                                           value="<?= isset($reception_for_new)? $reception_for_new->nombre: "No existe el cliente de esta recepcion";?>" title="Nombre del Cliente de la Recepcion campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Equipo del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input type="text" class="form-control" id="cliente_notificacion"
                                           value="<?= isset($reception_for_new)? 'Marca: '.$reception_for_new->nombre_marca.' / '.'Modelo: '.$reception_for_new->nombre_comercial.' / '.'Imei: '.$reception_for_new->imei: "No existe el equipo del Cliente";?>" title="Nombre del Dispositivo Recepcionado campo solo de lectura"disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" id="tipo_notificacion_edit"
                                            name="tipo_notificacion_edit">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Fecha de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <input type="date" class="form-control" id="fecha_notificacion_edit"
                                           name="fecha_notificacion_edit"
                                           title="Seleccione la Fecha que se Realizo la  Notificacion" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion de Notificacion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="glosa_notificacion_edit"
                                           name="glosa_notificacion_edit"
                                           placeholder="Ingrese la descripcion de la notificacion al cliente"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_notification" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/reception_notification.js'); ?>"></script>
<script>
    $(document).ready(function () {
        get_specific_reception_list();
    })
</script>
