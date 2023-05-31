<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 04:54 PM
 */
?>
<div class="block-header">
    <!-- <a class="btn btn-success" href="<?= site_url('reception/new_reception') ?>"><i class="fa fa-plus"></i> Nueva
        Recepcion</a> -->
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Recepciones Entregadas</h2>
            </div>
            <br>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="filter_date_start_delivered"
                                           id="filter_date_start_delivered">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Fin:</label>
                                    <input type="date" class="form-control" name="filter_date_end_delivered"
                                           id="filter_date_end_delivered">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="table-responsive">
                    <table id="list_delivered" class="table table-striped table-bordered table-responsive">
                        <thead>
                        <th>ID</th>
                        <th class="text-center" width="10%"><b>Codigo</b></th>
                        <th class="text-center" width="10%"><b>Cliente</b></th>
                        <th class="text-center" width="10%"><b>Marca</b></th>
                        <th class="text-center" width="10%"><b>Modelo</b></th>
                        <th class="text-center" width="10%"><b>Imei</b></th>
                        <th class="text-center" width="10%"><b>F. recepcion</b></th>
                        <th class="text-center" width="10%"><b>F. entrega</b></th>
                        <th class="text-center" width="10%"><b>Monto Total</b></th>
                        <th class="text-center" width="10%"><b>Estado</b></th>
                        <th class="text-center" width="10%"><b>Usuario Perito</b></th>
                        <th class="text-center" width="25%"><b>Opciones</b></th>
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
        <div class="modal-content ">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel" align="center">INFORMACION DE LA
                    RECEPCION</h4>
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
<?php $this->view('reception/modal_states') ?>


<script src="<?= base_url('jsystem/reception.js') ?>"></script>