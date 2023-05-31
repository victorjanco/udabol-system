<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */
$url_action = 'order_work/register';
$titulo = "REPARACION : " . $reception->codigo_trabajo;
$hidden = '';

?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm"><?= $titulo ?></h4>
            </div>
            <form id="frm_register" name="frm_register" action="<?= site_url($url_action) ?>"
                  method="post">
                <input type="text" name="id_reception" id="id_reception"
                       value="<?= isset($reception) ? $reception->id : '' ?>" hidden>
                <input type="text" name="id_order_work" id="id_order_work"
                       value="<?= isset($reception) ? $reception->orden_trabajo_id : '' ?>" hidden>
                <input type="text" id="diagnosed_state"
                       value="<?= isset($order_work) ? $order_work->estado_diagnostico : '' ?>" hidden>
                <?php $this->view('order/form_content') ?>
            </form>
        </div>
    </div>
</div>

<!-- //<editor-fold desc=" MODALS: Buscar Cliente, agregar mano de obra"> -->
<div class="modal fade" id="add_type_service" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_service">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Datos del Servicio de Mano de Obra 
                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" id="btn_new_service" >
                        <i class="fa fa-plus"></i>
                        </button>
                        <a style="height: 100%" class="btn btn-success btn-xs" id="btn_refresh_select_service"
						            title="Actualizar"><i class="material-icons">cached</i></a>
                    </h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="service_work">Servicio </label>
                                        <select style="width: 100%" class="form-control select2" id="service_work" name="service_work">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($services)) {
                                                foreach ($services as $row_service):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_service->id . '">' . $row_service->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="gama_work">Gama*: </label>
                                        <select style="width: 100%" class="form-control select2" id="gama_work" name="gama_work">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_work">Precio*:</label>
                                        <input type="text" class="form-control" name="price_work" id="price_work"
                                               placeholder="precio del servicio"
                                               onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="order_work_observation">Observacion *</label>
                                        <input type="text" class="form-control" name="order_work_observation"
                                               id="order_work_observation" placeholder="ingrese una descripcion"
                                               onkeypress="return alphabets_numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_add_table"
                            name="btn_add_table"><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="add_new_item" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_product">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Agregar repuesto </h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
							<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<div class="form-line">
										<label class="control-label" for="brand_product">Almacen*: </label>
										<div class="form-line">
											<select style="width: 100%" class="form-control select2" id="warehouse_id" name="warehouse_id">
												<!-- <option value="">Seleccione una opcion</option> -->
												<?php if (isset($list_warehouse)) {
													foreach ($list_warehouse as $row_warehouse):
														$html_option = '';
														$html_option .= '<option value="' . $row_warehouse->id . '">' . $row_warehouse->nombre . '</option>';
														echo $html_option;
													endforeach;
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="brand_product">Marca*: </label>
                                        <select style="width: 100%" class="form-control select2" id="brand_product" name="brand_product">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($list_brand)) {
                                                foreach ($list_brand as $row_brand):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_brand->id . '">' . $row_brand->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="model_product">Modelo*: </label>
                                        <select style="width: 100%" class="form-control select2" id="model_product" name="model_product">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="producto_order_work">Repuesto*:</label>
                                        <input type="text" name="product_selected" id="product_selected" hidden>
                                        <input type="text" class="form-control" name="producto_order_work"
                                               id="producto_order_work" placeholder="Nombre del producto"
                                        <!--onkeypress="return alphabets_numbers_point(event)-->">
                                        <!--    <input id="prueba">-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio costo*:</label>
                                        <input type="text" class="form-control" name="price_product" id="price_product"
                                               placeholder="precio unitario" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product">Precio venta*:</label>
                                        <input type="text" class="form-control" name="price_sale" id="price_sale"
                                               placeholder="precio venta" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <b>Stock Disponible</b>
                                        <input type="number" id="product_stock" class="form-control" align="center"
                                            readonly>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="quantity_product">Cantidad*:</label>
                                        <input type="number" class="form-control" name="quantity_product" min="1"
                                               id="quantity_product" placeholder="ingrese cantidad">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="add_new_item_recondition" style="overflow:hidden;" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="frm_add_row_product_recondition">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <h4 class="modal-title titulo_modal" id="largeModalLabel">Agregar repuesto </h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <div class="row clearfix">
							<div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<div class="form-line">
										<label class="control-label" for="brand_product">Almacen*: </label>
										<div class="form-line">
											<select style="width: 100%" class="form-control select2" id="warehouse_recondition_id" name="warehouse_recondition_id">
												<!-- <option value="">Seleccione una opcion</option> -->
												<?php if (isset($list_warehouse)) {
													foreach ($list_warehouse as $row_warehouse):
														$html_option = '';
														$html_option .= '<option value="' . $row_warehouse->id . '">' . $row_warehouse->nombre . '</option>';
														echo $html_option;
													endforeach;
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="brand_product_recondition">Marca*: </label>
                                        <select style="width: 100%" class="form-control select2" id="brand_product_recondition" name="brand_product_recondition">
                                            <option value="">Seleccione una opcion</option>
                                            <?php if (isset($list_brand)) {
                                                foreach ($list_brand as $row_brand):
                                                    $html_option = '';
                                                    $html_option .= '<option value="' . $row_brand->id . '">' . $row_brand->nombre . '</option>';
                                                    echo $html_option;
                                                endforeach;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" hidden>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="model_product_recondition">Modelo*: </label>
                                        <select style="width: 100%" class="form-control select2" id="model_product_recondition" name="model_product_recondition">
                                            <option value="">Seleccione una opcion</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="producto_order_work_recondition">Repuesto*:</label>
                                        <input type="text" name="product_selected_recondition" id="product_selected_recondition" hidden>
                                        <input type="text" class="form-control" name="producto_order_work_recondition"
                                               id="producto_order_work_recondition" placeholder="Nombre del producto"
                                         onkeypress="return alphabets_numbers_point(event)"> 
                                        <!--    <input id="prueba">-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" >
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="price_product_recondition">Precio unitario*:</label>
                                        <input type="text" class="form-control" name="price_product_recondition" id="price_product_recondition"
                                               placeholder="precio unitario" onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12" >
                                <div class="form-group">
                                    <div class="form-line">
                                        <label class="control-label" for="quantity_product_recondition">Cantidad*:</label>
                                        <input type="text" class="form-control" name="quantity_product_recondition"
                                               id="quantity_product_recondition" placeholder="ingrese cantidad"
                                               onkeypress="return numbers_point(event)">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Agregar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect close-modal" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!--Formulario Modal para registro de nuevo servicio-->
<div class="modal fade" id="modal_new_service" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo Servicio</h4>
            </div>
            <form id="frm_new_service" action="<?= site_url('service/register_service') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo Servicio</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="tipo_servicio_servicio" name="tipo_servicio_servicio" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nombre_servicio" name="nombre_servicio"
                                           placeholder="Ingrese el nombre del nuevo servicio"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Descripcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="descripcion_servicio"
                                           name="descripcion_servicio"
                                           placeholder="Ingrese la descripcion del nuevo servicio"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Servicio Estandar</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="precio_servicio"
                                           name="precio_servicio"
                                           placeholder="Ingrese el precio del servicio"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Servicio Gama Alta</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="precio_servicio_alta"
                                           name="precio_servicio_alta"
                                           placeholder="Ingrese el precio del servicio de gama alta"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Servicio Gama Media</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="precio_servicio_media"
                                           name="precio_servicio_media"
                                           placeholder="Ingrese el precio del servicio de gama media"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Precio Servicio Baja</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="precio_servicio_baja"
                                           name="precio_servicio_baja"
                                           placeholder="Ingrese el precio del servicio de gama baja"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_service" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>
<script src="<?= base_url('jsystem/order.js') ?>"></script>
<script src="<?= base_url('jsystem/service.js') ?>"></script>
<script src="<?= base_url('jsystem/type_service.js') ?>"></script>

