<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 12/07/2017
 * Time: 10:09 AM
 */
$user_profile = get_profile(get_user_id_in_session());
?>
</div>
</section>
<div class="modal fade" id="modal_login_session" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="frm_login_session" method="POST" action="<?= site_url('login/sign_in') ?>">
                    <div class="msg">Su session fue restablecida vuelva a iniciar session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>
                        <div class="form-line">
                            <select name="branch_office_session" id="branch_office_session" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="username_session" name="username_session"
                                   placeholder="Usuario" required
                                   autofocus value="<?= $this->session->flashdata("acount_login") ?>">
                            <span class="text-danger"><?php echo form_error('username') ?></span>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password_session" placeholder="Contraseña"
                                   required>
                            <span class="text-danger"><?php echo form_error('password') ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit">Volver a Iniciar Sesion
                            </button>
                        </div>
                        <button id="close_modal_login_session" type="button" data-dismiss="modal" hidden>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Formulario Modal para verificar acciones-->
<div class="modal fade" id="modal_verify" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Verificacion para descuento</h4>
            </div>
            <form id="frm_verify" action="<?= site_url('user/verify_key') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Usuario</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="user" name="user"
                                           placeholder="Ingrese usuario" title="Ingrese usuario"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Clave</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" class="form-control" id="key" name="key"
                                           placeholder="Ingrese la clave" title="Ingrese clave para habilitar descuento"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-check-circle"></i>
                        Verificar
                    </button>
                    <button id="close_modal_verify" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
                    </button>
                    <br>
                    <br>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_profile" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-small" role="document">
        <div class="modal-content modal-col-red">
            <div class="modal-header cabecera_modal">
                <button type="button" id="" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 20pt">&times;
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel" style="text-align: center;">Perfil de
                    Usuario</h4>
            </div>
            <form id="frm_profile" action="<?= site_url('user/change_pass_user') ?>" method="post">
                <div class="modal-body">
                    <div class="row clearfix name_profile">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Nombre</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label>: <?= $user_profile->nombre ?></label>
                        </div>
                    </div>
                    <div class="row clearfix name_profile">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>C.I.</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label>: <?= $user_profile->ci ?></label>
                        </div>
                    </div>
                    <div class="row clearfix name_profile">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Telefono</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label>: <?= $user_profile->telefono ?></label>
                        </div>
                    </div>
                    <div class="row clearfix name_profile">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label>Usuario</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <label>: <?= $user_profile->usuario ?></label>
                        </div>
                    </div>
                    <?php $charge_user = array("2", "4");
                    if (in_array($user_profile->cargo_id, $charge_user)) { ?>
                        <div class="row clearfix name_profile">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <label>Clave Descuento</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <label>: <?= $user_profile->generated_key ?></label>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="div_change_pass">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                                <label class="form-control-label">Password Actual: </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="pass_current" name="pass_current"
                                           value="">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                                <label class="form-control-label">Password Nuevo: </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="pass_new" name="pass_new"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="div_change_key">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="form-control-label">Nueva Clave Descuento: </label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="key_new" name="key_new"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <button type="button" id="change_password" class="btn btn-primary waves-effect"><i
                                class="fa fa-check-circle"></i>
                        Cambiar Password
                    </button>
                    <button type="button" id="change_key" class="btn btn-warning waves-effect"><i
                                class="fa fa-check-circle"></i>
                        Cambiar Clave Desc.
                    </button>


                    <button id="change_user" type="submit" class="btn btn-success waves-effect"><i
                                class="fa fa-check-circle"></i>
                        Guardar
                    </button>

                    <button id="close_modal_profile" type="button" class="btn btn-default waves-effect"
                            data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar
                    </button>
                    <br>
                    <br>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_close_session" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-small" role="document">
        <form id="frm_close_session" action="<?= site_url('/open_cash') ?>" method="post">
            <div class="modal-content">
                <!-- header modal -->
                <div class="modal-header cabecera_modal">
                    <button type="button" id="" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 20pt">&times;
                    </button>
                    <h4 class="modal-title titulo_modal" id="defaultModalLabel" style="text-align: center;">CERRAR SESSION</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="row">
                        <!-- <?php //if (get_user_box_enable_in_session() == 1) { ?> -->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <a id="select_cash_and_permission_close_cash">
                                    <div class="card">
                                        <div>
                                            <div>
                                                <i class="material-icons">attach_money</i>
                                            </div>
                                            <h3 align="center">Cerrar Caja</h3>
                                            <p><b>Esta opcion cerrará la caja activa en ventas de
                                                    esta sucursal.</b></p>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <a href="<?= site_url('login/sign_out') ?>">
                                    <div class="card">
                                        <div>
                                            <div>
                                                <i class="material-icons">backspace</i>
                                            </div>
                                            <h3 align="center">Cerrar Sesion</h3>
                                            <p><b>Cierra la sesion pero la caja de ventas sigue
                                                    permaneciendo activa.</b></p>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        <!-- <?php //} ?> -->
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_select_cash" role="dialog">
        <div class="modal-dialog modal-small" role="document">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">&times;
                    </button>
                    <h4 class="modal-title titulo_modal" id="defaultModalLabel">Apertura de Caja</h4>
                </div>
                <form id="frm_select_cash" action="<?= site_url('cash/check_cash') ?>" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="alert alert-success alert-dismissible" style="text-align: center">
                                    <h4><i class="icon fa fa-info"></i>Aviso!</h4>
                                    Seleccione la caja que desea utilizar para realizar ventas.
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-control-label">Seleccione Caja</label>
                                    <select id="cash_id" name="cash_id" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div align="center">
                        <button type="submit" id="btn_select_cash" class="btn btn-success waves-effect"><i
                                    class="fa fa-check-circle"></i>
                            Guardar
                        </button>
                        <button id="close_modal_select_cash" type="button" class="btn btn-danger waves-effect"
                                data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
                        </button>
                        <br>
                        <br>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_cash_aperture" role="dialog">
        <div class="modal-dialog modal-small" role="document">
            <div class="modal-content">
                <div class="modal-header cabecera_modal">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="color: white;font-size: 15pt">&times;
                    </button>
                    <h4 class="modal-title titulo_modal" id="defaultModalLabel">Apertura de Caja</h4>
                </div>
                <form id="frm_cash_aperture" action="<?= site_url('cash/cash_aperture') ?>" method="post">
                    <div class="modal-body">
                        <div style="overflow-y: scroll; height: 100%; width: 100%;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="alert alert-success alert-dismissible" style="text-align: center">
                                        <h4><i class="icon fa fa-info"></i>Aviso!</h4>
                                        Aperturar la caja con un monto.
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Caja: </label>
                                        <!-- Caja  -->
                                        <div class="form-line">
                                            <input type="text" id="aperture_cash_id" name="aperture_cash_id" hidden>
                                            <input type="text" class="form-control" id="aperture_name_cash"
                                                   name="aperture_name_cash" title="Caja de venta" disabled/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg6 col-md-6 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label>Monto Apertura Bs: </label>
                                        <div class="form-line">
                                            <!-- Monto  -->
                                            <input type="text" step="any" class="form-control" id="aperture_amount_bs"
                                                   name="aperture_amount_bs" value="0.00" title="Monto de apertura"
                                                   onkeypress="return numbers_point(event)" required/>
                                        </div>
                                    </div>
								</div>
								<div class="col-lg6 col-md-6 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label>Monto Apertura Sus: </label>
                                        <div class="form-line">
                                            <!-- Monto  -->
                                            <input type="text" step="any" class="form-control" id="aperture_amount_sus"
                                                   name="aperture_amount_sus" value="0.00" title="Monto de apertura"
                                                   onkeypress="return numbers_point(event)" required/>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" hidden>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success waves-effect" id="btn_add_row">
                                            <i class="fa fa-card"></i> Agregar
                                        </button>
                                    </div>
                                </div> -->
                            </div>
                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table_detail"
                                           class="table table-striped table-hover table-bordered table-responsive">
                                        <thead>
                                        <tr>
                                            <th width="0%" hidden>Tipo Pago</th>
                                            <th width="20%">Cuenta Bancaria / Moneda</th>
                                            <th width="20%">Monto</th>
                                            <th width="15%" hidden>Opcion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div align="center">
                        <button type="submit" id="btn_cash_aperture" class="btn btn-success waves-effect"><i
                                    class="fa fa-check-circle"></i>
                            Guardar
                        </button>
                        <button id="close_modal_cash_aperture" type="button" class="btn btn-danger waves-effect"
                                data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar
                        </button>
                        <br>
                        <br>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    base_url = '<?= base_url() ?>';
    site_url = '<?= site_url() ?>';
    b_o_n_s = '<?= get_branch_office_name_in_session() ?>';
    b_o_i_s = '<?= get_branch_id_in_session() ?>';
    u_s = '<?= get_user_name_in_session() ?>';
    number_printer = '<?= get_number_printer_by_branch_office_id(get_branch_id_in_session()) ?>';
    printer_invoice = '<?= get_printer_in_session() ?>';
    cash_id = '<?= get_session_cash_id() ?>';
    var user_profile =  '<?php echo json_encode($user_profile); ?>';
    user_profile = JSON.parse(user_profile);
    function login_session() {
        $('#frm_login_session')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_login_session').modal({
            show: true,
            backdrop: 'static'
        });
        $('#branch_office_session').append('<option value="' + b_o_i_s + '">' + b_o_n_s + '</option>');
        $('#username_session').val(u_s);
    }

    function open_verify() {
        $('#frm_verify')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_verify').modal({
            show: true,
            backdrop: 'static'
        });
    }

    $('#frm_verify').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $('.modal-error').remove();
                    $('#close_modal_verify').click();
                    $('#view_discount').show();
                    $('#open_discount').hide();
                    /*para formulario modal nueva venta*/
                    $('#sale_discount').val('');
                    $('#sale_discount').show();
                    $('#sale_discount').focus();
                    $('#sale_discount').removeAttr("readonly");
					/*para el formularion de nueva recepcion*/
					/*$('#reception_discount').val('');
					$('#reception_discount').show();*/
                    $('#reception_discount').focus();
                    $('#reception_discount').removeAttr("readonly");
                } else {
                    swal('INCORRECTO', response.messages, 'error');
                }
            }
        });
    });

     /* Evento que verifica si la caja esta aperturada o esta cerrada */
		 $('#frm_select_cash').submit(function (event) {
            event.preventDefault();
            var form = $(this);
            ajaxStart('Verificando...');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function (response) {
                    ajaxStop();

                    if (response.login == true) {
                        login_session();
                    } else if (response.check == true) {
                        swal('Caja Aperturada', 'La caja seleccionada se encuentra aperturada.', 'success');
                        $('#close_modal_select_cash').click();

                        /*setTimeout(function () {
                            location.href = site_url + "home/";
                        }, 1000);*/
                    } else {

                        swal('Caja No Aperturada', 'La caja seleccionada se encuentra disponible para aperturar.', 'success');
                        $('#aperture_cash_id').val(response.cash_id);
                        $('#aperture_name_cash').val(response.name_cash);
                        $('#aperture_amount_bs').val(response.total_efective_bs);
                        $('#aperture_amount_sus').val(response.total_efective_sus);

                        $('#close_modal_select_cash').click();
                        $('#modal_cash_aperture').modal({
                            show: true,
                            backdrop: 'static'
                        });
                        // cargar_tabla_tipos_monedas($('#aperture_cash_id').val());
                    }
                }
            });
        });

        $('#frm_cash_aperture').submit(function (event) {
            event.preventDefault();

            var frm = $(this);
            var data = $(frm).serialize();
            ajaxStart('Aperturando Caja, por favor espere');
            $.ajax({
                url: $(frm).attr('action'),
                type: $(frm).attr('method'),
                data: data,
                dataType: 'json',
                success: function (response) {
                    ajaxStop();
                    if (response.login) {
                        login_session();
                    } else if (response.success == true) {
                        swal('Exito', 'La caja de venta ha sido aperturada.', 'success');
                        $('#close_modal_cash_aperture').click();
                    } else {
                        swal('Error', 'Error al aperturar la caja.', 'error');
                        $('#close_modal_cash_aperture').click();
                    }

                }
            });
        });

    $('#frm_profile').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $('.modal-error').remove();
                    swal('CORRECTO', response.messages, 'success');

                } else {
                    swal('INCORRECTO', response.messages, 'error');
                }
            }
        });
    });

    $('#user_profile').click(function (event) {
        $('#frm_profile')[0].reset(); //para borrar todos los datos que tenga los input, textareas, select.
        $(".modal-error").empty();
        $('#modal_profile').modal({
            show: true,
            backdrop: 'static'
        });
        $('#div_change_pass').hide();
        $('#div_change_key').hide();
        $('#change_user').hide();
        $('#close_modal_profile').hide();
        $('#change_password').show();
        var array_charge=["2","4"];

        if(array_charge.includes(user_profile.cargo_id)){
            $('#change_key').show();
        }else{
            $('#change_key').hide();
        }
    });

    $('#change_password').click(function (event) {
        $('#div_change_pass').show();
        $('#change_user').show();
        $('#close_modal_profile').show();
        $('#change_password').hide();
        $('#change_key').hide();
    });

    $('#change_key').click(function (event) {
        $('#div_change_key').show();
        $('#change_user').show();
        $('#close_modal_profile').show();
        $('#change_key').hide();
        $('#change_password').hide();

    });


    $('#frm_login_session').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    $('.modal-error').remove();
                    swal('Volvio a Iniciar Sesion', 'Puede seguir ocupando el sistema', 'success');
                    $('#close_modal_login_session').click();
                   
                    swal({
                        title: "Volvio a Iniciar Sesion",
                        text: "Puede seguir ocupando el sistema",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "ok",
                        cancelButtonText: "No, cancelar",
                        closeOnConfirm: true,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            // get_cash_enable();
                        }
                    });
                } else {
                    swal('Error', 'Error de usuario o clave vuelva a intentar', 'error');
                }
            }
        });
    });
    $('#close_session').click(function (event) {
        event.preventDefault();
        //$('#btn_menu_movil').click();
        if (cash_id != false) {
            $('#modal_close_session').modal({
                show: true,
                backdrop: 'static'
            });
        } else {
            location.href = site_url + "login/sign_out";
        }
    });
    /* Evento para seleccionar caja y si tiene permiso para cerrar caja */
    // $('#select_cash_and_permission_close_cash').click(function (event) {
    //     select_cash_and_permission_close_cash();
    // });

    $('input[type=number').focusout(function () {
        if ($(this).attr('step')) {
            var monto = $(this).val();
            var monto = monto.split('.');
            if (monto[1] === undefined) {
                var entero = parseInt(monto[0]);
                $(this).val(entero.toFixed(2))
            }
        }
    });

  
        /* Cierra la caja de ventas */
    function select_cash_and_permission_close_cash() {
        ajaxStart('Verificando caja y permiso para cerrar');
        $.ajax({
            url: site_url + 'cash/select_cash_and_permission_close_cash',
            type: 'post',
            dataType: 'json',
            async: false,
            success: function (response) {
                ajaxStop();

                var selected_cash = response.selected_cash;
                var login = response.login;
                var state_cash = response.state_cash;
                var permission_close_cash = response.permission_close_cash;

                if (selected_cash == true) {

                    if (state_cash === true) {

                        swal('No se puede cerrar la caja', 'La caja seleccionada ya esta cerrada.', 'info');

                    } else {
                        $.redirect(site_url + 'cash/show_closing_cash', {
                            verificacion: permission_close_cash
                        }, 'POST', '_self');
                    }


                } else if (login == true) {
                    $('#modal_close_session').modal('hide');
                    login_session();
                } else {
                    swal.close()
                    $('#modal_close_session').modal('hide');
                    select_cash();
                }
            }
        });
    }

    $(function () {
        $(".select2").select2();
//        $('#optgroup').multiSelect({ selectableOptgroup: true });
    });

    function siteurl(url) {
        return site_url + url;

    }
	function initiateSelect2() {
		$('.select2').select2();
	}

    function baseurl(url) {
        return base_url + url;
    }
    function select_printer() {
		event.preventDefault();
		var printer_id = $('#printer_id').val();
		$.ajax({
			url: siteurl('dosage/printer_invoice'),
			type: 'post',
			data: {printer_id: printer_id},
			dataType: 'json',
			success: function (respuesta) {
				if (respuesta.success === true) {
					$('.modal-error').remove();
					swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
					$('#close_modal_printer').click();
				} else {
					$('.modal-error').remove();
					if (respuesta.messages !== null) {
						$.each(respuesta.messages, function (key, value) {
							var element = $('#' + key);
							var parent = element.parent();
							parent.removeClass('form-line');
							parent.addClass('form-line error');
							parent.after(value);
						});
					} else {
						swal('Error', 'Eror al registrar los datos.', 'error');
					}
				}
			}
		});
	}
	function get_cash_enable() {
        $.ajax({
            url: site_url + 'cash/get_cash_enabled',
            type: 'post',
            dataType: 'json',
            async: false,
            success: function (response) {
                var data = JSON.parse(response);
                var login = data.login;
                var login_cash = data.login_cash;
                var check = data.check;

                if (login == true) {
                    login_session();

                } else if (login_cash != true) {

                    if (check == true) {
						var result = data.result;
						console.log(result);
                        // $.each(result, function (i, item) {
						$('#cash_id').empty();
						$.each(result, function (i, item) {
							$('#cash_id').append('<option value="' + item.id + '">' + item.nombre + '</option>');
						});
                        // });

                        $(".modal-error").empty();
						$('#modal_select_cash').modal({
							show: true,
							backdrop: 'static'
						});
						// open_select_cash();
                    } else {
                        // swal('Caja', 'No existen ninguna cajas habilitadas para este usuario.', 'info');
                    }
                }
            }
        });
    }

