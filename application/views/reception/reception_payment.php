<?php
/**
 * User: Ing. Ariel Alejandro Gomez Chavez
 * Github: https://github.com/ariel-ssj
 * Date: 1/10/2019 10:41
 */?>
<div class="block-header">
    <button type="button" id="btn_new_reception_payment" class="btn btn-success waves-effect"><i
                class="fa fa-plus"></i> Nuevo Pago
</button>
    <a type="button" href="<?= site_url('reception') ?>" class="btn btn-danger waves-effect"><i
                class="fa fa-arrow-left"></i> Volver
    </a>
    <a type="button" onclick="print_payments(this)" class="btn btn-warning waves-effect"><i
                class="fa fa-print"></i> Imprimir Historial Pagos
    </a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Pagos de la Recepcion</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="list_reception_payment" class="table table-striped table-bordered ">
                        <thead>
                        <th style="width: 15%"><b>Codigo Recepcion</b></th>
                        <th style="width: 15%"><b>Usuario</b></th>
                        <th style="width: 10%"><b>Fecha</b></th>
                        <th style="width: 30%"><b>Observacion</b></th>
                        <th style="width: 10%"><b>Pago</b></th>
                        <th style="width: 10%"><b>Estado</b></th>
                        <th style="width: 10%"><b> Opciones</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Formulario Modal para registro de nueva pago para recepcion-->
<div class="modal fade" id="modal_new_reception_payment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Nuevo pago de Recepcion</h4>
            </div>
            <form id="frm_new_reception_payment"
                  action="<?= site_url('reception_payment/register_payment') ?> "
                  method="post">
                <input id="id_reception" name="id_reception" value="<?= isset($reception_id) ? $reception_id : "" ?>" hidden>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Codigo de Recepcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <input type="text" class="form-control" id="code_reception" name="code_reception"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           value="<?= isset($reception_for_new) ? 'O.T. '.$reception_for_new->codigo_recepcion : "No existe la recepcion"; ?>"
                                           title="Informacion de Codigo de Recepcion campo solo de lectura" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <input type="text" class="form-control" id="cliente_notificacion"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           value="<?= isset($reception_for_new) ? $reception_for_new->nombre : "No existe el cliente de esta recepcion"; ?>"
                                           title="Nombre del Cliente de la Recepcion campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Equipo del Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div>
                                    <input type="text" class="form-control" id="equipo"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           value="<?= isset($reception_for_new) ? 'Marca: ' . $reception_for_new->nombre_marca . ' / ' . 'Modelo: ' . $reception_for_new->nombre_comercial . ' / ' . 'Imei: ' . $reception_for_new->imei : "No existe el equipo del Cliente"; ?>"
                                           title="Nombre del Dispositivo Recepcionado campo solo de lectura" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Fecha de Pago</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div>
									<input type="date" class="form-control" id="date_payment"
										   name="date_payment"
										   value="<?= date("Y-m-d"); ?>"
										   style="background-color: rgb(235, 235, 228);text-align: left;padding-left: 2%"
										   title="Fecha que se Realizo el pago">
								</div>
							</div>
						</div>
					</div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Total Recepcion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input id="reception_total" name="reception_total"
                                           type="number" step="any"
                                           class="form-control"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           title="Total Recepcion"
										   value="<?= isset($reception_for_new) ? $reception_for_new->monto_total : "0.00"; ?>"
										   readonly
									/>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Descuento Anteriores</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div >
									<input
										   id="reception_discount_old" name="reception_discount_old"
										   type="number" step="any"
										   class="form-control"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
										   title="Descuentos Anteriores"
										   readonly
									/>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Descuento</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div >
									<input
										   id="reception_discount" name="reception_discount"
										   type="number" step="any"
										   class="form-control"
										   style="background-color: rgb(235, 235, 228);text-align: left;padding-left: 2%"
										   title="Descuento" onkeyup="calculate_total_reception()"
										   placeholder="0.00"
									/>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Total a Pagar</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div >
									<input id="reception_total_payment" name="reception_total_payment"
										   type="number" step="any"
										   class="form-control"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
										   title="Total a pagar"
										   placeholder="0.00"
										   readonly
									/>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Pagos Anteriores</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div >
                                    <input id="reception_payment_old" name="reception_payment_old"
                                           type="number" step="any"
                                           class="form-control"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           title="Monto que pago hasta la fecha"
											placeholder="0.00"
									/>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Pago</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div >
									<input id="reception_payment" name="reception_payment"
										type="number" step="any"
										min="1"
										class="form-control"
										style="background-color: rgb(235, 235, 228);text-align: left;padding-left: 2%"
										title="Monto que pagara" onkeyup="calculate_total_reception()"
										placeholder="0.00"
									/>
								</div>
							</div>
						</div>
					</div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Total Saldo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input id="reception_balance" name="reception_balance"
                                           type="number" step="any"
                                           class="form-control"
										   style="background-color: rgb(235,191,180);text-align: left;padding-left: 2%"
                                           title="Saldo"
											readonly
									/>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="row clearfix">
						<div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
							<label class="form-control-label">Observacion Pago</label>
						</div>
						<div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
							<div class="form-group">
								<div >
									<input
										id="reception_payment_observation" name="reception_payment_observation"
										type="text" step="any"
										class="form-control"
										style="background-color: rgb(235, 235, 228);text-align: left;padding-left: 2%"
										title="Glosa del pago que esta realizando"
										placeholder="Ingrese una glosa del pago que esta realizando"
									/>

								</div>
							</div>
						</div>
					</div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_new_reception_payment" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= base_url('jsystem/reception_payment.js'); ?>"></script>
<script>
$(document).ready(function () {
	get_reception_payment_list();
})
</script>
