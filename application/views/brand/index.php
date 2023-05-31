<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:25 PM
 */
?>
<div class="block-header">
    <button type="button" id="btn_new_brand" class="btn btn-success waves-block"><i class="fa fa-plus"></i> Nueva Marca
    </button>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Marcas</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_brand" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center"><b>CÓDIGO DE LA MARCA</b></th>
                        <th class="text-center"><b>NOMBRE DE LA MARCA</b></th>
                        <th class="text-center"><b>ESTADO</b></th>
                        <th class="text-center"><b>OPCIONES</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_new_brand" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva Marca</h4>
            </div>
            <form id="frm_new_brand" action="<?= site_url('brand/register_brand') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="codigo" name="codigo"
                                           placeholder="Codigo de la marca"
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
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           placeholder="Ingrese el nombre de una nueva marca "
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_brand" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_brand" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Marca</h4>
            </div>
            <form id="frm_edit_brand" action="<?= site_url('brand/modify_brand') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="codigo_marca"
                                           name="codigo_marca">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <input type="text" id="id_marca" name="id_marca" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_marca" name="nombre_marca">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_brand" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/brand.js'); ?>"></script>
