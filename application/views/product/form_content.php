<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 05:04 PM
 */
$title = "Nuevo de Producto";
$url_action = site_url('purchase/r');
?>
<div class="panel-heading cabecera_frm ">
    <div style="">
        <h2 class="panel-title titulo_frm"><?= $title ?></h2>
    </div>

</div>
<div class="body">
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="comercial">Nombre Comercial:</label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" id="comercial" name="comercial"
                               value="<?= isset($product) ? $product->nombre_comercial : '' ?>"
                               placeholder="Ingrese nombre comercial del producto" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="generico">Nombre Genérico:</label>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" class="form-control" id="generico" name="generico"
                               value="<?= isset($product) ? $product->nombre_generico : '' ?>"
                               placeholder="Ingrese nombre generico del producto" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="codigo">Codigo:</label>
                <?php if(isset($new)){?>
                 <button id="btn_generate_barcode" type="button" class="btn btn-info">GENERAR <i class="fa fa-refresh"></i></button> 
                <?php }?>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input type="text" id="id_product" name="id_product"
                               value="<?= isset($product) ? $product->id : '' ?>" hidden>
                        <input type="number" id="codigo"
                               name="codigo" value="<?= isset($product) ? $product->codigo : $codigo_barra ?>" hidden>
                        <input placeholder="Ingrese codigo del producto" type="number" class="form-control" id="codigo1"
                               name="codigo1" value="<?= isset($product) ? $product->codigo : $codigo_barra ?>" readonly>
                             
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="dimension">Color:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Ingrese color del producto" type="text" class="form-control" id="dimension"
                               name="dimension" value="<?= isset($product) ? $product->dimension : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="grupo">Marca:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control select2" id="brand" name="brand" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($brands as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->marca_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="modelo">Modelo:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control select2" id="modelo" name="modelo" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($models as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->modelo_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="row clearfix">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="grupo">Serie:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control" id="serie" name="serie" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($series as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->serie_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="grupo">Grupo:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control" id="grupo" name="grupo" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($groups as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->grupo_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="categoria">Subgrupo:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control" id="subgrupo" name="subgrupo" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($subgroups as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->subgrupo_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- MARCA  -->

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="medida">Medida:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <select class="form-control" id="medida" name="medida" <?= isset($view)? 'disabled': '' ?>>
                            <option value="">::Seleccione una opcion</option>
                            <?php foreach ($units as $row) : ?>
                                <option value="<?= $row->id; ?>" <?= isset($product) ? is_selected($product->unidad_id, $row->id) : ''; ?>><?= $row->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="row clearfix">
	<!-- -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="precio_costo">Precio costo:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Ingrese precio de compra del producto"
                               class="form-control" id="precio_costo" name="precio_costo"
                               value="<?= isset($product) ? number_format($product->precio_compra,CANTIDAD_MONTO_DECIMAL,'.','') : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="precio_venta">Precio venta:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Ingrese precio de venta del producto"
                               class="form-control" id="precio_venta" name="precio_venta"
                               value="<?= isset($product) ? number_format($product->precio_venta,CANTIDAD_MONTO_DECIMAL,'.','') : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="higher_price">P. venta mayorista:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Precio de venta al por mayor"
                               class="form-control" id="higher_price" name="higher_price"
                               value="<?= isset($product) ? number_format($product->precio_venta_mayor, CANTIDAD_MONTO_DECIMAL,'.','') : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
            <div class="col-lg-12">
                <label for="express_price">P. venta express:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Precio de venta express"
                               class="form-control" id="express_price" name="express_price"
                               value="<?= isset($product) ? number_format($product->precio_venta_express, CANTIDAD_MONTO_DECIMAL,'.','') : 0?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
            <div class="col-lg-12">
                <label for="lab_price">P. venta laboratorio:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Precio de venta laboratorios"
                               class="form-control" id="lab_price" name="lab_price"
                               value="<?= isset($product) ? number_format($product->precio_venta_laboratorio, CANTIDAD_MONTO_DECIMAL,'.',0) : 0 ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
            <div class="col-lg-12">
                <label for="percent_price">Porcentaje precio laboratorio(%):</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Ej. 15"
                               class="form-control" id="percent_price" name="percent_price"
                               value="<?= isset($product) ? $product->porcentaje_precioventa_laboratorio*100 : 15 ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
            <div class="col-lg-12">
                <label for="percent_commission">Porcentaje Comisión:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Ej. 20"
                               class="form-control" id="percent_commission" name="percent_commission"
                               value="<?= isset($product) ? $product->porcentaje_comision*100 : 20 ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="minimum_stock">Stock M&iacute;nimo:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Cantidad de stock minimo"
                               class="form-control" id="minimum_stock" name="minimum_stock"
                               value="<?= isset($product) ? $product->stock_minimo : '1' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="row clearfix">
	<!--<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="imei1">Imei 1:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Imei 1"
                               class="form-control" id="imei1" name="imei1"
                               value="<?= isset($product) ? $product->imei1 : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="col-lg-12">
                <label for="imei2">Imei 2:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="form-line">
                        <input placeholder="Imei 2"
                               class="form-control" id="imei2" name="imei2"
                               value="<?= isset($product) ? $product->imei2 : '' ?>" <?= isset($view)? 'readonly': '' ?>>
                    </div>
                </div>
            </div>
        </div>-->

        <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="col-lg-12">
                <label for="proveedores">Proveedores:</label>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <div class="">
                        <select class="form-control select2" style="width: 100%" multiple id="proveedores"
                                name="proveedores[]" <?= isset($view)? 'disabled': '' ?>>
                            <?php foreach ($proveiders as $row) : ?>
                                <option value="<?= $row->id; ?>" <?php if(isset($product)){ if(in_array($row->id, $product_providers)){ echo 'selected';}}?>><?= $row->nombre; ?>

                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
	</div>
    <?php if(isset($view)){?>
        <a href="<?= site_url('product/index') ?>" class="btn btn-default waves-effect" type="submit"><i
                    class="fa fa-times"></i> Salir</a>
    <?php }else{?>
        <button id="btn_accion_user" class="btn btn-primary waves-effect no-modal" type="submit"><i
                    class="fa fa-save"></i> Guardar
        </button>
        <a href="<?= site_url('product/index') ?>" class="btn btn-danger waves-effect" type="submit"><i
                    class="fa fa-times"></i> Cancelar y
            Salir</a>
    <?php }?>
</div>
