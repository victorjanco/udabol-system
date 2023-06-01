<?php

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>UDABOL</title>
    <!-- Favicon-->
    <link rel="icon" type="image/ico" href="<?= base_url('assets/images/logo.ico') ?>"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Multi select   -->
    <link rel="stylesheet" href="<?= base_url('assets/select2/select2.min.css') ?>">
    <!--    <link rel="stylesheet" href="-->
    <? //= base_url('assets/bootstrap-select/css/bootstrap-select.min.css') ?><!--">-->

    <!-- Waves Effect Css -->
    <link href="<?= base_url('assets/node-waves/waves.css') ?>" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="<?= base_url('assets/css/animate.css') ?>" rel="stylesheet"/>

    <script src="<?= base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>
    <!-- Sweet Alert Css -->
    <link href="<?= base_url('assets/sweetalert/sweetalert.css') ?>" rel="stylesheet"/>

    <!-- Custom Css -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/css-datatables.css') ?>" rel="stylesheet">
    <!-- Css system-->
    <link href="<?= base_url('assets/css/css-system.css') ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= base_url('assets/css/all-themes.css') ?>" rel="stylesheet"/>

    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/js/jquery-ui.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-style.css'); ?>">
    <script src="<?= base_url('assets/js/jquery.redirect.js') ?>"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.js"></script>
    <script src="<?= base_url('assets/datatables/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery-ui-1.10.3.min.js') ?>"></script>
    <script src="<?= base_url('assets/select2/select2.full.min.js') ?>"></script>
    <!--    <script src="--><? //= base_url('assets/bootstrap-select/js/bootstrap-select.min.js') ?><!--"></script>-->

    <style type="text/css">
        /*Estilo para cabecera de un formulario de modal*/
        .cabecera_modal {
            background-color: #f44336;
        }

        /*Estilo para titulo de un formulario de modal*/
        .titulo_modal {
            color: white;
            font-size: 50pt;
        }

        /*Estilo para cabecera de un formulario de frm*/
        .cabecera_frm {
            background-color: #f44336;
        }

        /*Estilo para titulo de un formulario de frm*/
        .titulo_frm {
            color: white !important;
            font-size: 20pt;
        }

        .name_profile {
            color: white !important;
            font-size: 11pt;
            font-weight: bold;
        }

        /* Estilo para quitar flechas de input de tipo number */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .abm-error, .modal-error {
            color: red;
            font-weight: bold;
            font-style: italic;
        }

        input[type=number] {

            -moz-appearance: textfield;

        }

        ul.ui-autocomplete {
            z-index: 1100;
        }

        .text-align-left {
            text-align: left !important;
        }
    </style>
</head>

<body class="theme-red">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Cargando datos...</p>
    </div>
</div>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">

            <!--<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
               data-target="#navbar-collapse" aria-expanded="false"></a>-->
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="<?= site_url('home/index') ?>"> LAST LEVEL </a>
        </div>
            <div class="nav navbar-nav navbar-right">
                <div align="center">
                <label style="text-align: center; color: white;font-size: 15pt;margin-right: 2% "><?=get_branch_office_name_in_session()?></label>
            </div>
            </div>
    </div>
</nav>
<section>
    <aside id="leftsidebar" class="sidebar">
        <div class="menu">
            <ul class="list">
                <li class="active">
                    <a href="<?= site_url('home/index') ?>">
                        <i class="material-icons">home</i>
                        <span>INICIO</span>
                    </a>
                </li>

                <li>
                    <a id="user_profile">
                        <i class="material-icons">account_box</i>
                        <span>PERFIL</span>
                    </a>
                </li>
                <?= $this->multi_menu->render(); ?>
                <!-- <li class="active">
                    <a class="menu-toggle" id="select_cash_and_permission_close_cash" class="menu-toggle">
                        <i class="material-icons">attach_money</i>
                        <span> CERRAR CAJA </span>
                    </a>
                </li> -->
                <li class="active">
                    <!-- <a class="menu-toggle" id="close_session">
                        <i class="material-icons">power_settings_new</i>
                        <p> CERRAR SESION </p>
                    </a> -->
                    <a href="<?= site_url('login/sign_out') ?>" class="menu-toggle">
                        <i class="material-icons">settings_power</i>
                        <span>CERRAR SESION</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="legal">
            <div class="copyright">
<!--                &copy; <a target="_blank" href="http://workcorp.net/">WORK CORP SRL</a>.-->
            </div>
        </div>
    </aside>
</section>
<section class="content">
    <div class="container-fluid">
