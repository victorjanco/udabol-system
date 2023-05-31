<?php
/**
 *
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato
 * Date: 21/10/2019
 * Time: 15:08 PM
 */

?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="panel-heading cabecera_frm ">
            <div style="">
                <h2 class="panel-title titulo_frm">IMPORTAR PRODUCTOS EXCEL</h2>
            </div>
        </div>
        <form enctype="multipart/form-data" id="frm_import_products" method="post">

            <div class="panel">
                <div class="panel-body">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="alert alert-success alert-dismissible" style="font-size: 12pt">
                                    <h4><i class="icon fa fa-info"></i> AVISO IMPORTANTE!!</h4>
                                    Asegurarse que que el archivo .excel tenga las siguientes condiciones: <br>
                                    * Seleccione un almacen. <br>
                                    * Los registros tienen que estar en letras mayusculas. <br>
                                    * Los registros NO tienen que estar con tildes. <br>
                                    * Asegurarse que no haya error ortográfico. <br>
                                    * Evitar en usar letras o caracteres especiales.
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<label>Almacen:</label>
										<div class="form-line">
											<select style="width: 100%" id="warehouse" name="warehouse"
													class="form-control select2">
												<option value="0">Todos</option>
												<?php foreach ($list_warehouse as $warehouse_list) : ?>
													<option value="<?= $warehouse_list->id; ?>"><?= $warehouse_list->nombre; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
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
                                <a href="<?= site_url('product/index') ?>" class="btn btn-danger waves-effect"
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

        $('#frm_import_products').on('submit', function (event) {
            event.preventDefault();
            ajaxStart('Importando archivos, espere por favor...');
            $.ajax({
                url: siteurl('product/save_import_products_warehouse'),
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
                    } else if (data == '2') {
                        swal('Validación Marca', 'Uno de los campos de Marca se encuentra vacío.', 'warning');
                    } else if (data == '3') {
                        swal('Validación Modelo', 'Uno de los campos de Modelo se encuentra vacío.', 'warning');
                    } else if (data == '4') {
                        swal('Validación Grupo', 'Uno de los campos de Grupo se encuentra vacío.', 'warning');
                    } else if (data == '5') {
                        swal('Validación Subgrupo', 'Uno de los campos de Subgrupo se encuentra vacío.', 'warning');
                    } else if (data == '6') {
                        swal('Validación Unidad Medida', 'Uno de los campos de Unidad Medida se encuentra vacío.', 'warning');
                    } else {
                        swal('Error', 'NO se pudo importar los datos!!', 'error');
                    }
                }
            });
        });
    });

</script>
