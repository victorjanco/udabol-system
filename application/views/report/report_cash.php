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
                <h2>REPORTE DE CIERRES DE CAJAS</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Sucursal:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_office" name="report_office"
                                            class="form-control select2" onchange="onChangeBranch()">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_office as $office) : ?>
                                            <option value="<?= $office->id; ?>"><?= $office->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Caja:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_cash" name="report_cash"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Usuario:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_user" name="report_user"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_user as $user_list) : ?>
                                            <option value="<?= $user_list->id; ?>"><?= $user_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Cierre Inicio:</label>
                                <div class="form-line">
                                    <input style="width: 100%" type="date" class="form-control" name="start_date"
                                           id="start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Cierre Fin:</label>
                                <div class="form-line">
                                    <input style="width: 100%" type="date" class="form-control" name="end_date"
                                           id="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_search_cash"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_cash"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_cash_report" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center"><b>SUCURSAL</b></th>
                                    <th class="text-center"><b>CAJA</b></th>
                                    <th class="text-center"><b>USUARIO</b></th>
                                    <th class="text-center"><b>FECHA CIRRE</b></th>
                                    <th class="text-center"><b>MONTO APERTURA Bs</b></th>
                                    <th class="text-center"><b>MONTO APERTURA Sus</b></th>
                                    <th class="text-center"><b>MONTO TARJETA</b></th>
                                    <th class="text-center"><b>MONTO TRANSFERENCIA</b></th>
                                    <th class="text-center"><b>MONTO CIERRE Bs</b></th>
                                    <th class="text-center"><b>MONTO CIERRE Sus</b></th>
                                    <th class="text-center"><b>TOTAL EFECTIVO</b></th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" style="text-align:right">Total:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
<script src="<?= base_url('jsystem/report_cash.js'); ?>"></script>
