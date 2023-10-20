<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 13/05/2019
 * Time: 10:30 AM
 */
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE PROVEEDORES</h2>
            </div>
                
            <div class="body">
            <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">  
                            <div class="form-group">
                                <label>Sucursal:</label>
                                <div class="form-line">
                                    <select name="branch_office_report" id="branch_office_report" class="form-control select2">
                                        <!-- <option value="0">Todos</option> -->
                                        <?php foreach ($list_branch_office as $row_branch_office): ?>
                                            <option value="<?= $row_branch_office->id ?>"><?= $row_branch_office->nombre_comercial ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Almacen:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="warehouse_report" name="warehouse_report"
                                            class="form-control select2">
                                            <option value="0">Todos</option>
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
                                <a class="btn btn-success btn-block" id="btn_excel_export_provider"><i
                                            class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="report_provider_list" class="table table-striped table-bordered ">
                                    <thead>
                                    <th>ID</th>
                                    <th class="text-center"><b>PROVEEDOR</b></th>
                                    <th class="text-center"><b>DIRECCION</b></th>
                                    <th class="text-center"><b>TELEFONO</b></th>
                                    <th class="text-center"><b>PRODUCTO</b></th>
                                    <th class="text-center"><b>STOCK</b></th>
                                    <th class="text-center"><b>ESTADO</b></th>
                                    <!-- <th class="text-center"><b>OPCIONES</b></th> -->
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

<div class="modal fade" id="modal_new_reason" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Motivo</h4>
            </div>
            <form id="frm_new_reason" action="<?= site_url('product/register_product_reason') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Motivo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="product_id" name="product_id" hidden>
                                    <select class="form-control" id="type_reason" name="type_reason">
                                        <option value="">::Seleccione una opcion</option>
                                        <?php foreach ($tipo_motivo as $row) : ?>
                                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Observaciones</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="observations" name="observations"
                                           placeholder="Ingrese una observacion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_reason" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_new_preci" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevos Precios</h4>
            </div>
            <form id="frm_new_preci" action="<?= site_url('inventory/register_product_preci') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Costo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="inventory_branch_id" name="inventory_branch_id"
                                           value="" hidden>
                                    <input type="text" class="form-control" id="cost_price" name="cost_price"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Venta</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sale_price" name="sale_price"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Venta Mayorista</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sale_price1" name="sale_price1"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_preci" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    
    get_report_provider_list();

    $('#branch_office_report').change(function (event) {
        get_warehouse_by_branch_office();
    });

    $('#start_date').change(function() {
        get_report_provider_list();
    });

    $('#end_date').change(function() {
        get_report_provider_list();
    });

    setTimeout(function() {
        get_warehouse_by_branch_office()
    }, 2000);

});

function get_warehouse_by_branch_office(){
    if ($('#branch_office_report').val() != 0) {
        var warehouse_list = get_warehouse_by_branch_office_id($('#branch_office_report').val());
        $('#warehouse_report').empty();
        // $('#warehouse_report').append('<option value="0"> Todos </option>')
        $.each(warehouse_list, function (i, item) {
            $('#warehouse_report').append('<option value="' + item.id + '">' + item.nombre + '</option>')
        });
    } else {
        $('#warehouse_report').empty();
        // $('#warehouse_report').append('<option value="0"> Todos </option>')
    }

    get_report_provider_list();
}
</script>
<script src="<?= base_url('jsystem/provider.js') ?>"></script>
