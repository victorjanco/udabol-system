<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header">
                <h2>REPORTE DE PRODUCTOS VENDIDOS</h2>
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
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Marca:</label>
                                <select style="width: 100%" id="report_brand" name="report_brand"
                                        class="form-control select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_brand as $brand_list) : ?>
                                        <option value="<?= $brand_list->id; ?>"><?= $brand_list->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Modelo:</label>
                                <select style="width: 100%" id="report_model" name="report_model"
                                        class="form-line select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_model as $model_list) : ?>
                                        <option value="<?= $model_list->id; ?>"><?= $model_list->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Producto:</label>
                                <select style="width: 100%" id="report_product" name="report_product"
                                        class="form-line select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_product as $product_list) : ?>
                                        <option value="<?= $product_list->id; ?>"><?= $product_list->nombre_comercial; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <select style="width: 100%" id="report_customer" name="report_customer"
                                        class="form-line select2">
                                    <option value="0">Todos</option>
                                    <?php foreach ($list_customer as $customer_list) : ?>
                                        <option value="<?= $customer_list->id; ?>"><?= $customer_list->nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Tipo Venta:</label>
                                <select id="report_type_sale" name="report_type_sale" class="form-control select2">
                                    <option value="0">Todos</option>
                                    <option value="NOTA VENTA">VENTA</option>
                                    <option value="VENTA CREDITO">VENTA CREDITO</option>
                                    <option value="SERVICIO TECNICO">SERVICIO TECNICO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Tipo Producto:</label>
                                <select id="report_type_product" name="report_type_product" class="form-control select2">
                                    <option value="">Todos</option>
                                    <option value="1">PRODUCTO</option>
                                    <option value="2">SERVICIO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Nro. Venta:</label>
                                <div class="form-line">
                                    <input type="number" id="reporte_number_sale" name="reporte_number_sale"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" hidden>
                            <div class="form-group">
                                <label>Nro. Recepcion:</label>
                                <div class="form-line">
                                    <input type="number" id="reporte_number_reception" name="reporte_number_reception"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Fecha Inicio:</label>
                                <div class="form-line">
                                    <input width="100%" type="date" class="form-control" name="report_start_date"
                                           id="report_start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
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
                                <a type="button" id="btn_search_report_sale_product"
                                   class="btn btn-primary btn-block"><i
                                            class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_export_report_sale_product"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="report_sale_product" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center">FECHA</th>
                                    <!-- <th class="text-center"><b>O.T.</b></th> -->
                                    <th class="text-center"><b>NRO. VENTA</b></th>
                                    <th class="text-center"><b>CLIENTE</b></th>
                                    <th class="text-center"><b>TIPO VENTA</b></th>
                                    <th class="text-center"><b>TIPO PRODUCTO</b></th>
                                    <th class="text-center"><b>MARCA</b></th>
                                    <th class="text-center"><b>CODIGO PIEZA</b></th>
                                    <th class="text-center"><b>PIEZA</b></th>
                                    <th class="text-center"><b>MODELO</b></th>
                                    <th class="text-center"><b>COD. MODELO</b></th>
                                    <th class="text-center"><b>CANTIDAD</b></th>
                                    <th class="text-center"><b>P.COSTO</b></th>
                                    <th class="text-center"><b>P.VENTA</b></th>
                                    <th class="text-center"><b>DESCUENTO</b></th>
                                    <th class="text-center"><b>P.VENTA DESCUENTO</b></th>
                                    <th class="text-center"><b>TOTAL</b></th>
                                    <th class="text-center"><b>UTILIDAD</b></th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="11" style="text-align:right">Total:</th>
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
<script src="<?= base_url('jsystem/report_sale.js'); ?>"></script>
