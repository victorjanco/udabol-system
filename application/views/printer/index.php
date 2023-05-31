<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 11/7/2017
 * Time: 1:51 PM
 */
?>
<div class="block-header">
    <a type="button" id="btn_new_printer" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> Nueva Impresora
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Impresoras</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_printer" class="table table-striped table-bordered ">
                    <thead>
                    <th class="text-center">ID</th>
                    <th class="text-center"><b>Sucursal Impuestos</b></th>
                    <th class="text-center"><b>Sucursal</b></th>
                    <th class="text-center"><b>Marca</b></th>
                    <th class="text-center"><b>Serial</b></th>
                    <th class="text-center"><b>Sucursal_id</b></th>
                    <th class="text-center">Opciones</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_new_printer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nueva Impresora</h4>
            </div>
            <form id="frm_new_printer" action="<?= site_url('printer/register_printer') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="sucursal" name="sucursal" class="form-control">
                                        <option value="">--Seleccione Sucursal--</option>
                                        <?php foreach ($branch_office_for_new as $branch_office_list) : ?>
                                            <option value="<?= $branch_office_list->id; ?>"><?= $branch_office_list->nombre_comercial.' - '.$branch_office_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="marca" name="marca"
                                           placeholder="Ingrese el nombre de la marca de la impresora"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Modelo y Serie</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="serie" name="serie"
                                           placeholder="Ingrese en modelo y nro de serie de la impresora ejemplo: MZ-123"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_printer" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_printer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Impresora</h4>
            </div>
            <form id="frm_edit_printer" action="<?= site_url('printer/modify_printer') ?>" method="post">
                <div class="modal-body">
                    <input type="text" id="id_printer" name="id_printer" value="" hidden>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="sucursal_edit" name="sucursal_edit" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Marca</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="marca_edit" name="marca_edit"
                                           placeholder="Ingrese el nombre de la marca de la impresora"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Modelo y Serie</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="serie_edit" name="serie_edit"
                                           placeholder="Ingrese en modelo y nro de serie de la impresora ejemplo: MZ-123"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_edit_printer" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/printer.js'); ?>"></script>