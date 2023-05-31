<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 13/07/2017
 * Time: 02:33 AM
 */
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <form id="frm_new_user" action="<?= site_url('user/registrer_user') ?>"
                  method="post">
                <?php $this->view('user/form_content') ?>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PARA REGISTRAR SUCURSALES-->
<div class="modal fade" id="modal_registrar_sucursal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Registro Sucursal</h4>
            </div>
            <form id="frm_registrar_sucursal" class="frm-modal" action="<?= site_url('user/registrer_office') ?>"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Sucursal (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_sin" name="sucursal_sin"
                                           placeholder="Ingrese el dato como esta registrado en el SIN"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Sucursal (Comercial)</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_comercial"
                                           name="sucursal_comercial"
                                           placeholder="Ejemplo: Sucursal Ramada, Sucursal Equipetrol . . ."
                                           value="">
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
                                    <input type="text" class="form-control" id="sucursal_telefono"
                                           name="sucursal_telefono"
                                           placeholder="Ingrese los telefonos que sean necesarios. Ejemplo: 70000100-60000124"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_direccion"
                                           name="sucursal_direccion" placeholder="Ingrese su direccion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Correo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_correo" name="sucursal_correo"
                                           placeholder="Si ingresa mas de un correo separelo con un -"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="departamento" name="departamento" class="form-control">
                                        <option value="0">:: Seleccione una opcion</option>
                                        <option value="Santa Cruz">Santa Cruz</option>
                                        <option value="Beni">Beni</option>
                                        <option value="Pando">Pando</option>
                                        <option value="Cochabamba">Cochabamba</option>
                                        <option value="Chuquisaca">Chuquisaca</option>
                                        <option value="Tarija">Tarija</option>
                                        <option value="La Paz">La Paz</option>
                                        <option value="Oruro">Oruro</option>
                                        <option value="Potosi">Potosi</option>
                                    </select>
                                    <input type="text" id="ciudad" name="ciudad" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL PARA REGISTRAR CARGOS -->
<div class="modal fade" id="modal_registrar_cargo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Registro Cargos</h4>
            </div>
            <form id="frm_registrar_cargo" class="frm-modal" action="<?= site_url('user/registrer_charge') ?>"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Cargo</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="cargo_nombre" name="cargo_nombre"
                                           placeholder="Ingrese el nombre del cargo"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="modal_cerrar_cargo" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/user.js') ?>"></script>
<script>
    $(document).ready(function () {
        get_offices();
        $('#frm_registrar_cargo').submit(function (event) {
            event.preventDefault();

            var form = $(this);
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function (respuesta) {
                    if (respuesta.success === true) {
                        $('.error').remove();
                        swal('Datos Guardados', 'Los datos se registraron correctamente', 'success');
                        $('#modal_cerrar_cargo').click();
                        get_offices();
                    } else {
                        $('.error').remove();
                        if (respuesta.messages !== null) {
                            $.each(respuesta.messages, function (key, value) {
                                var element = $('#' + key);
                                element.after(value);
                            });
                        } else {
                            swal('Error', 'Eror al registrar los datos.', 'error');
                        }
                    }
                }
            });
        });
    });

    function get_offices() {
        $.post(site_url + "user/get_charges",
            function (data) {
                var datos = JSON.parse(data);
                $('#cargo').empty();
                $.each(datos, function (i, item) {
                    $('#cargo').append(
                        '<option value="' + item.id + '">' + item.descripcion + '</option>'
                    );
                });
            });
    }
</script>