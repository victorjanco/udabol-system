<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 28/7/2017
 * Time: 2:07 PM
 */
class Type_notification extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_notification_model');
    }

    /*Mandar los parametros al modelo para registrar el tipo de almacen*/
    public function register_type_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_notification_model->register_type_notification();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el tipo de almacen*/
    public function modify_type_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_notification_model->modify_type_notification();
        } else {
            show_404();
        }
    }

    /*Para eliminar un tipo de almacen seleccionado de la lista*/
    public function disable_type_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_notification_model->disable_type_notification();
        } else {
            show_404();
        }
    }

    /*Obtener todas los tipos de almacen activos especial para cargar combos o autocompletados*/
    public function get_type_notification_work_order_enable()
    {
        if ($this->input->is_ajax_request()) {

            $type_notification_list=$this->type_notification_model->get_type_notification_work_order_enable();
            echo json_encode($type_notification_list);
        } else {
            show_404();
        }
    }
    /*Obtener todas los tipos de almacen activos especial para cargar combos o autocompletados*/
    public function get_type_notification_reception_enable()
    {
        if ($this->input->is_ajax_request()) {

            $type_notification_list=$this->type_notification_model->get_type_notification_reception_enable();
            echo json_encode($type_notification_list);
        } else {
            show_404();
        }
    }
    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_type_notification_reception_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->type_notification_model->get_type_notification_reception_list($params));
        } else {
            show_404();
        }
    }
}