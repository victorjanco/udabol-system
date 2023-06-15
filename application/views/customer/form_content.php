<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 11/7/2017
 * Time: 1:51 PM
 */
$select1 = '';
$select2 = '';
$select3 = '';
$select4 = '';
$select5 = '';
$select6 = '';
$select7 = '';
$select8 = '';
$select9 = '';
if (isset($customer)) {
    switch ($customer) {
        case "Santa Cruz" == $customer->ciudad:
            $select1 = "selected";
            break;
        case "Cochabamba" == $customer->ciudad:
            $select2 = "selected";
            break;
        case "La Paz" == $customer->ciudad:
            $select3 = "selected";
            break;
        case "Tarija" == $customer->ciudad:
            $select4 = "selected";
            break;
        case "Beni" == $customer->ciudad:
            $select5 = "selected";
            break;
        case "Chuquisaca" == $customer->ciudad:
            $select6 = "selected";
            break;
        case "Pando" == $customer->ciudad:
            $select7 = "selected";
            break;
        case "Oruro" == $customer->ciudad:
            $select8 = "selected";
            break;
        case "Potosi" == $customer->ciudad:
            $select9 = "selected";
            break;
    }
}
?>
<!--Form body-->
<input id="id" name="id" type="text" value="<?= isset($customer) ? $customer->id : null; ?>" hidden>
<div class="panel-body">
    <h3>Datos para su Servicio</h3>
    <div class="row clearfix" hidden>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="codigo">Codigo</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control" id="codigo" name="codigo"
                       value="<?= isset($customer) ? $customer->codigo_cliente : 'cod-001'; ?>"
                       title="Codigo del Cliente" disabled>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="ci">CI</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="ci" name="ci"
                           value="<?= isset($customer) ? $customer->ci : null; ?>"
                           placeholder="Carnet de Identidad del Cliente" title="Carnet de Identidad del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="nombre">Nombre</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?= isset($customer) ? $customer->nombre : null; ?>"
                           placeholder="Nombre Complero del Cliente" title="Nombre Complero del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="telefono">Telefono 1</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="telefono1" name="telefono1"
                           value="<?= isset($customer) ? $customer->telefono1 : null; ?>"
                           placeholder="Telefono del Cliente" title="Telefono del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="telefono">Telefono 2</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="telefono2" name="telefono2"
                           value="<?= isset($customer) ? $customer->telefono2 : null; ?>"
                           placeholder="Telefono del Cliente" title="Telefono del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="email">Email</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="email" name="email"
                           value="<?= isset($customer) ? $customer->email : null; ?>"
                           placeholder="Correo Electronico del Cliente" title="Correo Electronico del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="nombre">Direccion</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="direccion" name="direccion"
                           value="<?= isset($customer) ? $customer->direccion : null; ?>"
                           placeholder="Direccion del Cliente" title="Direccion del Cliente">
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix" hidden>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label">Tipo Cliente</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select id="tipo" name="tipo" class="form-control">
                        <option value="0">Cliente Diario</option>
                        <option value="1">Cliente por Mayor</option>
                        <!-- <option value="2">Cliente Express</option> -->
                        <!-- <option value="3">Cliente Laboratorio</option> -->
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix" hidden>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label">Departamento</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <select id="ciudad" name="ciudad" class="form-control">
                        <!-- <?= isset($customer) ? null : '<option value="0">:: Seleccione una opcion</option>' ?> -->
                        <option value="Santa Cruz" <?= $select1 ?>>Santa Cruz</option>
                        <option value="Cochabamba" <?= $select2 ?>>Cochabamba</option>
                        <option value="La Paz" <?= $select3 ?>>La Paz</option>
                        <option value="Tarija" <?= $select4 ?>>Tarija</option>
                        <option value="Beni" <?= $select5 ?>>Beni</option>
                        <option value="Chuquisaca" <?= $select6 ?>>Chuquisaca</option>
                        <option value="Pando" <?= $select7 ?>>Pando</option>
                        <option value="Oruro" <?= $select8 ?>>Oruro</option>
                        <option value="Potosi" <?= $select9 ?>>Potosi</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- <h3>Datos para su Factura</h3> -->
    <div class="row clearfix" hidden>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="nit">NIT </label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control" id="nit" name="nit"
                       value="<?= isset($customer) ? $customer->nit : null; ?>"
                       placeholder="Numero de Identificacion Triburaria de Impuestos del Cliente"
                       title="Numero de Identificacion Triburaria de Impuestos del Cliente">
            </div>
        </div>
    </div>
    <div class="row clearfix" hidden>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-label" for="nombre_factura">Nombre factura</label>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="form-line">
                    <input type="text" class="form-control" id="nombre_factura" name="nombre_factura"
                           value="<?= isset($customer) ? $customer->nombre_factura : null; ?>"
                           placeholder="Ingreso Nombre o Razon Social para facturar"
                           title="Nombre Completo para la factura">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-footer" align="center">
    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-save"></i> Guardar
    </button>
    <button type="reset" class="btn btn-warning waves-effect"><i class="fa fa-refresh"></i> Limpiar</button>
    <a type="button" href="<?= site_url('customer/index') ?>" class="btn btn-danger waves-effect "><i
                class="fa fa-arrow-left"></i> Cancelar</a>
</div>
