<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/02/2020
 * Time: 09:02 AM
 */ ?>

<div class="block-header" id="container_buttons">
    <a type="button" id="btn_new_cash" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> Nueva Caja
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Caja</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_cash" class="table table-striped table-bordered ">
                    <thead>
                    <th>ID</th>
                    <th><b>CODIGO</b></th>
                    <th><b>NOMBRE</b></th>
                    <th><b>DESCRIPCIÓN</b></th>
                    <th><b>FECHA MOD.</b></th>
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

<!--Formulario Modal para registro de nuevo caja-->
<div class="modal fade" id="modal_new_cash" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva Caja</h4>
            </div>
            <form id="frm_new_cash"
                  action="<?= site_url('cash/register_cash') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="add_code"
                                           name="add_code"
                                           placeholder="Ingrese el codigo"
                                           value="">
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
                                    <input type="text" class="form-control" id="add_name"
                                           name="add_name"
                                           placeholder="Ingrese el nombre de la Caja "
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
                                    <input type="text" class="form-control" id="add_description"
                                           name="add_description"
                                           placeholder="Ingrese la descripcion de la Caja "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_cash" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de modicacion de caja-->
<div class="modal fade" id="modal_edit_cash" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Caja</h4>
            </div>
            <form id="frm_edit_cash" action="<?= site_url('cash/modify_cash') ?> "
                  method="post" novalidate="novalidate">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo</label>
                        </div>
                        <input type="text" id="edit_id_cash" name="edit_id_cash" hidden>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="edit_code"
                                           name="edit_code"
                                           placeholder="Ingrese el Codigo"
                                           value="">
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
                                    <input type="text" class="form-control" id="edit_name"
                                           name="edit_name"
                                           placeholder="Ingrese el nombre de la Caja "
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
                                    <input type="text" class="form-control" id="edit_description"
                                           name="edit_description"
                                           placeholder="Ingrese la descripcion de la Caja "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_cash" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Formulario Modal para visualizar la informacion del caja-->
<div class="modal fade" id="modal_view_cash" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Ver Caja</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-label">Codigo:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" id="view_code" name="view_code" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-label">Nombre:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" id="view_name" name="view_name" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="form-control-label">Descripción:</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" id="view_description"
                                       name="view_description" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="close_modal_view_cash" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/cash.js'); ?>"></script>
    