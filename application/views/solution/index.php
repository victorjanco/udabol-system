<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 14/7/2017
 * Time: 10:50 AM
 */
?>
<div class="block-header">
    <button type="button" id="btn_new_solution" class="btn btn-success waves-block"><i class="fa fa-plus"></i> Nueva
        Solucion
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Soluciones</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_solution" class="table table-striped table-bordered dataTable ">
                        <thead>
                        <th class="text-center">ID</th>
                        <th class="text-center"><b>NOMBRE DE LA SOLUCION</b></th>
                        <th class="text-center"><b>DESCRIPCION</b></th>
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

<!--Formulario Modal para registro de nueva solucion-->
<div class="modal fade" id="modal_new_solution" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva Solucion</h4>
            </div>
            <form id="frm_new_solution" action="<?= site_url('solution/register_solution') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Grupo de Solucion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="grupo_solucion" name="grupo_solucion" class="form-control">
                                        <!-- <option value="">--Seleccione grupo--</option> -->
                                        <?php foreach ($group_for_new as $group_list) : ?>
                                            <option value="<?= $group_list->id; ?>"><?= $group_list->nombre; ?></option>
                                        <?php endforeach; ?>
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

<!--Formulario Modal para registro de modicacion de solucion-->
<div class="modal fade" id="modal_edit_solution" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f44336">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Solucion</h4>
            </div>
            <form id="frm_edit_solution" action="<?= site_url('solution/modify_solution') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Grupo de Solucion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="grupo_solucion_edit" name="grupo_solucion_edit" class="form-control">
                                        <option value="">--Seleccione grupo--</option>
                                        <?php foreach ($group_for_new as $group_list) : ?>
                                            <option value="<?= $group_list->id; ?>"><?= $group_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <input type="text" id="id_solucion" name="id_solucion" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_solucion" name="nombre_solucion"
                                    >
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
                                    <input type="text" class="form-control" id="descripcion_solucion"
                                           name="descripcion_solucion"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_solution" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/solution.js'); ?>"></script>