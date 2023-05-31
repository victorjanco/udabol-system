<?php
/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 26/04/2018
 * Time: 08:28 AM
 */
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE DE EQUIPOS ENTREGADOS</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Marca:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_brand" name="report_brand"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_brand as $brand_list) : ?>
                                            <option value="<?= $brand_list->id; ?>"><?= $brand_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Modelo:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_model" name="report_model"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_gustomer" name="report_gustomer"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_customer as $customer_list) : ?>
                                            <option value="<?= $customer_list->id; ?>"><?= $customer_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Nro. Recepcion:</label>
                                <div class="form-line">
                                    <input type="text" id="report_order_work_code" name="report_order_work_code"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Garantia:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_warranty" name="report_warranty"
                                            class="form-control select2">
                                        <option value="">Todos</option>
                                        <option value="0">SIN GARANTIA</option>
                                        <option value="1">CON GARANTIA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Inicio:</label>
                                <div class="form-line">
                                    <input style="width: 100%" type="date" class="form-control" name="start_date"
                                           id="start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Fin:</label>
                                <div class="form-line">
                                    <input style="width: 100%" type="date" class="form-control" name="end_date"
                                           id="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_new_order_work_delivered"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_order_work_delivered"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_order_work_report_delivered" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center"><b>FECHA RECEPCION</b></th>
                                    <th class="text-center"><b>FECHA CONCLUSION</b></th>
                                    <th class="text-center"><b>HORAS</b></th>
                                    <th class="text-center"><b>FECHA ENTREGA</b></th>
                                    <th class="text-center"><b>USUARIO RECEPCION</b></th>
                                    <th class="text-center"><b>USUARIO PERITO</b></th>
                                    <th class="text-center"><b>USUARIO CONCLUSION</b></th>
                                    <th class="text-center"><b>USUARIO ENTREGADO</b></th>
                                    <th class="text-center"><b>O.T.</b></th>
                                    <th class="text-center"><b>CLIENTE</b></th>
                                    <th class="text-center"><b>MARCA</b></th>
                                    <th class="text-center"><b>CODIGO MODELO</b></th>
                                    <th class="text-center"><b>MODELO</b></th>
                                    <th class="text-center"><b>IMEI</b></th>
                                    <th class="text-center"><b>GARANTIA</b></th>
                                    <th class="text-center"><b>PROBLEMA</b></th>
                                    <th class="text-center"><b>SERVICIO</b></th>
                                    <th class="text-center"><b>MONTO SERVICIO</b></th>
                                    <th class="text-center"><b>REPUESTO</b></th>
                                    <th class="text-center"><b>MONTO REPUESTO</b></th>
                                    <th class="text-center"><b>TOTAL</b></th>
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
    </div>
</div>
</div>
<script src="<?= base_url('jsystem/report_order_work.js'); ?>"></script>
