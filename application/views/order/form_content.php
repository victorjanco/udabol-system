<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 25/07/2017
 * Time: 05:38 PM
 */
$service_total_amount = 0;
$producto_total_amount = 0;
?>
    <div class="body">
        <fieldset>
            <legend align="left">Datos del cliente &nbsp;&nbsp;&nbsp;&nbsp;</legend>
            <div class="row clearfix">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="id_customer" name="id_customer"
                                   value="<?= isset($reception) ? $reception->cliente_id : '' ?>" hidden>

                            <input type="text" id="equipo_cliente_id" name="equipo_cliente_id"
                                   value="<?= isset($reception) ? $reception->equipo_cliente_id : '0' ?>" hidden>

                            <input type="text" id="monto_total_reception" name="monto_total_reception"
                                   value="<?= isset($reception) ? $reception->monto_total : 0 ?>" hidden>
                            <input type="text" class="form-control" id="ci_customer" name="ci_customer"
                                   value="<?= isset($reception) ? $reception->ci : '' ?>"
                                   placeholder="Busque por CI" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="name_customer" name="name_customer"
                                   value="<?= isset($reception) ? $reception->nombre : '' ?>" disabled
                                   placeholder="Busque por nombre de cliente">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="telefono1_customer" name="telefono1_customer"
                                   value="<?= isset($reception) ? $reception->telefono1 : '' ?>" disabled
                                   placeholder="Telefono principal">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="telefono2_customer" name="telefono2_customer"
                                   value="<?= isset($reception) ? $reception->contacto : '' ?>" disabled
                                   placeholder="Telefono secundario">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend align="left">Datos del peritaje</legend>

            <div class="row clearfix">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line" style="width: 93%; float: left">
                            <label class="control-label">Dispositivo *</label>
                            <select class="form-control" id="devices_select" name="devices_select" disabled>
                                <option value="0">Seleccione dispositivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12" hidden>
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label class="control-label">Codigo Seguridad *</label>
                            <input type="text" class="form-control" id="code_segurity" name="code_segurity"
                                   value="<?= isset($reception) ? $reception->codigo_seguridad : '' ?>"
                                   placeholder="Escriba el codigo o patron de seguridad" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12" hidden>
                    <div class="form-group">
                        <div class="form-line">
                            <label class="control-label">Garantia *</label>
                            <select class="form-control" id="warranty_select" name="warranty_select" required>
                                <!-- <option value="">Seleccione...</option> -->
                                <!-- <option value="1">Con garantia</option> -->
                                <option value="0">Sin garantia</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <label class="control-label">Accesorios </label>
                            <input type="text" class="form-control" id="accessories_select" name="accessories_select"
                                   placeholder="Detalle los accesorios dejados"
                                   value="<?= isset($reception) ? $reception->accesorio_dispositivo : '' ?>" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <p>
                            <b>Fallas Declaradas en Recepcion *</b>
                        </p>
                        <select style="width: 100%" class="form-control show-tick select2" id="failure_select_reception"
                                name="failure_select_reception[]" multiple="multiple"
                                data-placeholder="Seleccione una o varias fallas" disabled>
                        </select>
                    </div>
                </div>
                <div id="div_color" class="col-lg-2 col-md-12 col-sm-12 col-xs-12" hidden>
                    <div class="form-group">
                        <p>
                            <b>Color *</b>
                        </p>
                        <div class="form-line" style="width: 100%; float: left">
                            <select style="width: 100%" class="form-control select2" id="color_select"
                                    name="color_select" disabled>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <p>
                            <b>Posible Reparacion en Recepcion *</b>
                        </p>
                        <select style="width: 100%" class="form-control show-tick select2" id="solution_select_reception"
                                name="solution_select_reception[]" multiple="multiple"
                                data-placeholder="Seleccione una o varias soluciones" disabled>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <p>
                            <b>Fallas Declaradas Tecnico *</b>
                        </p>
                        <select style="width: 100%" class="form-control show-tick select2" id="failure_select"
                                name="failure_select[]" multiple="multiple"
                                data-placeholder="Seleccione una o varias fallas" required>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <p>
                            <b>Posible Reparacion Tecnico *</b>
                        </p>
                        <select style="width: 100%" class="form-control show-tick select2" id="solution_select"
                                name="solution_select[]" multiple="multiple"
                                data-placeholder="Seleccione una o varias soluciones" required>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <label class="control-label">Observacion </label>
                            <input type="text" class="form-control" id="observation_select" name="observation_select"
                                   placeholder="Escriba informacion adicional que concidere necesaria"
                                   value="<?= isset($order_work) ? $order_work->observacion : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <label class="control-label">Usuario*: </label>
                            <select name="user" id="user" class="form-control select2">
                                <option value="<?=get_user_id_in_session()?>"><?=get_user_name_in_session()?></option>
                                <?php /*if (isset($list_users)) {

                                    foreach ($list_users as $row_user):
                                        $select_user = "";
                                        if (isset($order_work)) {
                                            if ($order_work->asignado_usuario_id == $row_user->id) {
                                                $select_user = "selected";
                                            }

                                        }
                                        $html = '<option value="' . $row_user->id . '" '. $select_user .'>' . $row_user->usuario . ' - ' . $row_user->nombre . '</option>';
                                        echo $html;
                                    endforeach;
                                } */?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend align="left">Servicios de manos de obra <a data-toggle="modal" data-target="#add_type_service"
                                                               class="btn btn-info btn-xs"
                                                               title="Nuevo dispositivo"><i
                            class="fa fa-plus"></i></a></legend>

            <div class="row clearfix">
                <div class="col-lg-12 col-xs-12">
                    <table width="100%" id="table_services_work" class="table-bordered">
                        <thead>
                        <th style="width: 30%" class="text-center">Tipo de servicio</th>
                        <th style="width: 40%" class="text-center">Servicio</th>
                        <th style="width: 20%" class="text-center">Precio</th>
                        <th style="width: 10%" class="text-center">Opciones</th>
                        </thead>
                        <tbody>
                        <?php if (isset($reception)) {
                            $index_delete = 1;
                            foreach ($detail_service as $row_detail):
                                $html = '<tr id="' . $index_delete . '" data-id="' . $row_detail->servicio_id . '" data-cost="' . $row_detail->precio_servicio . '" data-price="' . $row_detail->precio_servicio . '" >';
                                $html .= '<td style="padding-left: 2%; text-align: left">' . $row_detail->nombre_tipo_servicio . '</td>';
                                $html .= '<td style="padding-left: 2%; text-align: left"><input  value="' . $row_detail->servicio_id . '" name="serviceid[]" hidden/>' . $row_detail->nombre_servicio . '</td>';
                                $html .= '<td class="text-right"><input  value="' . $row_detail->precio_servicio . '" name="serviceprice[]" hidden/>' . $row_detail->precio_servicio . '</td>';
                                $html .= '<td style="text-align: center"><input type="text" name="serviceobservation[]" value="" hidden><a type="button" class="btn btn-danger elimina_service"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td></tr>';
                                echo $html;
                                $index_delete++;
                                $service_total_amount = $service_total_amount + $row_detail->precio_servicio;
                            endforeach;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td style="text-align: right" colspan="2"><b>Total Bs.:</b></td>
                            <td style="padding: 10px; text-align: right"><b><?php if (isset($reception)) {
                                        echo number_format($service_total_amount, CANTIDAD_MONTO_DECIMAL, '.', '');
                                    } else {
                                        echo '0.00';
                                    } ?></b></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend align="left">Repuestos o accesorios necesarios <a data-toggle="modal" data-target="#add_new_item"
                                                                      class="btn btn-info btn-xs"
                                                                      title="Nuevo dispositivo"><i
                            class="fa fa-plus"></i></a></legend>

            <div class="row clearfix">
                <div class="col-lg-12 col-xs-12">
                    <table width="100%" id="table_services_product" class="table-bordered ">
                        <thead>
                        <th style="width: 20%" class="text-center">Modelo</th>
                        <th style="width: 20%" class="text-center">Repuesto</th>
                        <th style="width: 20%" class="text-center">Cantidad</th>
                        <th style="width: 15%" class="text-center">Precio</th>
                        <th style="width: 15%" class="text-center">Total</th>
                        <th style="width: 10%" class="text-center">Opciones</th>
                        </thead>
                        <tbody>
                        <?php if (isset($reception)) {
                            $index_delete = 1;
                            foreach ($detail_product as $row_detail):
                                $html = '<tr data-price="' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . ' " id="' . $index_delete . '" data-price_product="' . $row_detail->precio_venta . '" data-quantity="' . $row_detail->cantidad . '" data-product_id="' . $row_detail->producto_id . '" >';
                                $html .= '<td>' . $row_detail->nombre_modelo . '</td>';
                                $html .= '<td><input value="' . $row_detail->producto_id . '"  name="product_id[]" hidden/>' . $row_detail->nombre_producto . '</td>';
                                $html .= '<td align="right"><input value="' . $row_detail->cantidad . '" name="quantity_product[]" hidden/>' . number_format($row_detail->cantidad, '2', '.', '') . '</td>';
                                $html .= '<td align="right"><input value="' . $row_detail->precio_venta . '" name="price_sale[]" hidden/><input value="' . $row_detail->precio_costo . '" name="price_product[]" hidden/>' . number_format($row_detail->precio_venta, 2, '.', '') . '</td>';
                                $html .= '<td align="right">' . number_format($row_detail->precio_venta * $row_detail->cantidad, 2, '.', '') . '</td>';
                                $html .= '<td style="text-align: center"><a class="elimina btn-danger btn"><i class="material-icons">delete_forever</i><span class="icon-name">Eliminar</span></a></td>';
                                $html .= '</tr>';
                                echo $html;
                                $index_delete++;
                                $producto_total_amount = $producto_total_amount + ($row_detail->precio_venta * $row_detail->cantidad);
                            endforeach;
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td style="text-align: right" colspan="4"><b>Total Bs.:</b></td>
                            <td style="padding: 10px; text-align: right"><b><?php if (isset($reception)) {
                                        echo number_format($producto_total_amount, CANTIDAD_MONTO_DECIMAL, '.', '');
                                    } else {
                                        echo '0.00';
                                    } ?></b></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>

        <fieldset hidden>
            <legend align="left">Repuestos para reacondicionar
                <a data-toggle="modal" data-target="#add_new_item_recondition"
                   class="btn btn-info btn-xs" title="Nuevo dispositivo">
                    <i class="fa fa-plus"></i>
                </a>
            </legend>

            <div class="row clearfix">
                <div class="col-lg-12 col-xs-12">
                    <table width="100%" id="table_services_product_recondition" class="table-bordered ">
                        <thead>
                        <th style="width: 20%" class="text-center">Modelo</th>
                        <th style="width: 20%" class="text-center">Repuesto</th>
                        <th style="width: 20%" class="text-center">Cantidad</th>
                        <th style="width: 10%" class="text-center">Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>


        <div class="modal-footer">
            <input type="text" value="<?= SIN_SOLUCION ?>" id="not_solution" hidden>
            <a id="btn_not_solution" class="btn btn-primary waves-effect no-modal"><i
                        class="fa fa-check-square"></i> Sin soluci&oacuten
            </a>
            <button id="btn_accion_user" class="btn btn-success waves-effect no-modal" type="submit"><i
                        class="fa fa-save"></i> Guardar
            </button>
            <a href="<?= site_url('reception') ?>" class="btn btn-danger waves-effect" type="submit"><i
                        class="fa fa-times"></i> Cancelar y
                Salir</a>
        </div>
    </div>

<?php
if (!isset($reception)) {
    ?>
    <script>
        $(function () {
            $('#modal_search_customer').modal({
                show: true,
                backdrop: 'static'
            });
        })
    </script>
<?php } ?>
