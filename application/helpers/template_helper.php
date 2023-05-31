<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/03/2017
 * Time: 05:43 PM
 */


function template($view, $data = null)
{
    if (!logged()) {
        redirect(site_url('login'));
    }
    $ci = get_instance();
    $sesion['sesion'] = $ci->session->userdata('user');
    //$data_header['dato_sesion'] = $datosesion;

    $menu_items = $ci->session->userdata('menu');
    $ci->multi_menu->set_items($menu_items);

    $array_controladores = array();
    $index = 0;
    foreach ($menu_items as $row) {
        $array_controladores[$index] = $row['slug'];
        $index++;
    }

    /*if (!tieneAcceso($array_controladores)) {
        show_error('Usted no cuenta con los permisos necesarios.<br><br><a class="btn btn-danger" href="' . base_url() . 'inicio"> Volver</a> ', 'Error de acceso', '<b>Restriccion de Acceso</b>');
    }*/

    $ci->load->view('template/index', $sesion);
    $ci->load->view($view, $data);
    $ci->load->view('template/footer');
}

/*----------------------------------------------------
 * Este metodo es para restringir el acceso por url
 * --------------------------------------------------**/
function access($menu)
{
    $ci = get_instance();
    $controlador = $ci->uri->segment(1);
    //$metodo = $ci->uri->segment(2);

    // Verificamos si la se esta accediento a un metodo de un controlador de
    // vista para compara con los slug registrados
//    if ($metodo != '') {
//        $funcion = $controlador ;
//    } else {
    $funcion = $controlador;
//    }

    if ($funcion === 'Home') { // Si es inicio el controlador inicio con el metodo de cambio (que es metodo que llama a plantilla)
        return true;
    } else {
        if (in_array($funcion, $menu)) {
            return true;
        } else {
            return false;
        }
    }
}

function get_state_abm($value){
    $ci = &get_instance();
    $estados = $ci->config->item('estados_abm');
    return array_search($value, $estados);
}

/*Metodo global para recuperar el nombre de tipo de notificacion para regisrar tipo de notifacion*/
function get_type_notification($value){
    $ci = &get_instance();
    $tipo_notificacion = $ci->config->item('tipo_notificacion');
    return array_search($value, $tipo_notificacion);
}

function is_selected($value, $itemvalue)
{
    if ($value === $itemvalue) {
        return 'selected';
    } else {
        return '';
    }
}
function implode_array($object_array, $fieldname)
{
    $simple_array = array_object_to_simple_array($object_array, $fieldname);
    return implode(',', $simple_array);
}

function array_object_to_simple_array($object, $fieldname) {
    $simple_array = array();
    foreach ($object as $row)
    {
        array_push($simple_array, $row->$fieldname);
    }
    return $simple_array;
}

function get_file_path($relative_file_path)
{
    $ci = &get_instance();
    $file_url_path = $ci->config->item('file_url');
    $file_full_path = $file_url_path . $relative_file_path;

    // Comprobar si el archivo existe y retornar la url. Caso contrario, retornar el archivo de prueba
    if (file_exists($file_full_path)) {
        return $file_url_path . $relative_file_path;
    } else {
        return $file_url_path . '/vacia.jpg';
    }
}

/*
    0= Peritar (lista para ver el equipo y diagnosticar)
    1= Atendido (se hizo el diagnostico )
    2= En espera
    3= En proceso
    4= En mora
    5= Concluido
 * */
function get_work_order_states($state){
    switch ($state){
        case 0:
            // return 'POR PERITAR';
            return 'RECEPCIONADO';
            break;
        case 1:
            return 'REPARADO';
            break;
        case 2:
            return 'APROBADO';
            break;
        case 3:
            return 'EN PROCESO';
            break;
        case 4:
            return 'CONCLUIDO';
            break;
        case 5:
            return 'ENTREGADO';
            break;
        case 6:
            return 'EN MORA';
            break;
        case 7:
            return 'ESPERA STOCK';
            break;
        case 8:
            return 'ENTREGADO ESPERA STOCK';
            break;
        case 9:
            return 'NO APROBADO';
            break;
        case 10:
            return 'SIN SOLUCION';
            break;
    }
}

