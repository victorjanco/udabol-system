<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 23/08/2017
 * Time: 03:50 PM
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Ordenes de Trabajo</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Datos:</label>
                                    <select class="form-control" id="filter_date" name="filter_date" required>
                                        <option value="1">Todos</option>
                                        <option value="2">Con retraso(3+ dias)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Codigo de recepcion:</label>
                                    <input type="text" class="form-control" name="filter_reception_code" id="filter_reception_code">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="filter_date_start_reception" id="filter_date_start_reception" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Fin:</label>
                                    <input type="date" class="form-control" name="filter_date_end_reception" id="filter_date_end_reception">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered ">
                        <thead>
                            <th>ID</th>
                            <th class="text-center"><b>Codigo trabajo</b></th>
                            <th class="text-center"><b>F. ingreso</b></th>
                            <th class="text-center"><b>T. sistema</b></th>
                            <th class="text-center"><b>Sucursal</b></th>
                            <th class="text-center"><b>Cliente</b></th>
                            <th class="text-center"><b>Marca</b></th>
                            <th class="text-center"><b>Modelo</b></th>
                            <th class="text-center"><b>Imei</b></th>
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
<div class="modal fade" id="modal_view_reception" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel"align="center">INFORMACION DE LA RECEPCION</h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_sale" name="id_sale" hidden>
                <div class="row clearfix" id="reception_data">
                </div>
            </div>
            <div class="modal-footer">
                <button id="close_modal_edit_service" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/order.js') ?>"></script>
