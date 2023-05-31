<?php
/**
 * Created by Visual Code.
 * User: victor janco
 * Date: 16/09/2019
 * Time: 11:08 AM
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE LCV</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Mes:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="month" name="month"
                                            class="form-control select2">
                                            <option value="01">ENERO</option>
                                            <option value="02">FEBRERO</option>
                                            <option value="03">MARZO</option>
                                            <option value="04">ABRIL</option>
                                            <option value="05">MAYO</option>
                                            <option value="06">JUNIO</option>
                                            <option value="07">JULIO</option>
                                            <option value="08">AGOSTO</option>
                                            <option value="09">SEPTIEMBRE</option>
                                            <option value="10">OCTUBRE</option>
                                            <option value="11">NOVIEMBRE</option>
                                            <option value="12">DICIEMBRE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Año:</label>
                                <div class="form-line">
                                <input type="text" class="form-control" id="year" name="year" value="<?= date('Y') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_search_lcv"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_txt_export_lcv"
                                   class="btn btn-warning btn-block"><i
                                            class="material-icons">assignment</i><span>Txt</span></a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_lcv"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_report_lcv" class="table table-striped table-bordered">
                                    <thead>
                                        <th class="text-center"><b>NRO FACTURA</b></th>
                                        <th class="text-center"><b>AUTORIZACION</b></th>
                                        <th class="text-center"><b>FECHA</b></th>
                                        <th class="text-center"><b>NIT</b></th>
                                        <th class="text-center"><b>CLIENTE</b></th>
                                        <th class="text-center"><b>SUBTOTAL</b></th>
                                        <th class="text-center"><b>DESCUENTO</b></th>
                                        <th class="text-center"><b>MONTO TOTAL</b></th>
                                        <th class="text-center"><b>IMPORTE BASE PARA DEBITO FISCAL</b></th>
                                        <th class="text-center"><b>ESTADO</b></th>
                                        <th class="text-center"><b>CODIGO DE CONTROL</b></th>
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
<script src="<?= base_url('jsystem/report.js'); ?>"></script>
