<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 24/08/2017
 * Time: 06:36 PM
 */
//Devuelve el id del usuario sesionado

function get_current_average_cost($warehouse_id,$product_id)
{
	$CI =& get_instance();
	$CI->load->model('product_model');
	$product = $CI->product_model->get_current_average_cost($warehouse_id,$product_id);
	return $product;

}
function get_profile($user_id)
{
    $CI =& get_instance();
    $CI->load->model('user_model');
    $user = $CI->user_model->get_user_id($user_id);
    return $user;

}

function get_number_printer_by_branch_office_id($branch_office_id)
{
	$CI =& get_instance();
	$CI->load->model('dosage_model');
	return $CI->dosage_model->get_number_printer_by_branch_office_id($branch_office_id);
}

function get_user_id_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['id_user'];
}

function get_user_type_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['type_user'];
}

//Devuelve el id de la sucursal en sesion
function get_branch_id_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    // return $sesion['id_branch_office'];
    return isset($sesion['id_branch_office'])? $sesion['id_branch_office']:0;
}

//Devuelve el nombre de la sucursal en sesion
function get_branch_office_name_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['name_branch_office'];
}

//Devuelve el nombre de la sucursal en sesion
function get_branch_office_nit_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['nit_branch_office'];
}

//Devuelve el nombre del usuario en sesion
function get_printer_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('dosage');
    return $sesion['id_printer'];
}
//Devuelve el nombre del usuario en sesion
function get_user_name_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['name_user'];
}

//Devuelve el id de la sesion guardada en sesion
function get_session_id()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('user');
    return $sesion['session_id'];
}

function get_reception_id_in_session()
{
    $ci =& get_instance();
    $sesion = $ci->session->userdata('reception');
    return $sesion['reception_id'];
}

function logged()
{
    $ci =& get_instance();
    $logueado = $ci->session->userdata('logged');
    return $logueado;
}


function verify_session()
{
    if (logged()) {
        return true;
    } else {
        return false;
    }
}
function verify_cash_session()
{
    $CI =& get_instance();
	$CI->load->model('cash_aperture_model');
    return $CI->cash_aperture_model->verify_cash_session(get_session_cash_aperture_id());
    // if ($cash_aperture) {
    //     return true;
    // } else {
    //     return false;
    // }
}

function get_month($valor)
{
    $result = '';
    switch ($valor) {
        case '01':
            $result = 'Enero';
            break;
        case '02':
            $result = 'Febrero';
            break;
        case '03':
            $result = 'Marzo';
            break;
        case '04':
            $result = 'Abril';
            break;
        case '05':
            $result = 'Mayo';
            break;
        case '06':
            $result = 'Junio';
            break;
        case '07':
            $result = 'Julio';
            break;
        case '08':
            $result = 'Agosto';
            break;
        case '09':
            $result = 'Septiembre';
            break;
        case '10':
            $result = 'Octubre';
            break;
        case '11':
            $result = 'Noviembre';
            break;
        case '12':
            $result = 'Diciembre';
            break;
    }
    return $result;
}

function text_format($value)
{
    switch (TYPE_TEXT_FORMAT) {
        case 1:/**/
            $result = strtoupper($value);
            break;
        case 2:
            $result = strtolower($value);
            break;
        case 3:
            $result = $value;
            break;
    }
    return $result;
}

/* RUTA DEL PROYECTO */
const DIRECTORY_RAIZ_PATH_BARCODE = 'C:\laragon\www\lastlevel\barcodes\\';
// const DIRECTORY_RAIZ_PATH_PRODUCT = '/var/www/workcorp.net/public_html/m_y_k/barcodes/';


/* Devuelve nombre de la caja en sesion */
function get_session_cash_name(){
    $ci =& get_instance();
    $sesion = $ci->session->userdata('cash_session');
    return isset($sesion['name_cash'])? $sesion['name_cash']:'';
}
/* Devuelve id de la caja en sesion */
function get_session_cash_id(){
    $ci =& get_instance();
    $sesion = $ci->session->userdata('cash_session');
    return isset($sesion['cash_id'])? $sesion['cash_id']:false;
}   
/* Devuelve id de apertura_caja en sesion */
function get_session_cash_aperture_id(){
    $ci =& get_instance();
    $sesion = $ci->session->userdata('cash_session');
    return isset($sesion['cash_aperture_id'])? $sesion['cash_aperture_id']:false;
} 