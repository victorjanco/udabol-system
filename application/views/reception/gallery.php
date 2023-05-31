<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 31/07/2017
 * Time: 03:11 PM
 */


?>

<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="panel-heading cabecera_frm">
                <h4 class="panel-title titulo_frm">Galeria de Imagenes de Recepcion</h4>
            </div>

            <div class="body">
                <form action="<?= site_url('reception/registrar_multiple') ?>" id="uploadForm">
                    <fieldset>
                        <legend align="left">Datos del Cliente &nbsp;</legend>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="id_reception" name="id_reception"
                                               value="<?= isset($reception) ? $reception->id : '' ?>" hidden>
                                        <input type="text" id="reception_code" name="reception_code"
                                               value="<?= isset($reception) ? $reception->codigo_recepcion : '' ?>" hidden>
                                        <input type="text" class="form-control" id="" name=""
                                               value="<?= isset($reception) ? $reception->ci : '' ?>"
                                               placeholder="Busque por CI" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                               value="<?= isset($reception) ? $reception->nombre : '' ?>" disabled
                                               placeholder="Busque por nombre de cliente">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="" name=""
                                               value="<?= isset($reception) ? $reception->nombre_comercial : '' ?>"
                                               disabled
                                               placeholder="Telefono principal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row clearfix">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-success">
                                        <div class="panel-heading with-border">
                                            <h2 class="panel-title"><i class="fa fa-camera"></i> Tomar Fotografia
                                            </h2>
                                        </div>
                                        <center>
                                            <video id="video" style="height: max-content; width: max-content"></video>
                                            <br>
                                            <button id="boton" style="align-content: center">Tomar foto</button>
                                        </center>
                                            <canvas id="canvas" style="display: none;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-success">
                                        <div class="panel-heading with-border">
                                            <h2 class="panel-title"><i class="fa fa-file-picture-o"></i> Seleccionar Imagenes
                                            </h2>
                                        </div>
                                        <div class="panel-body">
                                            <p>Puede agregar imágenes que serán visualizadas en forma de galería.</p>
                                            <p>Para seleccionar multiples imagenes, presione ctrl + click. Posteriormente,
                                                click derecho
                                                sobre una
                                                imagen y selecciones "Select all"</p>
                                            <input name="files[]" type="file" multiple id="file_images" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <center>
                        <h4>IMAGENES DE LA RECEPCION GUARDADAS EN SISTEMA</h4>
                    </center>
                    <div class="row" id="image_preview"></div>

                    <div class="modal-footer">
                        <a id="print_order_work"  class="btn btn-default"><i class="material-icons">print</i><span class="icon-name">Imprimir orden</span></a>
                        <div hidden>
                            <button class="btn btn-success waves-effect" type="submit" id="btn_submit_galery"><i
                                        class="material-icons">save</i><span class="icon-name">Guardar Galeria</span>
                            </button>
                        </div>
                        <a href="<?= site_url('reception') ?>" class="btn btn-danger waves-effect" type="submit"><i
                                    class="material-icons">cancel</i><span class="icon-name">Cancelar y
                                Salir</span></a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<script src="<?= base_url('jsystem/reception.js') ?>"></script>
<script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
<script>
    $(document).ready(function () {
        $("#file_images").on('change', function () {
            $("#btn_submit_galery").click();
            $(this).val('');
        });
        $('#uploadForm').on('submit', function(e){
            e.preventDefault();
            ajaxStart('subiendo archivos...')
            var form_data = new FormData(this);
            var aaa = new FormData(this);
            console.log(form_data);
            $.ajax({
                url: siteurl('reception/add_gallery'),
                type: "POST",
                data: form_data,
                contentType: false,
                processData:false,
                success: function(response)
                {
                    ajaxStop();
                    console.log(response);
                    if(response == '1'){
                        console.log("DATOS CORRECTOS");
                    }else{
                        console.log("DEBE SELECCIONAR ALGUNA IMAGEN");
                    }
                    images_view();

                },
                error:function (response) {
                    ajaxStop();
                    console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
                }
            });
        });
        images_view();
    });

    function images_view()
    {
        $.ajax({
            url: siteurl('reception/get_reception_images'),
            type: "POST",
            data: {reception_id: $("#id_reception").val()},
            success: function(response)
            {
                console.log(response);
                if(response == '1'){
                    console.log("DATOS CORRECTOS");
                }else{
                    console.log("DEBE SELECCIONAR ALGUNA IMAGEN");
                }
                //$('#image_preview').append(response);
                document.getElementById("image_preview").innerHTML = response;
            },
            error:function (response) {
                console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
            }
        })
    }


    function delete_div(index) {
       ajaxStart('Eliminando registro...');
        $.ajax({
            url: siteurl('reception/delete_image'),
            type: "POST",
            data: {image_id: index},
            success: function(response)
            {
                console.log(response);
                $("#div_"+index).remove();
                ajaxStop();
            },
            error:function (response) {
                console.log(response);
                console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
                ajaxStop();
            }
        })
    }
</script>