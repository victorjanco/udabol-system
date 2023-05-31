<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 02:58 PM
 */
?>
<div class="block-header">
    <button class="btn btn-success" type="button" id="btn_new_brand_reception"><i class="fa fa-plus"></i> Nueva
        Marca
    </button>
    <button class="btn bg-warning" data-toggle="modal" id="btn_new_model_reception" ><i
                class="fa fa-plus"></i> Nuevo Modelo
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Marcas y Modelos</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#home" data-toggle="tab">Lista de Marcas</a>
                    </li>
                    <li role="presentation"><a href="#profile" data-toggle="tab">Lista de Modelos</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table id="list_brand_reception" class="table table-striped table-bordered">
                                <thead>
                                <th>ID</th>
                                <th><b>Nombre</b></th>
                                <th><b>Descripcion</b></th>
                                <th><b>Opciones</b></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table style="width: 100%" id="list_model_reception" class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th><b>Nombre</b></th>
                                <th><b>Descripcion</b></th>
                                <th><b>Marca</b></th>
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

<!-- MODAL PARA REGISTRO Y EDICION DE MARCA -->
<div class="modal fade" id="modal_new_brand_reception" tabindex="-1" role="dialog">
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
<!-- Modal para editar sucursales -->
<div class="modal fade" id="modal_edit_brand_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Marca Seleccionada</h4>
            </div>
            <form id="frm_edit_brand_reception" class="frm-modal" action="<?= site_url('brand_reception/modify_brand_reception') ?>" method="post">
                <div class="modal-body">
                    <input type="text" id="id_brand_reception" name="id_brand_reception" hidden>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="edit_name_brand" name="edit_name_brand" placeholder="Ingrese el nombre"
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
                                    <input type="text" class="form-control" id="edit_description_brand" name="edit_description_brand" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_edit_brand_reception" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_brand_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Ver Marca</h4>
            </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="view_name_brand" name="view_name_brand" placeholder="Ingrese el nombre"
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
                                    <input type="text" class="form-control" id="view_description_brand" name="view_description_brand" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="close_modal_brand_reception" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
        </div>
    </div>
</div>
<!--------------------------- MODAL PARA EL REGISTRO Y EDICION DE MODELO ---------------------->
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
<!-- EDICION DE LA ACTIVIDAD -->
<div class="modal fade" id="modal_edit_model_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel"> Editar Modelo</h4>
            </div>
            <form id="frm_edit_model_reception" class="frm-modal" action="<?= site_url('model_reception/modify_model_reception') ?>"
                  method="post">
                <div class="modal-body">
                    <input type="text" id="id_model_reception" name="id_model_reception" hidden>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="brand_reception_model_edit" name="brand_reception_model_edit" class="form-control">
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
                                    <input type="text" class="form-control" id="edit_name_model" name="edit_name_model" placeholder="Ingrese el nombre"
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
                                    <input type="text" class="form-control" id="edit_description_model" name="edit_description_model" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_model_reception" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_model_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel"> Ver Modelo</h4>
            </div>
            <form id="frm_view_model_reception" class="frm-modal"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="brand_reception_model_view" name="brand_reception_model_view" class="form-control">
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
                                    <input type="text" class="form-control" id="view_name_model" name="view_name_model" placeholder="Ingrese el nombre"
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
                                    <input type="text" class="form-control" id="view_description_model" name="view_description_model" placeholder="Ingrese una descripcion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="close_modal_view_model_reception" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/brand_reception.js') ?>"></script>
<script src="<?= base_url('jsystem/model_reception.js') ?>"></script>
