<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 02:34 AM
 */

// Verificamos si el tipo para editar esta habilitado
// para ocultar los campos de usario y las contraseñas
if (isset($type)) {
    $hidden = 'hidden';
} else {
    $hidden = '';
}
//Obtenemos las oficinas seleccionadas
$array_office_user = array();
if (isset($office_user)) {
    foreach ($office_user as $row_office) {
        $array_office_user[] = $row_office->id;
    }
}
//Obtenermos las funciones del usuario
$array_function_user = array();
if (isset($functions_user)) {
    foreach ($functions_user as $row_function) {
        $array_function_user[] = $row_function->id;
    }
}
$title = "Registro de Usuario y Asignacion de Privilegios";
?>
<div class="panel-heading cabecera_frm ">
    <div style="">
        <h2 class="panel-title titulo_frm"><?= $title ?></h2>
    </div>

</div>

<div class="body">
    <div class="row">
        <div class="col-md-7">
            <div class="row clearfix">
                <input type="text" id="id_usuario" name="id_usuario" value="<?= isset($user) ? $user->id : null ?>"
                       hidden>
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">CI</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="ci_usuario" name="ci_usuario"
                                   placeholder="CI del usuario"
                                   value="<?= isset($user) ? $user->ci : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Nombre</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                                   placeholder="Nombre completo del usuario"
                                   value="<?= isset($user) ? $user->nombre : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Telefono</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="telefono" name="telefono"
                                   placeholder="Telefono o celular"
                                   value="<?= isset($user) ? $user->telefono : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div <?= $hidden ?> class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Usuario</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario"
                                   minlength="4" maxlength="15"
                                   value="">
                        </div>
                        <div class="help-info">Min. 4, Max. 15 caracteres</div>
                    </div>
                </div>
            </div>
            <div <?= $hidden ?> class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Clave</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" class="form-control" id="clave" name="clave"
                                   placeholder="Clave de cuenta de usuario"
                                   value="">
                        </div>
                        <div class="help-info">Puede ingresar números, letras y caracteres especiales. Min. 4, Max. 15
                            caracteres
                        </div>
                    </div>
                </div>
            </div>
            <div <?= $hidden ?> class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Confirmar Clave</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave"
                                   placeholder="Repita clave para confirmar"
                                   value="">
                        </div>
                        <span id="msj_pass" style="font-weight: bold; font-size: 12pt; color: red;" hidden><em>Las claves no coinciden.</em></span>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Seleccion de Sucursales</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <?php
                        if (isset($offices)) {
                            foreach ($offices as $office) {
                                if (in_array($office->id, $array_office_user)) {
                                    ?>
                                    <input type="checkbox" id="seleccion_sucursal<?= $office->id ?>"
                                           name="seleccion_sucursal[]" value="<?= $office->id ?>"
                                           class="filled-in" checked="checked"/>
                                    <label for="seleccion_sucursal<?= $office->id ?>"> <?= $office->nombre ?></label> &nbsp;&nbsp;
                                    <?php
                                } else {
                                    ?>
                                    <input type="checkbox" id="seleccion_sucursal<?= $office->id ?>"
                                           name="seleccion_sucursal[]" value="<?= $office->id ?>"
                                           class="filled-in"/>
                                    <label for="seleccion_sucursal<?= $office->id ?>"> <?= $office->nombre ?></label> &nbsp;&nbsp;
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <span id="mensaje" style="font-weight: bold; font-size: 12pt; color: red;"><em>No existen sucursales para asignar. <a
                                            href="#modal_registrar_sucursal"
                                            data-toggle="modal">Registrar Aqui.</a> </em></span>
                            <?php
                        }
                        ?>
                        <label class="error" style="color: red; font-size: 12pt;font-weight: bold">Debe seleccionar al
                            menos una opcion</label>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-label">Cargo</label>
                </div>
                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control" id="cargo" name="cargo">
                                <option value="">:: Seleccione un cargo</option>
                                <?php foreach ($charges as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= isset($user) ? is_selected($user->cargo_id, $row->id) : ''; ?>><?= $row->descripcion; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (isset($type)) {
                            ?>
                            <span><em><a style="font-weight: bold; font-size: 12pt; color: red;"
                                         href="#modal_registrar_cargo"
                                         data-toggle="modal"> Registrar Cargos</a></em></span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                    <button class="btn btn-success waves-effect no-modal" type="submit"><i
                                class="fa fa-save"></i> Guardar
                    </button>
                    <a href="<?= site_url('user/index') ?>" class="btn btn-danger waves-effect"><i
                                class="fa fa-times"></i> Cancelar y
                        Salir</a>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="col-md-12">
                <h4 class="title">Privilegios del sistema</h4>
                <p class="">Elija las funciones de acuerdo al cargo para el acceso del usuario al sistema. <br>
                    <label class="error" style="color: red; font-size: 12pt;font-weight: bold">Debe seleccionar al menos
                        una opcion</label>
                </p>
                <div style="padding: 1%;" class="card-content table-responsive">
                    <table style="margin: 0%" id="lista_funciones" class="table table-bordered">
                        <thead>
                        <th class="text-center"><b>Modulos</b></th>
                        <th class="text-center"><b>Funciones</b></th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($functions as $row) {
                            ?>
                            <tr>
                                <td style="text-align: center;vertical-align: inherit"><?= $row['modules']->name ?></td>
                                <td>
                                    <table width="100%">
                                        <?php
                                        foreach ($row['functions'] as $row2) {
                                            if (in_array($row2->id, $array_function_user)) { // array de funciones seleccionadas
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="menu<?= $row2->id ?>" name="menu[]"
                                                               class="filled-in" checked="checked" value="<?= $row2->id ?>"/>
                                                        <label for="menu<?= $row2->id ?>"><?= $row2->name ?></label>
                                                    </td>
                                                </tr>
                                                <?php
                                            } else {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="menu<?= $row2->id ?>" name="menu[]"
                                                               value="<?= $row2->id ?>"
                                                               class="filled-in"/>
                                                        <label for="menu<?= $row2->id ?>"><?= $row2->name ?></label>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>