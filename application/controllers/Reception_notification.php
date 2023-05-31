<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/7/2017
 * Time: 2:12 PM
 */
class Reception_notification extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reception_notification_model');
        $this->load->model('type_notification_model');
        $this->load->model('reception_model');
    }


    /*Mandar al index para cargar la lista de tipos de almacen*/
    public function index()
    {
        template('reception_notification/index');

    }

    /*Mandar los parametros al modelo para registrar el tipo de almacen*/
    public function register_reception_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reception_notification_model->register_reception_notification();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el tipo de almacen*/
    public function modify_reception_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reception_notification_model->modify_reception_notification();
        } else {
            show_404();
        }
    }

    /*Para eliminar un tipo de almacen seleccionado de la lista*/
    public function disable_reception_notification()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reception_notification_model->disable_reception_notification();
        } else {
            show_404();
        }
    }

    /*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

    public function get_reception_notification_enable()
    {
        if ($this->input->is_ajax_request()) {

            $reception_notification_list = $this->reception_notification_model->get_reception_notification_enable();
            echo json_encode($reception_notification_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de todas recepciones en el dataTable*/
    public function get_reception_notification_list()
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

            echo json_encode($this->reception_notification_model->get_reception_notification_list($params));
        } else {
            show_404();
        }
    }


    public function reception_specific()
    {
        if ($this->input->post()) {
            $id_reception = $this->input->post('id');


        $reception_for_new = $this->reception_model->get_reception_by_id($id_reception);
        $type_notification_for_new = $this->type_notification_model->get_type_notification_reception_enable();
        $data = array(
            'reception' => $id_reception,
            'type_notification_for_new' => $type_notification_for_new,
            'reception_for_new' => $reception_for_new
        );
        template('reception_notification/specific_reception', $data);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de todas recepciones en el dataTable*/
    public function get_reception_notification_specific_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $id_reception = $this->input->post('id_reception');
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'id_reception'=>$id_reception,
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->reception_notification_model->get_reception_notification_specific_list($params));
        } else {
            show_404();
        }
    }

}