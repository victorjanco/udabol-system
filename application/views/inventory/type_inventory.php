<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 18/7/2017
 * Time: 8:25 PM
 */
?>
<!--<div class="block-header">
    <button type="button" id="btn_new" class="btn btn-success waves-block"><i class="fa fa-plus"></i> Nueva categoria
    </button>
</div>-->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Tipo de ingreso de inventario:</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                <?php foreach ($type_inventory_entry as $row_type_inventory_entry):?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box hover-expand-effect inventory_link" id="<?= $row_type_inventory_entry->id?>" onclick="div_click(<?=$row_type_inventory_entry->id?>,'<?=$row_type_inventory_entry->url?>')">
                            <div class="icon bg-teal">
                                <i class="material-icons">equalizer</i>
                            </div>
                            <div class="content">
                                <div class="number"><?=$row_type_inventory_entry->nombre?></div>
                                <div class="text"><?=$row_type_inventory_entry->decripcion?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= site_url('inventory') ?>" class="btn btn-danger waves-effect" type="submit">
                    <i class="fa fa-backward"></i> Cancelar y volver </a>
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
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva categoria</h4>
            </div>
            <form id="frm_new" action="<?= site_url('category/register_category') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nombre_categoria">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria"
                                           placeholder="Nombre de la categoria">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="descripcion_categoria">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion_categoria" name="descripcion_categoria"
                                           placeholder="Ingrese descripcion  si es necesario.">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal" type="button" class="btn btn-danger waves-effect"
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
            <form id="frm_edit" action="<?= site_url('category/modify') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <input type="text" id="id" name="id" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nombre_categoria_edit">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_categoria_edit" name="nombre_categoria_edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="descripcion_categoria_edit">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion_categoria_edit"
                                           name="descripcion_categoria_edit">
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
<script src="<?= base_url('jsystem/inventory.js'); ?>"></script>
