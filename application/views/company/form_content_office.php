<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 05:53 PM
 */
?>
<div class="modal-body">
    <input type="text" class="form-control" id="id_branch_office" name="id_branch_office" value="" hidden>
    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label class="form-control-label">Tipo de Sucursal</label>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select id="departamento" name="departamento" class="form-control">
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
                        <option value="0">--Seleccione una opcion--</option>
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
