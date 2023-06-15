/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 05/11/2018
 * Time: 11:57 AM
 */

$(document).ready(function () {

    /*cargamos el metodo los almacenes apartir de la sucursal selecciona*/
    if(typeof cash_id !== 'undefined' && typeof cash_aperture_id !== 'undefined'){
        if(cash_id==false || cash_aperture_id==0){
            console.log(cash_id);
            console.log(cash_aperture_id);
            // alert('CAJA NO APERTURADA');
            // swal({
            //     title: "Caja No aperturada",
            //     text: "La caja  no fue aperturada",
            //     type: "warning",
            //     showCancelButton: false,
            //     confirmButtonColor: "#DD6B55",
            //     confirmButtonText: "Ok!",
            //     closeOnConfirm: true,
            //     closeOnCancel: true
            // },
            // function (isConfirm) {
            //     // $.redirect(siteurl("home"));
            // });
        }
    }

    /* Evento para seleccionar caja y si tiene permiso para cerrar caja */
    $('#select_cash_and_permission_close_cash').click(function () {
        // event.preventDefault();
        swal({
                title: "Esta seguro que desea Cerrar Caja?",
                text: "La caja cerrara y dejara de hacer ventas o otras transacciones de cajas",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, Cerrar Caja!",
                cancelButtonText: "No, cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                   select_cash_and_permission_close_cash();
                }
            });

        // select_cash_and_permission_close_cash();
    });

    /* Evento para cerrar caja */
    $('#btn_close_cash').click(function (event) {

        ajaxStart('Cerrando caja...');
        $.ajax({
            url: siteurl('cash/get_permission_to_close_cash'),
            dataType: 'json',
            success: function (response) {
                ajaxStop();
                
                if (response.success == true){
                    ajaxStart('Cerrando caja y saliendo del sistema... ');
                    $.ajax({
                        url: site_url + 'cash/close_cash',
                        type: 'post',
                        data: {
                            'close_cash_aperture_id': $('#close_cash_aperture_id').val(),
                            'close_date_close': $('#close_date_close').val(),
                            'cash_total_bs': $('#cash_total_bs').val(),
                            'cash_total_sus': $('#cash_total_sus').val(),
                            'cash_income_total_venta': $('#cash_income_total_venta').val(),
                            'cash_income_total_bs': $('#cash_income_total_bs').val(),
                            'cash_income_total_sus': $('#cash_income_total_sus').val(),
                            'cash_income_total_tarjeta': $('#cash_income_total_tarjeta').val(),
                            'cash_income_total_cheque': $('#cash_income_total_cheque').val(),
                            'cash_income_total_cambio': $('#cash_income_total_cambio').val(),
                            'cash_output_total_bs': $('#cash_output_total_bs').val(),
                            'cash_output_total_sus': $('#cash_output_total_sus').val(),
                            'cash_total_efective': $('#cash_total_efective').val(),
                        },
                        dataType: 'json',
                        success: function (response) {
                            ajaxStop();
                            if (response.success === true) {

                                setTimeout(function () {
                                    // location.href = site_url + "login/sign_out";
                                    location.href = site_url + "home";
                                }, 500);

                            } else if (response.login === true) {
                                login_session();
                                setTimeout(function () {
                                    location.href = site_url + "home";
                                }, 8000);
                            }
                        },
                        error: function (error) {
                            ajaxStop();
                            console.log(error.responseText);
                            // **alert('error; ' + eval(error));**
                            swal('Error', 'Error al registrar los datos.', 'error');
                        }
                    });
                } else{
                    swal('ACCESO NO AUTORIZADO', 'No tienes permiso para cerrar caja.', 'error');
                }
            }
        });

    });

    /* Evento para cerrar caja */
    $('#btn_generate_pdf').click(function (event) {

        var url = siteurl('cash/print_close_cash');
        $.redirect(url, {
            close_date_close:$('#close_date_close').val(),
            close_date_open:$('#close_date_open').val(),
            close_branch_office_id:$('#close_branch_office_id').val(),
            close_cash_id: $('#close_cash_id').val(),
            close_cash_aperture_id: $('#close_cash_aperture_id').val(),
            cash_total_bs: $('#cash_total_bs').val(),
            cash_total_sus: $('#cash_total_sus').val(),
            cash_income_total_venta: $('#cash_income_total_venta').val(),
            cash_income_total_bs: $('#cash_income_total_bs').val(),
            cash_income_total_sus: $('#cash_income_total_sus').val(),
            cash_income_total_tarjeta: $('#cash_income_total_tarjeta').val(),
            cash_income_total_cheque: $('#cash_income_total_cheque').val(),
            cash_income_total_cambio: $('#cash_income_total_cambio').val(),
            cash_output_total_bs: $('#cash_output_total_bs').val(),
            cash_output_total_sus: $('#cash_output_total_sus').val(),
            cash_total_efective: $('#cash_total_efective').val(),
            close_user_id:$('#close_user_id').val(),
            valor: 'CERRAR'
            }
            , 'POST', '_blank');
    });

    $('#frm_select_cash_close').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        ajaxStart('Verificando...');
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(form).serialize(),
            dataType: 'json',
            success: function (response) {
                ajaxStop();

                if (response.login == true) {
                    login_session();
                } else if (response.check == true) {
                    if (response.login != true) {
                        swal('Caja Aperturada', 'La caja seleccionada se encuentra aperturada.', 'success');
                        $('#close_modal_select_cash_close').click();
                        select_cash_and_permission_close_cash();
                    }

                } else {
                    swal('No se puede cerrar la caja', 'La caja seleccionada ya esta cerrada.', 'info');
                    $('#close_modal_select_cash_close').click();
                }

            }
        });
    });


});

