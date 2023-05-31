<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 14:48 PM
 */

?>


<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Reporte Comisión</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Marca:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="brand" name="brand"
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
                                    <select style="width: 100%" id="model" name="model"
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
                                <label>Serie:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="serie" name="serie"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_serie as $serie_list) : ?>
                                            <option value="<?= $serie_list->id; ?>"><?= $serie_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sucursales Comision:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="commission_branch_office"
                                            name="commission_branch_office"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_commission_branch_office as $commission_branch_office_list) : ?>
                                            <option value="<?= $commission_branch_office_list->id; ?>"><?= $commission_branch_office_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Producto:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="product" name="product"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_product as $product_list) : ?>
                                            <option value="<?= $product_list->id; ?>"><?= $product_list->codigo; ?> | <?= $product_list->nombre_comercial; ?></option>
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
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-primary btn-block" id="btn_search"><i
                                            class="material-icons">search</i><span>Consultar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-warning btn-block" id="btn_import_excel"><i
                                            class="material-icons">format_indent_increase</i><span>Importar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_export_excel"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="list_commission" class="table table-striped table-bordered dataTable ">
                        <thead>
                        <th class="text-center"><b>FECHA TRANSACCION</b></th>
                        <th class="text-center"><b>CODIGO PRODUCTO</b></th>
                        <th class="text-center"><b>NOMBRE PRODUCTO</b></th>
                        <th class="text-center"><b>SUCURSALES DE COMISION</b></th>
                        <th class="text-center"><b>GLOSA DE PRODUCTOS</b></th>
                        <th class="text-center"><b>SERIE</b></th>
                        <th class="text-center"><b>CANTIDAD</b></th>
                        <th class="text-center"><b>TOTAL BS</b></th>
                        <th class="text-center"><b>TOTAL $us</b></th>
                        <th class="text-center"><b>CODIGO TRANSACCION</b></th>
                        <th class="text-center"><b>PORCENTAJE</b></th>
                        <th class="text-center"><b>COMISION BS</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/report_commission.js'); ?>"></script>
