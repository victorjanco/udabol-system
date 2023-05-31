<?php
/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/05/2019
 * Time: 11:31 AM
 */
?>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>REPORTE DE FALLAS EN ORDEN DE TRABAJO</h2>
            </div>
            <div class="body">
                <div class="card-content">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Marca:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_brand" name="report_brand"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_brand as $brand_list) : ?>
                                            <option value="<?= $brand_list->id; ?>"><?= $brand_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Modelo:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_model" name="report_model"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_gustomer" name="report_gustomer"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_customer as $customer_list) : ?>
                                            <option value="<?= $customer_list->id; ?>"><?= $customer_list->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Estado:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_state" name="report_state"
                                            class="form-control select2">
                                        <option value="">Todos</option>
                                        <option value="<?= RECEPCIONADO ?>"><?= get_work_order_states(RECEPCIONADO) ?></option>
                                        <option value="<?= REPARADO ?>"><?= get_work_order_states(REPARADO) ?></option>
                                        <option value="<?= APROBADO ?>"><?= get_work_order_states(APROBADO) ?></option>
                                        <option value="<?= EN_PROCESO ?>"><?= get_work_order_states(EN_PROCESO) ?></option>
                                        <option value="<?= CONCLUIDO ?>"><?= get_work_order_states(CONCLUIDO) ?></option>
                                        <option value="<?= ENTREGADO ?>"><?= get_work_order_states(ENTREGADO) ?></option>
                                        <option value="<?= EN_MORA ?>"><?= get_work_order_states(EN_MORA) ?></option>
                                        <option value="<?= ESPERA_STOCK ?>"><?= get_work_order_states(ESPERA_STOCK) ?></option>
                                        <option value="<?= ENTREGADO_ESPERA_STOCK ?>"><?= get_work_order_states(ENTREGADO_ESPERA_STOCK) ?></option>
                                        <option value="<?= NO_APROBADO ?>"><?= get_work_order_states(NO_APROBADO) ?></option>
                                        <option value="<?= SIN_SOLUCION ?>"><?= get_work_order_states(SIN_SOLUCION) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Nro. Recepcion:</label>
                                <div class="form-line">
                                    <input type="text" id="report_order_work_code" name="report_order_work_code"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Garantia:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_warranty" name="report_warranty"
                                            class="form-control select2">
                                        <option value="">Todos</option>
                                        <option value="0">SIN GARANTIA</option>
                                        <option value="1">CON GARANTIA</option>
                                        <option value="2">POR VERIFICAR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Referencias:</label>
                                <div class="form-line">
                                    <select style="width: 100%" id="report_reference" name="report_reference"
                                            class="form-control select2">
                                        <option value="0">Todos</option>
                                        <?php foreach ($list_reference as $row) : ?>
                                            <option value="<?= $row->id; ?>"><?= $row->nombre; ?></option>
                                        <?php endforeach; ?>
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
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a type="button" id="btn_new_order_work_fault"
                                   class="btn btn-primary btn-block"><i
                                        class="material-icons">search</i><span>Buscar</span></a>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <a class="btn btn-success btn-block" id="btn_excel_export_order_work_fault"><i
                                        class="material-icons">format_indent_increase</i><span>Exportar excel</span></a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table id="list_order_work_fault_report" class="table table-striped table-bordered">
                                    <thead>
                                    <th class="text-center"><b>FECHA RECEPCION</b></th>
                                    <th class="text-center"><b>FECHA CONCLUSION</b></th>
                                    <th class="text-center"><b>HORAS</b></th>
                                    <th class="text-center"><b>FECHA ENTREGA</b></th>
                                    <th class="text-center"><b>USUARIO RECEPCION</b></th>
                                    <th class="text-center"><b>USUARIO PERITO</b></th>
                                    <th class="text-center"><b>USUARIO CONCLUSION</b></th>
                                    <th class="text-center"><b>USUARIO ENTREGADO</b></th>
                                    <th class="text-center"><b>O.T.</b></th>
                                    <th class="text-center"><b>REFERENCIA</b></th>
                                    <th class="text-center"><b>CLIENTE</b></th>
                                    <th class="text-center"><b>MARCA</b></th>
                                    <th class="text-center"><b>CODIGO MODELO</b></th>
                                    <th class="text-center"><b>MODELO</b></th>
                                    <th class="text-center"><b>IMEI</b></th>
                                    <th class="text-center"><b>ESTADO</b></th>
                                    <th class="text-center"><b>GARANTIA</b></th>
                                    <th class="text-center"><b>OBSERVACION</b></th>
                                    <th class="text-center"><b>FALLAS RECEPCION</b></th>
                                    <th class="text-center"><b>FALLAS TECNICO</b></th>
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
</div>
<script src="<?= base_url('jsystem/report_order_work.js'); ?>"></script>