/* Cierra la caja de ventas */
function select_cash_and_permission_close_cash() {
    ajaxStart('Verificando caja y permiso para cerrar');
    $.ajax({
        url: site_url + 'cash/select_cash_and_permission_close_cash',
        type: 'post',
        dataType: 'json',
        async: false,
        success: function (response) {
            ajaxStop();

            var selected_cash = response.selected_cash;
            var login = response.login;
            var state_cash = response.state_cash;
            var permission_close_cash = response.permission_close_cash;

            if (selected_cash == true) {

                if (state_cash === true) {

                    swal('No se puede cerrar la caja', 'La caja seleccionada ya esta cerrada.', 'info');

                } else {
                    $.redirect(site_url + 'cash/show_closing_cash', {
                        verificacion: permission_close_cash
                    }, 'POST', '_self');
                }


            } else if (login == true) {
                $('#modal_close_session').modal('hide');
                login_session();
            } else {
                swal.close()
                $('#modal_close_session').modal('hide');
                // select_cash();
            }
        }
    });
}

function select_cash() {
    $.ajax({
        url: site_url + 'cash/get_cash_enable',
        type: 'post',
        dataType: 'json',
        async: false,
        success: function (response) {
            var data = JSON.parse(response);
            var login = data.login;
            var login_cash = data.login_cash;
            var check = data.check;
            var box_enable = data.box_enable;

            if (login == true) {
                login_session();
            } else if (box_enable != true) {
                if (login_cash != true) {

                    if (check == true) {
                        var result = data.result;
                        $.each(result, function (i, item) {

                            $('#select_cash_close').empty();
                            $.each(result, function (i, item) {
                                $('#select_cash_close').append('<option value="' + item.id + '">' + item.descripcion + '</option>');
                            });
                        });

                        $('#modal_select_cash_close').modal({
                            show: true,
                            backdrop: 'static'
                        });

                    } else {
                        swal('Caja', 'No existen ninguna cajas habilitadas para esta sucursal.', 'info');
                    }
                }
            }


        }
    });
}