<!DOCTYPE html>
<html>
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



    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/js/jquery-ui.css') ?>" rel="stylesheet">
    <script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
        body {
            height: 100%;
            background-image: url("<?= base_url('assets/images/banner.jpg') ?>");
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        .customer {
            max-width: 360px;
            position: absolute;
            top: 50%; /* Buscamos el centro horizontal (relativo) del navegador */
            left: 50%; /* Buscamos el centro vertical (relativo) del navegador */
            margin-top: -180px;
            margin-left: -180px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
        }

        .customer .customer-box {
            /*background: rgba(255,0,0,0.5);*/
            margin: 30px 0px 30px 0px;

        }

        .customer .customer-box .div-white {
            background-color: white;
            border-radius: 5px 5px 5px 5px;
            -moz-border-radius: 5px 5px 5px 5px;
            -webkit-border-radius: 5px 5px 5px 5px;
        }

        .customer .customer-box .div-white .input-white {
            background-color: rgba(255, 0, 0, 0.5);
            border-radius: 25px 25px 25px 25px;
            -moz-border-radius: 25px 25px 25px 25px;
            -webkit-border-radius: 25px 25px 25px 25px;
        }

        .main-footer {
            background-color: rgba(255, 0, 0, 0.5);
            text-align: center;
            font-family: sans-serif;
            color: whitesmoke;
            width: 100%;
            bottom: 0;
            position: fixed;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="customer">
        <div align="center">
            <img src="<?= base_url('assets/images/clinica_celular.png') ?>"
                 style="max-width:400px; width: 100%; height: 100%">
        </div>
        <div>
            <form id="frm_sign_in" method="POST"
                  action="<?= site_url('cliente/login_validation') ?>">
                <div>
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
                            <input type="password" class="form-control" name="password"
                                   placeholder="Ingrese su clave de acceso"
                                   required>
                        </div>
                    </div>
                </div>
                <div align="center">
                    <div>
                        <button class="btn btn-block bg-red waves-effect" type="submit">Iniciar Sesion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="main-footer">
    <strong>Copyright &copy; 2017 <a href="http://workcorp.net"> WORK CORP </a>.</strong> Derechos reservados.
</footer>
</body>
<script>
    site_url = '<?= site_url() ?>';
    function siteurl(url) {
        return site_url + url;
    }

    $('#frm_sign_in').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success === true) {
                    swal('Datos Correctos', 'Espere por favor mientras se lo redirecciona', 'success');
                    location.href =siteurl('cliente/reporte');
                } else {
                    swal('Error', 'Eror al registrar los datos.', 'error');
                }
            }
        });
    });
</script>


<!-- Bootstrap Core Js -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?= base_url('assets/node-waves/waves.js') ?>"></script>

<script src="<?= base_url('assets/js/jquery-ui-1.10.3.min.js') ?>"></script>

<!-- Validation Plugin Js -->
<script src="<?= base_url('assets/js/jquery.validate.js') ?>"></script>

<!-- JQuery Steps Plugin Js -->
<script src="<?= base_url('assets/js/jquery.steps.js') ?>"></script>

<!-- Sweet Alert Plugin Js -->
<script src="<?= base_url('assets/sweetalert/sweetalert.min.js') ?>"></script>


</html>