</script>


<!-- Bootstrap Core Js -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<!-- Select Plugin Js -->
<!--<script src="--><? //= base_url('assets/js/bootstrap-select.js') ?><!--"></script>-->
<!-- Slimscroll Plugin Js -->
<script src="<?= base_url('assets/js/jquery.slimscroll.js') ?>"></script>
<!-- Jquery Validation Plugin Css -->
<script src="<?= base_url('assets/js/jquery.validate.js') ?>"></script>
<!-- JQuery Steps Plugin Js -->
<script src="<?= base_url('assets/js/jquery.steps.js') ?>"></script>
<!-- Sweet Alert Plugin Js -->
<script src="<?= base_url('assets/sweetalert/sweetalert.min.js') ?>"></script>
<!-- Waves Effect Plugin Js -->
<script src="<?= base_url('assets/node-waves/waves.js') ?>"></script>
<!-- Custom Js -->
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<!--<script src="../../js/pages/forms/form-validation.js"></script>-->
<!-- Demo Js -->
<script src="<?= base_url('assets/js/demo.js') ?>"></script>

<!--<script src="--><? //= base_url('assets/datatables/jquery.dataTables.js') ?><!--"></script>-->
<!--<script src="--><? //= base_url('assets/datatables/jquery.dataTables.min.js') ?><!--"></script>-->

<script src="<?= base_url('jsystem/common.js') ?>"></script>

<script src="<?= base_url('jsystem/close_session.js'); ?>"></script>
<!--<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.15/js/dataTables.material.min.js"></script>-->
</body>
</html>
