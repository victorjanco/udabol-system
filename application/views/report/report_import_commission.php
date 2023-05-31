<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 17/09/2019
 * Time: 18:46 PM
 */
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm ">
            <div style="">
                <h2 class="panel-title titulo_frm">IMPORTAR COMISION</h2>
            </div>
        </div>
        <form enctype="multipart/form-data" id="frm_import_commission" method="post">

            <div class="panel">
                <div class="panel-body">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="alert alert-success alert-dismissible" style="font-size: 12pt">
                                    <h4><i class="icon fa fa-info"></i> AVISO IMPORTANTE!!</h4>
                                    Asegurarse que que el archivo .excel tenga las siguientes condiciones: <br>
                                    * Los registros tienen que estar en letras mayusculas. <br>
                                    * Los registros NO tienen que estar con tildes. <br>
                                    * Asegurarse que no haya error ortográfico. <br>
                                    * Evitar en usar letras o caracteres especiales.
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label>Importar: </label>
                                        <input type="file" class="form-control" id="excel" name="excel" required/>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 ol-xs-12" align="center">
                                <button type="submit" class="btn btn-success waves-effect"><i
                                        class="fa fa-save"></i> Importar y Guardar
                                </button>
                                <a href="<?= site_url('report_commission/index') ?>" class="btn btn-danger waves-effect"
                                   type="submit"><i
                                        class="fa fa-times"></i> Cancelar y
                                    Salir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#frm_import_commission').on('submit', function (event) {
            event.preventDefault();
            ajaxStart('Importando archivos, espere por favor...');
            $.ajax({
                url: siteurl('report_commission/import_report_commission'),
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    ajaxStop();
                    $('#file_excel').val('');
                    if (data === '1') {
                        swal('Correcto', 'Se importo los datos correctamente!!', 'success');
                    } else if (data === '2') {
                        swal('Error', 'NO se pudo importar los datos, porque un producto no existe!!', 'error');
                    } else if (data === '3'){
                        swal('Error', 'NO se pudo importar los datos porque una sucursal comision no existe!!', 'error');
                    } else if (data === '0'){
                        swal('Error', 'NO se pudo importar los datos!!', 'error');
                    }
                }
            });
        });
    });

</script>
