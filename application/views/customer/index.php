<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 11/7/2017
 * Time: 1:51 PM
 */
?>
<div class="block-header">
    <a type="button" href="<?= site_url('customer/new_customer') ?>" class="btn btn-success waves-effect"><i class="fa fa-plus"></i> Nuevo Cliente
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Clientes</h2>
            </div>
            <div class="body table-responsive">
                <table id="list_customer" class="table table-striped table-bordered ">
                    <thead>
                    <th class="text-center">ID</th>
                    <th class="text-center"><b>C.I.</b></th>
<!--                    <th class="text-center"><b>NIT.</b></th>-->
                    <th class="text-center"><b>Nombre del cliente</b></th>
                    <!-- <th class="text-center"><b>Tipo cliente</b></th> -->
                    <th class="text-center"><b>Telefono</b></th>
                    <th class="text-center"><b>Estado</b></th>
                    <th class="text-center">Opciones</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--Vista del Cliente en modal-->
<div class="modal fade" id="modal_view_customer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Informacion del Cliente</h4>
            </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">NIT</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit_cliente_view" name="nit_cliente_view"
                                           value="" readonly>
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
                                    <input type="text" class="form-control" id="nombre_factura_cliente_view" name="nombre_factura_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Carnet Identidad</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="ci_cliente_view" name="ci_cliente_view"
                                           value="" readonly>
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
                                    <input type="text" class="form-control" id="nombre_cliente_view" name="nombre_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono 1</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="telefono1_cliente_view" name="telefono1_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono 2</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="telefono2_cliente_view" name="telefono2_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="direccion_cliente_view" name="direccion_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Email</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="email_cliente_view" name="email_cliente_view"
                                           value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="close_modal_view_customer" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"> Cerrar
                    </button>
                </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/customer.js'); ?>"></script>
