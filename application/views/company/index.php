<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 02:58 PM
 */
?>
<div class="block-header">
    <!-- <button class="btn btn-success" type="button" id="btn_new_branch_office"><i class="fa fa-plus"></i> Nueva
        Sucursal
    </button> -->
    <button class="btn bg-warning" data-toggle="modal" data-target="#modal_registro_actividad"><i
                class="fa fa-plus"></i> Nueva Actividad
    </button>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Lista de Sucursales y Actividades</h2>
            </div>
            <div class="body">
                <ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#home" data-toggle="tab">Lista de Sucursales</a>
                    </li>
                    <li role="presentation"><a href="#profile" data-toggle="tab">Lista de Actividades</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="home">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table id="list_branch_office" class="table table-striped table-bordered">
                                <thead>
                                <th>ID</th>
                                <th style="width: 10%; text-align: center"><b>NIT</b></th>
                                <th style="width: 13%; text-align: center"><b>Nombre SIN</b></th>
                                <th style="width: 25%; text-align: center"><b>Nombre Comercial</b></th>
                                <th style="width: 12%; text-align: center"><b>Telefono</b></th>
                                <th style="width: 20%; text-align: center"><b>Direccion</b></th>
                                <th style="width: 10%; text-align: center"><b>Ciudad</b></th>
                                <th style="width: 10%; text-align: center"><b>Opciones</b></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile">
                        <div style="padding: 1%;" class="card-content table-responsive">
                            <table style="width: 100%" id="lista_actividad" class="table table-striped table-bordered ">
                                <thead>
                                <th>ID</th>
                                <th><b>Nombre</b></th>
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
    </div>
</div>

<!-- MODAL PARA REGISTRO Y EDICION DE SUCURSAL -->
<div class="modal fade" id="modal_new_branch_office" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Registro Sucursal</h4>
            </div>
            <form id="frm_new_branch_office" class="frm-modal" action="<?= site_url('company/register_branch_office') ?>"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="tipo" name="tipo" class="form-control">
                                        <option value="1">Sucursal Propia</option>
                                        <option value="2">Franquicia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nit</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit" name="nit" placeholder="Ingrese el nit de la sucursal"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre Sucursal (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_sin" name="sucursal_sin" placeholder="Ingrese el dato como esta registrado en el SIN"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Sucursal (Comercial)</label>
                        </div>
                        <div class="col-lg-9 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_comercial" name="sucursal_comercial" placeholder="Ejemplo: Sucursal Ramada, Sucursal Equipetrol . . ."
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_telefono" name="sucursal_telefono" placeholder="Ingrese los telefonos que sean necesarios. Ejemplo: 70000100-60000124"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_direccion" name="sucursal_direccion" placeholder="Ingrese su direccion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Correo</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_correo" name="sucursal_correo" placeholder="Si ingresa mas de un correo separelo con un -"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="departamento" name="departamento" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="ciudad_impuestos" name="ciudad_impuestos" class="form-control" placeholder="Ejemplo: Santa Cruz - Bolivia"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Enlace WEB</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="url" name="url" class="form-control" placeholder="Ejemplo: www.canis-care.tk/sistema/cliente"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_branch_office" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para editar sucursales -->
<div class="modal fade" id="modal_edit_branch_office" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Editar Sucursal Seleccionada</h4>
            </div>
            <form id="frm_edit_branch_office" class="frm-modal" action="<?= site_url('company/edit_branch_office') ?>" method="post">
                <div class="modal-body">
                    <input type="text" id="id_branch_office" name="id_branch_office" hidden>
                    <div class="row clearfix">

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="tipo_edit" name="tipo_edit" class="form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nit</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit_edit" name="nit_edit" placeholder="Ingrese el nit de la sucursal"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre Sucursal (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_sin_edit" name="sucursal_sin_edit" placeholder="Ingrese el dato como esta registrado en el SIN"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Sucursal (Comercial)</label>
                        </div>
                        <div class="col-lg-9 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_comercial_edit" name="sucursal_comercial_edit" placeholder="Ejemplo: Sucursal Ramada, Sucursal Equipetrol . . ."
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_telefono_edit" name="sucursal_telefono_edit" placeholder="Ingrese los telefonos que sean necesarios. Ejemplo: 70000100-60000124"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_direccion_edit" name="sucursal_direccion_edit" placeholder="Ingrese su direccion"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Correo</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_correo_edit" name="sucursal_correo_edit" placeholder="Si ingresa mas de un correo separelo con un -"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <select id="departamento_edit" name="departamento_edit" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="ciudad_impuestos_edit" name="ciudad_impuestos_edit" class="form-control" placeholder="Ejemplo: Santa Cruz - Bolivia"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Enlace WEB</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="url_edit" name="url_edit" class="form-control" placeholder="Ejemplo: www.canis-care.tk/sistema/cliente"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar</button>
                    <button id="close_modal_edit_branch_office" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_view_branch_office" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">Ver Datos de la Sucursal</h4>
            </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Tipo de Sucursal</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="tipo_view" name="tipo_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nit</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="nit_view" name="nit_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label" >Nombre Sucursal (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_sin_view" name="sucursal_sin_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Sucursal (Comercial)</label>
                        </div>
                        <div class="col-lg-9 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_comercial_view" name="sucursal_comercial_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Telefono</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_telefono_view" name="sucursal_telefono_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Direccion</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_direccion_view" name="sucursal_direccion_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Correo</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" id="sucursal_correo_view" name="sucursal_correo_view" placeholder="No tiene registrado este dato"
                                           value="" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="departamento_view" name="departamento_view" class="form-control" placeholder="No tiene registrado este dato" disabled/>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Departamento (SIN)</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="ciudad_impuestos_view" name="ciudad_impuestos_view" class="form-control" placeholder="No tiene registrado este dato" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-label">Enlace WEB</label>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="url_view" name="url_view" class="form-control" placeholder="No tiene registrado este dato" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="close_modal_branch_office" type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                </div>
        </div>
    </div>
</div>
<!--------------------------- MODAL PARA EL REGISTRO Y EDICION DE ACTIVIDAD ---------------------->
<div class="modal fade" id="modal_registro_actividad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header cabecera_modal">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white;font-size: 15pt">
                </button>
                <h4 class="modal-title titulo_modal" id="defaultModalLabel">REGISTRO DE ACTIVIDAD ECONOMICA</h4>
            </div>
            <form id="frm_registrar_actividad" action="<?= site_url('company/registrer_activity') ?>" method="post">
                <?php $this->view('company/form_content_actividad') ?>
            </form>
        </div>
    </div>
</div>
<!-- EDICION DE LA ACTIVIDAD -->
<div class="modal fade" id="modal_editar_actividad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">EDITAR ACTIVIDAD ECONOMICA</h4>
            </div>
            <form id="frm_editar_actividad" class="frm-modal" action="<?= site_url('company/edit_activity') ?>"
                  method="post">
                <div class="modal-body">
                    <div class="row clearfix">
                        <input id="id_actividade" name="id_actividade" value="" hidden>
                        <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-label">Nombre Actividad</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea class="form-control" id="nombre_actividade" name="nombre_actividade"
                                              placeholder="Ingrese el nombre de su actividad economica"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
                    </button>
                    <button id="close_modal_editar_actividad" type="button" class="btn btn-danger waves-effect"
                            data-dismiss="modal"><i
                                class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('jsystem/company.js') ?>"></script>
<script src="<?= base_url('jsystem/branch_office.js') ?>"></script>
