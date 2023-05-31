
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE POR MARCA</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="control-label">Fecha Inicio:</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label class="control-label">Fecha Fin:</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <b>Marca</b>
                                    <select id="reception_brand" class="form-control">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_brand as $brand_list) : ?>
                                            <option value="<?= $brand_list->id; ?>"><?= $brand_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <a type="button" id="btn_new_brand" class="btn btn-primary btn-block"><i class="material-icons">search</i><span>Buscar</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <a class="btn btn-success btn-block" id="excel_export"><i class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="table-responsive">
                    <table id="list_brand_report" class="table table-striped table-bordered">
                        <thead>
                        <th class="text-center">NRO DE ORDEN</th>
                        <th class="text-center"><b>NOMBRE</b></th>
                        <th class="text-center"><b>TELEFONO</b></th>
                        <th class="text-center"><b>ESTADO</b></th>
                        <th class="text-center"><b>COD MODELO</b></th>
                        <th class="text-center"><b>MODELO</b></th>
                        <th class="text-center"><b>IMEI</b></th>
                        <th class="text-center"><b>FECHA INGRESO</b></th>
                        <th class="text-center"><b>FECHA ENTREGA</b></th>
                        <th class="text-center"><b>REPARACION</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/report.js'); ?>"></script>