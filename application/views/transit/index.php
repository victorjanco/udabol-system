<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 3/6/2019
 * Time: 10:03
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Transito de Productos Prestados</h2>
            </div>
            <br>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Prestamo Inicio:</label>
                                    <input type="date" class="form-control" name="transit_date_start_loan"
                                           id="transit_date_start_loan">
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Prestamo Fin:</label>
                                    <input type="date" class="form-control" name="transit_date_end_loan"
                                           id="transit_date_end_loan">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Devolucion Inicio:</label>
                                    <input type="date" class="form-control" name="transit_date_start_return"
                                           id="transit_date_start_return">
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Fecha Devolucion Fin:</label>
                                    <input type="date" class="form-control" name="transit_date_end_return"
                                           id="transit_date_end_return">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Codigo O.T.:</label>
                                    <input type="text" class="form-control" name="transit_reception_code"
                                           id="transit_reception_code">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Estado Transito:</label>
                                    <select class="form-control" id="transit_state"
                                            name="transit_state">
                                            <option value="0">Todos</option>
                                            <option value="1">PRESTADO</option>
                                            <option value="2">DEVUELTO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Tipo Motivo:</label>
                                    <select class="form-control" id="transit_type_reason"
                                            name="transit_type_reason">
                                        <option value="">Todos</option>
                                        <?php foreach ($list_type_reason as $type_reason_list) : ?>
                                            <option value="<?= $type_reason_list->id; ?>"><?= $type_reason_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Marca:</label>
                                    <select class="form-control select2" id="transit_brand"
                                            name="transit_brand">
                                        <option value="">Todos</option>
                                        <?php foreach ($list_brand as $brand_list) : ?>
                                            <option value="<?= $brand_list->id; ?>"><?= $brand_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Modelo:</label>
                                    <select class="form-control select2" id="transit_model"
                                            name="transit_model">
                                        <option value="">Todos</option>
                                        <?php foreach ($list_model as $model_list) : ?>
                                            <option value="<?= $model_list->id; ?>"><?= $model_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line" style="width: 93%; float: left">
                                    <label class="control-label">Producto:</label>
                                    <select class="form-control select2" id="transit_product"
                                            name="transit_product">
                                        <option value="">Todos</option>
                                        <?php foreach ($list_product as $product_list) : ?>
                                            <option value="<?= $product_list->id; ?>"><?= $product_list->nombre_comercial; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_search_transit"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_transit"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <div class="table-responsive">
                    <table id="transit_list" class="table table-striped table-bordered table-responsive">
                        <thead>
                        <th hidden>ID</th>
                        <th class="text-center" width="5%"><b>Nro. Prestamo</b></th>
                        <th class="text-center" width="5%"><b>Tipo</b></th>
                        <th class="text-center" width="5%"><b>Estado</b></th>
                        <th class="text-center" width="5%"><b>O.T.</b></th>
                        <th class="text-center" width="5%"><b>Fecha Prestamo</b></th>
                        <th class="text-center" width="5%"><b>Fecha Devolucion</b></th>
                        <th class="text-center" width="10%"><b>Usuario Prestador</b></th>
                        <th class="text-center" width="10%"><b>Usuario Solicitador</b></th>
                        <th class="text-center" width="20%"><b>Producto</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= base_url('jsystem/report_transit.js'); ?>"></script>