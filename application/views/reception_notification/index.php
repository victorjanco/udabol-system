<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 27/7/2017
 * Time: 2:05 PM
 */ ?>
<div class="block-header">
    <button class="btn bg-warning" type="button" id="btn_new_type_notification"><i class="fa fa-plus"></i> Nuevo Tipo
        Notificacion
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Historial de Notificaciones</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_notification_reception" data-toggle="tab">Lista de
                            Notificaciones</a></li>
                    <li role="presentation"><a href="#tab_type_notification_reception" data-toggle="tab">Lista de Tipos de
                            Notifiacion</a></li>
                </ul>
                <!-- Tab panes -->
                <!--Lista de Notificacion de recepcion-->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="tab_notification_reception">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table id="list_notification_reception" class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th style="width: 25%"><b>Tipo notificacion</b></th>
                                <th style="width: 25%"><b>Codigo recep.</b></th>
                                <th style="width: 20%"><b>Cliente</b></th>
                                <th style="width: 20%"><b>Descripcion</b></th>
                                <th style="width: 15%"><b>Fecha</b></th>
                                <th style="width: 10%"><b>Estado</b></th>
                                <th style="width: 15%"><b> Opciones</b></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Lista de Tipo Notificaciones para recepcion-->
                    <div role="tabpanel" class="tab-pane fade" id="tab_type_notification_reception">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table style="width: 100%" id="list_type_notification_reception"
                                   class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th><b>Tipo</b></th>
                                <th><b>Nombre</b></th>
                                <th><b>Descripcion</b></th>
                                <th><b>Estado</b></th>
                                <th><b>Opciones</b></th>
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

<!--Formulario Modal para registro de nuevo tipo de notificacion para recepcion-->
<div class="modal fade" id="modal_new_type_notification" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Tipo de Notificacion para Recepcion</h4>
            </div>
            <form id="frm_new_type_notification"
                  action="<?= site_url('type_notification/register_type_notification') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo para</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" id="tipo_tipo_notificacion" name="tipo_tipo_notificacion">
                                        <option value="0">Recepcion</option>
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
                                    <input type="text" class="form-control" id="nombre_tipo_notificacion"
                                           name="nombre_tipo_notificacion"
                                           placeholder="Ingrese el nombre del nuevo tipo notificacion de recepcion "
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
                                    <input type="text" class="form-control" id="descripcion_tipo_notificacion"
                                           name="descripcion_tipo_notificacion"
                                           placeholder="Ingrese la descripcion del nuevo tipo de notificacion "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_type_notification" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de modicacion de tipo  de notificacion para recepcion-->
<div class="modal fade" id="modal_edit_type_notification" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Tipo de Notificacion para  Recepcion</h4>
            </div>
            <form id="frm_edit_type_notification" action="<?= site_url('type_notification/modify_type_notification') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo para</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" id="tipo_tipo_notificacion_edit" name="tipo_tipo_notificacion_edit">
                                        <option value="0">Recepcion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <input type="text" id="id_tipo_notificacion_edit" name="id_tipo_notificacion_edit" hidden>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_tipo_notificacion_edit"
                                           name="nombre_tipo_notificacion_edit"
                                           placeholder="Ingrese el nombre del nuevo tipo notificacion de recepcion "
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
                                    <input type="text" class="form-control" id="descripcion_tipo_notificacion_edit"
                                           name="descripcion_tipo_notificacion_edit"
                                           placeholder="Ingrese la descripcion del nuevo tipo de notificacion de recepcion "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_type_notification" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/type_notification.js'); ?>"></script>
<script src="<?= base_url('jsystem/reception_notification.js'); ?>"></script>
<script>
    $(document).ready(function () {
        get_reception_notification_list();
    })
</script>


