/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 01/04/2019
 * Time: 04:40 PM
 */

$(document).ready(function () {
    enable_webcam_product();
    images_view_product();
});

$(document).ready(function () {

    $("#file_images_product").on('change', function () {
        $("#btn_submit_galery").click();
    });

    $('#frm_select_image').on('submit', function(e){
        e.preventDefault();
        ajaxStart('subiendo archivos...');
        var form_data = new FormData(this);
        console.log(form_data);
        $.ajax({
            url: siteurl('product/add_gallery_product'),
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
                images_view_product();

            },
            error:function (response) {
                ajaxStop();
                console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
            }
        });
    });

});


function exists_support_user_media() {
    return !!(navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia)
}

function exists_browser_permissions_user_media() {
    return (navigator.getUserMedia || (navigator.mozGetUserMedia || navigator.mediaDevices.getUserMedia) || navigator.webkitGetUserMedia || navigator.msGetUserMedia).apply(navigator, arguments);
}

function enable_webcam_product() {

    var $video_product = document.getElementById("video_product"),
        $canvas_product = document.getElementById("canvas_product"),
        $btn_take_photo = document.getElementById("btn_take_photo")

    if (exists_support_user_media()) {
        exists_browser_permissions_user_media(
            {video: true},
            function (stream) {
                console.log("PERMISO CONCEDIDO PARA USAR CAMARA WEB EN PRODUCTOS");

                $video_product.srcObject = stream;
                $video_product.play();

                $btn_take_photo.addEventListener("click", function () {
                    // Pausar reproducción
                    $video_product.pause();

                    // Obtener contexto del canvas y dibujar sobre él
                    var contexto = $canvas_product.getContext("2d");
                    $canvas_product.width = $video_product.videoWidth;
                    $canvas_product.height = $video_product.videoHeight;
                    contexto.drawImage($video_product, 0, 0, $canvas_product.width, $canvas_product.height);

                    // Imagen tomada en base64
                    var image = $canvas_product.toDataURL();

                    ajaxStart('Guardando fotografia, por favor espere...');
                    $.ajax({
                        url: siteurl('product/take_product_picture_from_webcam'),
                        data: {
                            product_id: $("#id_product").val(),
                            codigo: $("#codigo").val(),
                            image: encodeURIComponent(image)
                        },
                        type: 'post',
                        success: function (data) {
                            ajaxStop();
                            images_view_product();
                        }
                    });

                    $video_product.play();
                    images_view_product();
                });

            }, function (error) {
                console.log("PERMISO DENEGADO O ERROR: ", error);
            });
    } else {
        alert("Lo siento. Tu navegador no soporta esta característica");
    }
}

function images_view_product()
{
    $.ajax({
        url: siteurl('product/get_images_product'),
        type: "POST",
        data: {
            product_id: $("#id_product").val()
        },
        success: function(response)
        {
            if(response == '1'){
                console.log("DATOS CORRECTOS");
            }else{
                console.log("DEBE SELECCIONAR ALGUNA IMAGEN");
            }

            document.getElementById("image_preview_product").innerHTML = response;
        },
        error:function (response) {
            console.log("ERROR INTERNO, CONTACTE CON EL ADMINISTRADOR");
        }
    });
}

function delete_product_photo(index) {
    ajaxStart('Eliminando registro...');
    $.ajax({
        url: siteurl('product/delete_image_product'),
        type: "POST",
        data: {
            image_id: index
        },
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
    });
}
