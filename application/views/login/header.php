<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/07/2017
 * Time: 12:20 AM
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>LOGIN  LAST LEVEL</title>
    <!-- Favicon-->
    <link rel="icon" type="image/ico" href="<?= base_url('assets/images/logo.ico') ?>"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= base_url('assets/node-waves/waves.css') ?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?= base_url('assets/css/animate.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/css-system.css')?>" rel="stylesheet">
</head>
<body class="login-page background-login">
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);"><b>LAST LEVEL</b></a>
        <small>Software</small>
    </div>
    <?php $this->view('login/content') ?>

</div>
<!-- Jquery Core Js -->
<script src="<?= base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
<!-- Bootstrap Core Js -->
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<!-- Waves Effect Plugin Js -->
<script src="<?= base_url('assets/node-waves/waves.js') ?>"></script>
<!-- Validation Plugin Js -->
<script src="<?= base_url('assets/js/jquery.validate.js') ?>"></script>
<!-- Custom Js -->
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
<!--<script src="../../js/pages/examples/sign-in.js"></script>-->
</body>
</html>
