<?php
/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 07/08/2017
 * Time: 04:54 PM
 */
?>
<div class="block-header">
    <a class="btn btn-success" id="btn_new_provider"><i class="fa fa-plus"></i>Nuevo Proveedor</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Proveedores</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="provider_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th class="text-center"><b>Nombre</b></th>
                        <th class="text-center"><b>Nit</b></th>
                        <th class="text-center"><b>Direcci&oacute;n</b></th>
                        <th class="text-center"><b>Telefono</b></th>
                        <th class="text-center"><b>Contacto</b></th>
                        <th class="text-center"><b>Estado</b></th>
                        <th class="text-center"><b>Opciones</b></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de nuevo de proveedor-->
<div class="modal fade" id="modal_new_provider" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Proveedor</h4>
            </div>
            <form id="frm_new_provider" class="frm_provider" action="<?= site_url('provider/register_provider') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                           placeholder="Ingrese el nombre del nuevo preveedor"
                                           value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">NIT</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit" name="nit"
                                           placeholder="Ingrese el Nit del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direcci&oacute;n</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                           placeholder="Ingrese la direcci&oacute;n del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tel&eacute;fono</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                           placeholder="Ingrese el tel&eacute;fono del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Contacto</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="contacto" name="contacto"
                                           placeholder="Ingrese el contacto"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_provider" type="button" class="btn btn-danger waves-effect close_modal_provider"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para modificacion de proveedor-->
<div class="modal fade" id="modal_edit_provider" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Proveedor</h4>
            </div>
            <form id="frm_edit_provider" class="frm_provider" action="<?= site_url('provider/modify_provider') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <input type="text" id="id_edit" name="id" hidden>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_edit" name="nombre_edit"
                                           placeholder="Ingrese el nombre del nuevo preveedor"
                                           value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">NIT</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit_edit" name="nit_edit"
                                           placeholder="Ingrese el Nit del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direcci&oacute;n</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="direccion_edit" name="direccion_edit"
                                           placeholder="Ingrese la direcci&oacute;n del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tel&eacute;fono</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="telefono_edit" name="telefono_edit"
                                           placeholder="Ingrese el tel&eacute;fono del nuevo proveedor"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Contacto</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="contacto_edit" name="contacto_edit"
                                           placeholder="Ingrese el contacto"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_provider" type="button" class="btn btn-danger waves-effect close_modal_provider"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= base_url('jsystem/provider.js'); ?>"></script>
