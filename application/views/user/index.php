<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 02:33 AM
 */
?>
<div class="block-header">
    <a class="btn btn-success" href="<?= site_url('user/new_user') ?>"><i class="fa fa-plus"></i> Nuevo Usuario</a>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Usuarios</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="users_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th><b>CI</b></th>
                        <th><b>Nombre Usuario</b></th>
                        <th><b>Telefono</b></th>
                        <th><b>Cargo</b></th>
                        <th><b>Usuario</b></th>
                        <th><b>Estado</b></th>
                        <th><b>Opciones</b></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/user.js') ?>"></script>