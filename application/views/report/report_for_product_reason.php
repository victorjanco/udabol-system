<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 13/05/2019
 * Time: 04:59 PM
 */
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE POR PRODUCTOS MOTIVOS</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
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
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Modelo:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_model" name="report_model"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_model as $model_list) : ?>
                                            <option value="<?= $model_list->id; ?>"><?= $model_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Producto:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_product" name="report_product"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_product as $product_list) : ?>
                                            <option value="<?= $product_list->id; ?>"><?= $product_list->codigo; ?>
                                                / <?= $product_list->nombre_comercial; ?></option>
                                        <?php endforeach; ?>
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
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_new_product_reason"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_product_reason"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_product_reason_report" class="table table-striped table-bordered">
                                    <thead>

                                    <th class="text-center"><b>CODIGO PRODUCTO</b></th>
                                    <th class="text-center"><b>PRODUCTO</b></th>
                                    <th class="text-center"><b>CODIGO MODELO</b></th>
                                    <th class="text-center"><b>MODELO</b></th>
                                    <th class="text-center"><b>COLOR</b></th>
                                    <th class="text-center"><b>GRUPO</b></th>
                                    <th class="text-center"><b>MARCA</b></th>
                                    <th class="text-center"><b>MOTIVO</b></th>
                                    <th class="text-center"><b>OBSERVACION</b></th>
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
<script src="<?= base_url('jsystem/report_product_reason.js'); ?>"></script>


