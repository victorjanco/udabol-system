<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 20/7/2017
 * Time: 7:00 PM
 */
class Warehouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_warehouse_model');
        $this->load->model('warehouse_model');
        $this->load->model('office_model');
    }

    /*Mandar al index para cargar la lista de tipos de almacen*/
    public function index()
    {

        $offices_for_new = $this->office_model->get_offices();
        if ($offices_for_new) {
            $data = array(
                'offices_for_new' => $offices_for_new
            );
            template('warehouse/index',$data);

        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para registrar el tipo de almacen*/
    public function register_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->warehouse_model->register_warehouse();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el tipo de almacen*/
    public function modify_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->warehouse_model->modify_warehouse();
        } else {
            show_404();
        }
    }

    /*Para eliminar un tipo de almacen seleccionado de la lista*/
    public function disable_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->warehouse_model->disable_warehouse();
        } else {
            show_404();
        }
    }
    /*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

    public function get_warehouse_enable()
    {
        if ($this->input->is_ajax_request()) {

            $warehouse_list=$this->warehouse_model->get_warehouse_enable();
            echo json_encode($warehouse_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_warehouse_list()
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

            echo json_encode($this->warehouse_model->get_warehouse_list($params));
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para registrar el tipo de almacen*/
    public function register_type_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_warehouse_model->register_type_warehouse();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el tipo de almacen*/
    public function modify_type_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_warehouse_model->modify_type_warehouse();
        } else {
            show_404();
        }
    }

    /*Para eliminar un tipo de almacen seleccionado de la lista*/
    public function disable_type_warehouse()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_warehouse_model->disable_type_warehouse();
        } else {
            show_404();
        }
    }

    /*Obtener todas los tipos de almacen activos especial para cargar combos o autocompletados*/
    public function get_type_warehouse_enable()
    {
        if ($this->input->is_ajax_request()) {

            $type_warehouse_list=$this->type_warehouse_model->get_type_warehouse_enable();
            echo json_encode($type_warehouse_list);
        } else {
            show_404();
        }
    }
    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_type_warehouse_list()
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

            echo json_encode($this->type_warehouse_model->get_type_warehouse_list($params));
        } else {
            show_404();
        }
    }

    function get_warehouse_by_branch_office_id()
    {
        if ($this->input->is_ajax_request()) {
            $warehouse_list=$this->warehouse_model->get_warehouse_by_branch_office_id();
            echo json_encode($warehouse_list);
        } else {
            show_404();
        }
    }
}