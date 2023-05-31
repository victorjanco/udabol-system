<?php

/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 19/04/2018
 * Time: 12:39 PM
 */

?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE POR PRODUCTOS</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Almacen:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_warehouse" name="report_warehouse"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_warehouse as $warehouse) : ?>
                                            <option value="<?= $warehouse->id; ?>"><?= $warehouse->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
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
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Producto:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_product" name="report_product"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_product as $product_list) : ?>
                                            <option value="<?= $product_list->id; ?>"><?= $product_list->nombre_comercial; ?></option>
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
                                <a type="button" id="btn_new_product"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_product"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_product_report" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center"><b>FECHA</b></th>
                                    <th class="text-center"><b>ALMACEN</b></th>
                                    <th class="text-center"><b>MARCA</b></th>
                                    <th class="text-center"><b>CODIGO PRODUCTO</b></th>
                                    <th class="text-center"><b>PRODUCTO</b></th>
                                    <th class="text-center"><b>CODIGO MODELO</b></th>
                                    <th class="text-center"><b>MODELO</b></th>
                                    <th class="text-center"><b>CANTIDAD ENTRANTE</b></th>
                                    <th class="text-center"><b>CANTIDAD SALIENTE</b></th>
                                    <th class="text-center"><b>SALDO DISPONIBLE</b></th>
                                    <th class="text-center"><b>PRECIO COSTO</b></th>
                                    <th class="text-center"><b>PRECIO VENTA</b></th>
                                    </thead>
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
<script src="<?= base_url('jsystem/report_product.js'); ?>"></script>