function get_priority_text($number){
    switch ($number){
        case 1:
            return 'BAJA';
            break;
        case 2:
            return 'MEDIA';
            break;
        case 3:
            return 'ALTA';
            break;
    }
}

/* Direccion Raiz para las recepciones */
//const DIRECTORY_RAIZ_PATH = 'C:\xampp\htdocs\canis-care\reception_gallery\\';
const DIRECTORY_RAIZ_PATH = '/var/www/canis.mobi/public_html/sistema/reception_gallery/';

/* Direccion Raiz para las productos */
//const DIRECTORY_RAIZ_PATH_PRODUCT = 'C:\xampp\htdocs\canis-care\product_gallery\\';
const DIRECTORY_RAIZ_PATH_PRODUCT = '/var/www/canis.mobi/public_html/sistema/product_gallery/';
  
// Variables para las imagenes
//$config['file_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/sistema/galeria_recepcion';
/*
    0= Peritar (lista para ver el equipo y diagnosticar)
    1= REPARADO (se hizo el diagnostico )
    2= En proceso
    3= En mora
    4= Concluido
 * */


const RECEPCIONADO = 0;              /* Cuando se crea la recepcion - PORCENTAJE: 0% */
const REPARADO = 1;  
const ENTREGADO = 5;

const POR_PERITAR = 0;              /* Cuando se crea la recepcion - PORCENTAJE: 0% *//* Cuando el tecnico realizo el diagnostico - PORCENTAJE: 50% */
const APROBADO = 2;                 /* Cuando el cliente da el visto bueno para poder realizar el trabajo - PORCENTAJE: 50%*/
const EN_PROCESO = 3;               /* El tecnico esta realizando el trabajo   PORCENTAJE: 80%*/
const CONCLUIDO = 4;                /* El trabajo se acepto y terminado , pero no fue entregado al cliente - PORCENTAJE: 100%*/
const EN_MORA = 6;                  /* Se pospuso el trabajo por algun motivo - PORCENTAJE: 50%*/
const ESPERA_STOCK = 7;             /* Cuando no hay un repuesto despues de que el cliente aprobo - PORCENTAJE: 50%*/
const ENTREGADO_ESPERA_STOCK = 8;   /* Cuando el cliente recoge el equipo para traerlo posteriormente cuando ya haya stock - PORCENTAJE: 50%*/
const NO_APROBADO = 9;              /* Cuando no aceptan el diagnostico, y devuelven el equipo al cliente - PORCENTAJE: 100%*/
const SIN_SOLUCION = 10;            /* Cuando no aceptan el diagnostico, y devuelven el equipo al cliente - PORCENTAJE: 100%*/



const ELIMINADO = 2;
const ACTIVO = 1;
const ANULADO = 0;

const CANTIDAD_MONTO_DECIMAL = 2;

const POR_VERIFICAR = 2;
const CON_GARANTIA = 1;
const SIN_GARANTIA = 0;

/*
    1: Mayusculas
    2: Minusculas
    3: Sin formato, tal cual como llega
*/
const TYPE_TEXT_FORMAT = 1;

/* TIPO DE MENU DE CAMBIOS DE ESTADOS */
const TYPE_STATES_RECEPTION = 0;
const TYPE_STATES_SERVICE = 1;

/* TIPO DE MOTIVO */
const TIPO_MOTIVO_PRODUCTO = 0;
const TIPO_MOTIVO_RECEPCION = 1;
const TIPO_MOTIVO_TRANSITO = 2;


/* ESTADOS TRANSITO */
const POR_APROBAR = 3;
const DEVUELTO = 2;
const PRESTADO = 1;


// ESTADO DE CIERRE: EGRESO DE CAJA
const CERRADO = 0;
const HABILITADO = 1;