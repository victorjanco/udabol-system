<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:25 PM
 */
?>
<div class="block-header">
   <div class="row">
       <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
           <br>
           <a type="button" id="btn_new" class="btn btn-success waves-block "><i class="fa fa-plus"></i> Nuevo sub-grupo
           </a>
       </div>
       <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
           <br>
           <a href="<?= base_url('group') ?>" class="btn btn-primary waves-block"><i class="fa fa-arrow-left"></i>                                    Volver</a>
       </div>
   </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de subgrupos de: <?= $group->nombre?></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered ">
                        <thead>
                        <th class="text-center">id</th>
                        <th class="text-center"><b>Nombre del sub-grupo</b></th>
                        <th class="text-center"><b>Descripción</b> </th>
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

<div class="modal fade" id="modal_new" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo subgrupo</h4>
            </div>
            <form id="frm_new" action="<?= site_url('group/register_subgroup') ?>" method="post">
                <input type="text"  id="group_id" name="group_id" value="<?= $group->id?>" hidden>
                <div class="modal-body">

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nombre_subgrupo">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_subgrupo" name="nombre_subgrupo"
                                           placeholder="Ingrese el nombre"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="descripcion_subgrupo">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion_subgrupo" name="descripcion_subgrupo"
                                           placeholder="Ingrese una descripcion del grupo si es necesario">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar grupo</h4>
            </div>
            <form id="frm_edit" action="<?= site_url('group/modify') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <input type="text" id="id" name="id" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_grupo_edit" name="nombre_grupo_edit">
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
                                    <input type="text" class="form-control" id="descripcion_grupo_edit"
                                           name="descripcion_grupo_edit">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/subgroup.js'); ?>"></script>
