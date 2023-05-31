<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 04:50 PM
 */
?>
<div class="block-header">
    <a id="btn_create_product" class="btn btn-success"><i class="fa fa-plus"></i> Crear Producto</a>
    <!--<a id="btn_excel_export_product" class="btn btn-warning"><i class="fa fa-file-excel-o"></i> Exportar todos los productos a Excel</a>-->
    <!-- <a id="btn_excel_import_price_product" class="btn btn-info"><i class="fa fa-file-excel-o"></i> Importar precios productos Excel</a> -->
	<!-- <a id="btn_excel_import_product" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Importar nuevos productos Excel</a>-->
	<!--  <a id="btn_excel_import_stock_product" class="btn btn-info"><i class="fa fa-file-excel-o"></i> Importar Cantidad(Stock) productos Excel</a>-->
    <div hidden>
        <a id="btn_excel_delete_product" class="btn btn-danger"><i class="fa fa-file-excel-o"></i> Eliminar productos del almacen</a>
    </div>

	<!-- <a href="<?= site_url('product/imprimir_codigos') ?>" target="_blank" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir todos los códigos </a>-->
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Productos</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="product_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th class="text-center"><b>CÓDIGO</b></th>
                        <th class="text-center"><b>NOMBRE COMERCIAL</b></th>
                        <th class="text-center"><b>NOMBRE GENERICO</b></th>
                        <th class="text-center"><b>COLOR</b></th>
                        <th class="text-center"><b>PRECIO VENTA</b></th>
                        <th class="text-center"><b>GRUPO</b></th>
                        <th class="text-center"><b>SUB GRUPO</b></th>
                        <th class="text-center"><b>MODELO</b></th>
                        <th class="text-center"><b>MARCA</b></th>
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

<div class="modal fade" id="modal_view_product" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <h4 class="modal-title titulo_modal" id="largeModalLabel">Datos del Producto</h4>
            </div>
            <div class="modal-body">
                <div class="row clearfix">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Codigo *</label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="codigo" name="codigo" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Nombre Comercial *</label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="comercial" name="comercial" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Nombre Genérico </label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="generico" name="generico" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Dimension *</label>
                            <div class="form-line">
                                <input type="text" class="form-control" id="dimension" name="dimension" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Medida *</label>
                            <div class="form-line">
                                <input class="form-control" id="medida" name="medida" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Precio Venta *</label>
                            <div class="form-line">
                                <input type="number" step="any" class="form-control" id="precio_venta" name="precio_venta" value="" readonly >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Precio Costo *</label>
                            <div class="form-line">
                                <input type="number" step="any" class="form-control" id="precio_costo" name="precio_costo" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Grupo *</label>
                            <div class="form-line">
                                <input class="form-control" id="grupo" name="grupo" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Modelo *</label>
                            <div class="form-line">
                                <input class="form-control" id="modelo" name="modelo" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Provedores *</label>
                            <div class="form-line">
                                <input class="form-control" id="proveedor" name="proveedor" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group form-float">
                            <label class="form-label">Categoria *</label>
                            <div class="form-line">
                                <input class="form-control" id="categoria" name="categoria" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/product.js') ?>"></script>
