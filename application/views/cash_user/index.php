<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 08/04/2020
 * Time: 15:02 PM
 */ ?>

<div class="block-header" id="container_buttons">
    
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Usuarios Para la Asignacion de Cajas</h2>
            </div>
            <div class="body">
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table id="cash_users_list" class="table table-striped table-bordered ">
                        <thead>
                        <th>ID</th>
                        <th><b>CI</b></th>
                        <th><b>Nombre Usuario</b></th>
                        <th><b>Telefono</b></th>
                        <th><b>Usuario</b></th>
                        <th><b>Cargo</b></th>
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
<script src="<?= base_url('jsystem/cash_user.js') ?>"></script>