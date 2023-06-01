<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/07/2017
 * Time: 12:37 AM
 */
?>
<div class="card" style="background:#ffffff1f;">
    <div class="body">
        <form method="POST" action="<?= site_url('login/sign_in') ?>">
            <div class="msg">Ingrese los datos para iniciar sesion</div>
            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">home</i>
                        </span>
                <div class="form-line">
                    <select name="branch_office" id="branch_office" class="form-control" required>
                        <option value=""> Seleccione su sucursal</option>
                        <?php foreach ($branch_offices as $row_office):?>
                            <option value="<?= $row_office->id?>"><?= $row_office->nombre_comercial?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                <div class="form-line">
                    <input type="text" class="form-control" name="username" placeholder="Usuario" required autofocus value="<?= $this->session->flashdata("acount_login")?>">
                    <span class="text-danger"><?php echo form_error('username') ?></span>
                </div>
            </div>
            <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                <div class="form-line">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                    <span class="text-danger"><?php echo form_error('password') ?></span>
                </div>
            </div>
            <div class="row">

                <div class="col-xs-12">
                    <?php
                    echo '<label class="text-danger">' . $this->session->flashdata("error") . '</label>';
                    echo '<label class="text-danger">' . $this->session->flashdata("branch_office") . '</label>';
                    ?>
                    <button class="btn btn-block bg-red waves-effect" type="submit">Ingresar</button>

                </div>
                <div class="col-xs-12">
                    <div class="small" style="text-align: center">
                        | © 2022 |
                    </div>
                    <div class="small" style="text-align: center">
						<!-- <a target="_blank" href="http://workcorp.net">http://workcorp.net</a>-->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
