<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>LAST LEVEL</title>

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
    <style>
        .fondo {
            background-image: url("<?= base_url('assets/images/banner.jpg') ?>");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            background-color: #e7c3c3;
            max-height: 865px;
        }

        .fondo .login {
            text-align: center;
            align-items: center;
            align-content: center;
            margin: auto;
            max-width: 360px;
            padding-top: 10%;
        }

        .fondo .login .customer {
            margin: 30px 0px 30px 0px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.5);
            display: inline-block;
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
        }

        .fondo .login .customer .customer-box {
            /*background: rgba(255,0,0,0.5);*/
            margin: 30px 0px 30px 0px;

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
    </style>

</head>

<body class="fondo">

<div class="login">
    <div align="center">
        <img src="<?= base_url('assets/images/clinica_celular.png') ?>"
             style="max-width:400px; width: 100%; height: 100%">
    </div>
    <div class="customer">
        <form id="sign_in" method="POST">
            <div class="customer-box">
                <div class="input-group div-white">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                    <div class="input-white">
                        <input type="text" class="form-control" name="username" placeholder="Ingrese su usuario"
                               required
                               autofocus>
                    </div>
                </div>
                <div class="input-group div-white">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                    <div class="input-white">
                        <input type="password" class="form-control" name="password" placeholder="Ingrese su clave de acceso"
                               required>
                    </div>
                </div>
            </div>
            <div  align="center">
                <div >
                    <a class="btn btn-block bg-red waves-effect" type="submit">Iniciar Sesion</a>
                </div>
            </div>
        </form>
    </div>
</div>
</body>

<!-- Jquery Core Js -->
<script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>

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

<!-- Demo Js -->
<script src="<?= base_url('assets/js/demo.js') ?>"></script>
</html>