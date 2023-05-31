<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header">
                <h2>REPORTE DE VENTAS POR USUARIO</h2>
            </div>

            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
                            <div class="form-group">
                                <label>SUCURSAL:</label>
                                <select style="width: 100%" id="report_branch_office" name="report_branch_office"
                                        class="form-control select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_branch_office as $row) : ?>
                                        <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" >
                            <div class="form-group">
                                <label>USUARIOS:</label>
                                <select style="width: 100%" id="report_user" name="report_user"
                                        class="form-line select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_user as $row) : ?>
                                        <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" hidden>
                            <div class="form-group">
                                <label>Tipo Venta:</label>
                                <select id="report_type_sale" name="report_type_sale" class="form-control select2">
                                    <option value="0">Todos</option>
                                    <option value="NOTA VENTA">VENTA</option>
                                    <option value="VENTA CREDITO">VENTA CREDITO</option>
                                    <!--<option value="SERVICIO TECNICO">SERVICIO TECNICO</option>-->
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Nro. Venta:</label>
                                <div class="form-line">
                                    <input type="number" id="reporte_number_sale" name="reporte_number_sale"
                                           class="form-control">
                                </div>
                            </div>
                        </div> -->
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Inicio:</label>
                                <div class="form-line">
                                    <input width="100%" type="date" class="form-control" name="report_start_date"
                                           id="report_start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Fecha Fin:</label>
                                    <input width="100%" type="date" class="form-control" name="report_end_date"
                                           id="report_end_date">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_search_report_sale_user"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_export_report_sale_user"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="report_sale_user" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center"><b>NRO. VENTA</b></th>
                                    <th class="text-center">FECHA</th>
                                    <th class="text-center"><b>CLIENTE</b></th>
                                    <th class="text-center"><b>TIPO VENTA</b></th>
                                    <th class="text-center"><b>USUARIO</b></th>
                                    <th class="text-center"><b>SUCURSAL</b></th>
                                    <th class="text-center"><b>COSTO TOTAL</b></th>
                                    <th class="text-center"><b>SUB TOTAL</b></th>
                                    <th class="text-center"><b>DESCUENTO</b></th>
                                    <th class="text-center"><b>TOTAL</b></th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="8" style="text-align:right">Total:</th>
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
<script src="<?= base_url('jsystem/report_sale.js'); ?>"></script>
