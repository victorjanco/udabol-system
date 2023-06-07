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
    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>

    <!-- Custom Css -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= base_url('assets/css/all-themes.css') ?>" rel="stylesheet"/>

    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/js/jquery-ui.css') ?>" rel="stylesheet">
    <style>
        .fondo {
            /* background-image: url("

        <?= base_url('assets/images/banner.jpg') ?>  ");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            max-height: 865px;*/
            background-color: white;

        }

        .fondo .box-report {
            text-align: center;
            align-items: center;
            align-content: center;
            /*padding: 2%;*/
        }

        .fondo .box-report .customer {
            /*background: rgba(255, 0, 0, 0.75);*/
            background-color: white;
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
            color: black;
            padding: 3%;
        }

        .fondo .box-report .customer .report {
            /*background: rgba(0, 0, 0, 0.65);*/
            background: rgba(234, 20, 20, 0.53);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
            color: black;
            padding: 1%;
        }

        .fondo .box-report .customer .report-customer {
            /*background: rgba(0, 0, 0, 0.65);*/
            background: rgba(234, 20, 20, 0.53);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
            color: black;
            padding: 1%;
        }

        .fondo .login .customer .customer-box .div-white {
            background-color: white;
            border-radius: 5px 5px 5px 5px;
            -moz-border-radius: 5px 5px 5px 5px;
            -webkit-border-radius: 5px 5px 5px 5px;
        }

        .fondo .login .customer .customer-box .div-white .input-white {
            background-color: rgba(255, 0, 0, 0.5);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
        }

        .centrado {
            text-align: center;
            align-items: center;
            align-content: center;
        }

        .left-text {
            text-align: left;
        }

        .right-text {
            text-align: right;
        }
    </style>
</head>
<body class="fondo">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="box-report">
        <div class="customer">
            <div class="body">
                <?php
                if (isset($customer)) { ?>
                    <div class="row report-customer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div>
                                <div class="header centrado">
                                    <h2>REPORTE DE SUS RECEPCIONES</h2>
                                </div>
                                <h3>Datos del Cliente</h3>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Codigo de Cliente
                                        :</b> <?= !empty($customer->codigo_cliente) ? $customer->codigo_cliente : 'No Tiene Codigo Registrado'; ?>
                                </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Nombre:</b>
                                    <?= !empty($customer->nombre) ? $customer->nombre : 'No tiene Nombre Registrado'; ?>
                                </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Carnet : </b>
                                    <?= !empty($customer->ci) ? $customer->ci : 'No tiene Carnet'; ?></label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Telefono 1 : </b>
                                    <?= !empty($customer->telefono1) ? $customer->telefono1 : 'No tiene Telefeno Registrado'; ?>
                                </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Telefono 2
                                        : </b><?= !empty($customer->telefono2) ? $customer->telefono2 : 'No Tiene Telefono Registrado'; ?>
                                </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Correo
                                        : </b><?= !empty($customer->email) ? $customer->email : 'No Tiene Correo Registrado'; ?>
                                </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-text">
                                <label><b>Ciudad : </b><?= $customer->ciudad; ?></label>
                            </div>
                        </div>
                    </div>
                <?php }

                if (isset($reception_enable)) {
                    if(count($reception_enable) > 0){ ?>
                    <div class="row centrado">
                        <h3><label>Avance de las Recepciones Pendientes</label></h3>
                    </div>
                    <?php
                    foreach ($reception_enable as $reception_enable_list) : ?>
                        <div>
                            <h3>Recepcion <?= $reception_enable_list->codigo_recepcion; ?></h3>
                        </div>
                        <div class="row centrado report">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-text">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                    <label><b>Sucursal: </b><?= $reception_enable_list->nombre_comercial; ?>
                                    </label>
                                </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label><b>Vendedor: </b><?= $reception_enable_list->nombre_vendedor; ?>
                                        </label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label><b>Codigo Trabajo: </b><?= $reception_enable_list->codigo_trabajo; ?>
                                        </label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label><b>Marca: </b><?= $reception_enable_list->nombre_marca; ?>
                                        </label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Modelo: <?= $reception_enable_list->nombre_modelo; ?></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Imei: <?= $reception_enable_list->imei; ?></label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-text">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Garantia: <?= $reception_enable_list->garantia; ?></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Accesorio: <?= $reception_enable_list->accesorio_dispositivo; ?></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Fecha Orden Trabajo: <?= $reception_enable_list->fecha_registro_orden; ?></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Fecha Recepcion: <?= $reception_enable_list->fecha_registro_recepcion; ?></label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label><b>Monto Total Trabajo: </b><?= $reception_enable_list->monto_total_orden; ?>
                                        </label>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label><b>Estado Trabajo: </b><?= get_work_order_states($reception_enable_list->estado_trabajo) ?>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-text">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Obvervacion Tecnico: <?= $reception_enable_list->observacion_orden; ?></label>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 left-text">
                                        <label>Obvervacion Recepcion: <?= $reception_enable_list->observacion_recepcion; ?></label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-text">
                                <input type="text" class="knob" value="<?= $reception_enable_list->progreso; ?>"
                                       data-width="150" data-height="150"
                                       data-thickness="0.15" data-fgColor="red"
                                       readonly>
                            </div>
                        </div>
                        <div class="row centrado"><h3></h3></div>
                        <div class="row centrado">
                        <?php
                        $id =($reception_enable_list->recepcion_id);
                         if( $reception_image[$id] != 'SIN-IMAGEN'){
                        foreach ($reception_image[$id] as $reception_image_list) :
                           ?>
                             <div class="report col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                               <img class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " src="<?= $reception_image_list->url ?>">
                             </div>
                             <?php endforeach; }else{
                             ?>
                             <div class="report col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                               <label>Entro Sin Imagen</label>
                             </div>
                             <?php

                         }?>
                        </div>
                    <?php
                        endforeach;
                    }
                } ?>

            </div>
        </div>
    </div>
</div>


<!-- Bootstrap Core Js -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?= base_url('assets/node-waves/waves.js') ?>"></script>

<script src="<?= base_url('assets/js/jquery.redirect.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery-ui-1.10.3.min.js') ?>"></script>

<!-- Validation Plugin Js -->
<script src="<?= base_url('assets/js/jquery.validate.js') ?>"></script>

<!-- Slimscroll Plugin Js -->
<script src="<?= base_url('assets/js/jquery.slimscroll.js') ?>"></script>
<!-- Jquery Validation Plugin Css -->

<!-- JQuery Steps Plugin Js -->
<script src="<?= base_url('assets/js/jquery.steps.js') ?>"></script>

<!-- Sweet Alert Plugin Js -->
<script src="<?= base_url('assets/sweetalert/sweetalert.min.js') ?>"></script>

<!-- Custom Js -->
<!--<script src="--><? //= base_url('assets/js/admin.js') ?><!--"></script>-->
<script src="<?= base_url('assets/jquery-knob/jquery.knob.min.js') ?>"></script>

<!-- Demo Js -->
<!--<script src="<? /*= base_url('assets/js/demo.js') */ ?>"></script>-->
<script src="<?= base_url('jsystem/customer_report.js'); ?>"></script>
</body>
</html>