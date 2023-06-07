<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 21/8/2017
 * Time: 10:45 AM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>SISTEMA VENTA</title>

    <!-- Favicon-->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= base_url('assets/node-waves/waves.css') ?>" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="<?= base_url('assets/css/animate.css') ?>" rel="stylesheet"/>
    <!-- Sweet Alert Css -->
    <link href="<?= base_url('assets/sweetalert/sweetalert.css') ?>" rel="stylesheet"/>

    <!-- Custom Css -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= base_url('assets/css/all-themes.css') ?>" rel="stylesheet"/>

    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/js/jquery-ui.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>

    <style>
        .fondo {
            background-color: rgba(255, 0, 0, 0.85);
        }

        .fondo .box-register {
            background: rgba(0, 0, 0, 0.25);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
            color: white;
            margin: 40px 0px 0px 0px;
        }

        .fondo .box-register .div-title {
            font-size: 25pt;
            text-align: center;
            align-content: center;
            align-items: center;
            padding-top: 3%;
        }
        .fondo .box-register .div-title-2 {
            text-align: left;
            padding-left: 5%;
        }

        .fondo .box-register .div-row {
            padding: 3%;
        }

        .fondo .box-register .div-margin {
            margin-left: 4%;
            margin-right: 4%;
            margin-bottom: 4%;
            border: 3px solid #ffffff;
            padding-bottom: 3%;
        }

        .fondo .box-register .input-border {
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;

        }
    </style>
</head>
<body class="fondo">
<div>
    <div class="col-lg-offset-3 col-lg-6 col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
        <div class="box-register">
            <div class="div-title">
                <h2>Registarse</h2>
            </div>
            <form id="frm_register_customer" class="frm-event-submit" action="<?= site_url('customer/register_customer') ?>"
                  method="post">
                <div class="body">
                    <div class="div-title-2">
                        <h4>Datos Personales</h4>
                    </div>
                    <div class="div-row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="" for="ci">Carnet Identidad</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">
                            <input type="text" class="form-control" id="ci" name="ci"
                                   value=""
                                   placeholder="Carnet de Identidad del Cliente"
                                   title="Carnet de Identidad del Cliente" required>
                        </div>
                    </div>
                    <div class="div-row ">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 ">
                            <label class="form-control-label" for="nombre">Nombre Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   value=""
                                   placeholder="Nombre Complero del Cliente" title="Nombre Complero del Cliente"
                                   required>

                        </div>
                    </div>
                    <div class="div-row ">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Telefono 1 Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="telefono1" name="telefono1"
                                   value=""
                                   placeholder="Telefono del Cliente" title="Telefono del Cliente">

                        </div>
                    </div>
                    <div class="div-row ">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="telefono">Telefono 2 Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="telefono2" name="telefono2"
                                   value=""
                                   placeholder="Telefono del Cliente" title="Telefono del Cliente">

                        </div>
                    </div>
                    <div class="div-row ">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nombre">Direccion Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="direccion" name="direccion"
                                   value=""
                                   placeholder="Direccion del Cliente" title="Direccion del Cliente">

                        </div>
                    </div>
                    <div class="div-row ">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="email">Email Cliente</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">
                            <input type="text" class="form-control" id="email" name="email"
                                   value=""
                                   placeholder="Correo Electronico del Cliente"
                                   title="Correo Electronico del Cliente">
                        </div>
                    </div>
                    <div class="div-row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Ciudad</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <select id="ciudad" name="ciudad" class="form-control">
                                <option value="0">Seleccione una Ciudad</option>
                                <option value="Santa Cruz">Santa Cruz</option>
                                <option value="Cochabamba">Cochabamba</option>
                                <option value="La Paz">La Paz</option>
                                <option value="Tarija">Tarija</option>
                                <option value="Beni">Beni</option>
                                <option value="Chuquisaca">Chuquisaca</option>
                                <option value="Pando">Pando</option>
                                <option value="Oruro">Oruro</option>
                                <option value="Potosi">Potosi</option>
                            </select>
                        </div>
                    </div>
                    <div class=" div-row">
                    </div>
                    <div class="div-title-2">
                        <h4>Datos para su Factura</h4>
                    </div>
                    <div class=" div-row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nit">NIT </label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="nit" name="nit"
                                   value=""
                                   placeholder="NIT para su Factura"
                                   title="Numero de Identificacion Triburaria de Impuestos del Cliente">

                        </div>
                    </div>
                    <div class=" div-row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" for="nombre_factura">Nombre para Factura </label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 input-border">

                            <input type="text" class="form-control" id="nombre_factura" name="nombre_factura"
                                   value=""
                                   placeholder="Nombre para su Factura"
                                   title="Nombre para la Factura del Cliente">

                        </div>
                    </div>
                    <div class="div-row" align="center">
                        <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                        </button>
                        <button type="reset" class="btn btn-warning waves-effect"><i class="fa fa-refresh"></i> Limpiar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
<script>
    site_url = '<?= site_url() ?>';

    function siteurl(url) {
        return site_url + url;
    }

    $('#frm_register_customer').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    $('.abm-error').remove();
                    swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                    // Si es un vista de formulario
                    if ( !$(this).hasClass('frm-noreset') ) {
                        $(form)[0].reset();
                    }

                } else {
                    $('.abm-error').remove();
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
    });
</script>
<!-- Bootstrap Core Js -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
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

</html>

