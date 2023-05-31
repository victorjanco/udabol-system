<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 8/8/2017
 * Time: 10:37 AM
 */ ?>

<div class="block-header">
    <button class="btn btn-success" type="button" id="btn_new_group_failure_solution"><i class="fa fa-plus"></i> Nuevo Grupo
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Grupos para las Fallas y Soluciones Comunes</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_group_failure_solution" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center"><b>Nombre</b> </th>
                        <th class="text-center"><b>Descripcion</b></th>
                        <th class="text-center"><b>Estado</b></th>
                        <th class="text-center"><b>Opciones</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Formulario Modal para registro de nuevo grupo de falla o solucion-->
<div class="modal fade" id="modal_new_group_failure_solution" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Grupo de Falla y Solucion</h4>
            </div>
            <form id="frm_new_group_failure_solution" action="<?= site_url('group_failure_solution/register_group_failure_solution') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_group_failure_solution" name="nombre_group_failure_solution"
                                           placeholder="Ingrese el nombre del nuevo grupo de falla o solucion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion</label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion_group_failure_solution"
                                           name="descripcion_group_failure_solution"
                                           placeholder="Ingrese la descripcion del nuevo grupo de falla o solucion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_group_failure_solution" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Formulario Modal para modificacion de grupo de falla o solucion-->
<div class="modal fade" id="modal_edit_group_failure_solution" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Grupo de Falla y Solucion</h4>
            </div>
            <form id="frm_edit_group_failure_solution" action="<?= site_url('group_failure_solution/modify_group_failure_solution') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="id_group_failure_solution_edit" name="id_group_failure_solution_edit" hidden>
                                    <input type="text" class="form-control" id="nombre_group_failure_solution_edit"
                                           name="nombre_group_failure_solution_edit"
                                           placeholder="Ingrese el nombre del grupo de falla o solucion"
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
                                    <input type="text" class="form-control" id="descripcion_group_failure_solution_edit"
                                           name="descripcion_group_failure_solution_edit"
                                           placeholder="Ingrese la descripcion del grupo de falla o solucion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_group_failure_solution" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/group_failure_solution.js'); ?>"></script>

