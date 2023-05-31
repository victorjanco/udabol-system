
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
                                    <label class="control-label">Marca:</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <button type="button" id="btn_new_brand" class="btn btn-success waves-block"><i class="fa fa-plus"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <a class="btn bg-green" onclick="exportar_excel_lcv()"><i class="fa fa-file-excel-o fa-3x"></i><br>EXPORTAR EXCEL</a class="btn">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="table-responsive">
                    <table id="list_for_company" class="table table-striped table-bordered dataTable ">
                        <thead>
                        <th class="text-center">NRO DE ORDEN</th>
                        <th class="text-center"><b>NOMBRE</b></th>
                        <th class="text-center"><b>TELEFONO</b></th>
                        <th class="text-center"><b>ESTADO</b></th>
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