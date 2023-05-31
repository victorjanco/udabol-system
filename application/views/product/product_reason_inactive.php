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
                <h2>Consulta de Productos Inactivos en almacenes</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="inactive_product_reason_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th class="text-center"><b>SUCURSAL</b></th>
						<th class="text-center"><b>ALMACEN</b></th>
						<th class="text-center"><b>CÓDIGO</b></th>
                        <th class="text-center"><b>NOMBRE</b></th>
						<th class="text-center"><b>MODELO</b></th>
                        <th class="text-center"><b>COLOR</b></th>
                        <th class="text-center"><b>SUBGRUPO</b></th>
                        <th class="text-center"><b>MARCA</b></th>
                        <th class="text-center"><b>STOCK</b></th>
                        <th class="text-center"><b>PRECIO COSTO</b></th>
                        <th class="text-center"><b>PRECIO VENTA</b></th>
                        <th class="text-center"><b>ESTADO</b></th>
                        <th class="text-center"><b>OPCIONES</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
<script>
$(document).ready(function () {
    get_inactive_product_reason_list();
});
</script>
<script src="<?= base_url('jsystem/product_reason.js') ?>"></script>
