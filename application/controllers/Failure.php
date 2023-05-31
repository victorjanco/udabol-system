<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 17/7/2017
 * Time: 6:15 PM
 */
class Failure extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('failure_model');
        $this->load->model('group_failure_solution_model');

    }

    /*Mandar al index para cargar la lista de falla*/
    public function index()
    {
        $group_for_new = $this->group_failure_solution_model->get_group_failure_solution_enable();
        $data = array(
            'group_for_new' => $group_for_new
        );
        template('failure/index',$data);
    }

    /*Para registrar la nueva falla*/
    public function register_failure()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->failure_model->register_failure();
        } else {
            show_404();
        }
    }

    /*Para modificar una falla*/
    public function modify_failure()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->failure_model->modify_failure();
        } else {
            show_404();
        }
    }

    /*Para eliminar una marca seleccionado de la lista*/
    public function disable_failure()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->failure_model->disable_failure();
        } else {
            show_404();
        }
    }

    /*Para eliminar un servicio seleccionado de la lista*/
    public function enable_failure()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->failure_model->enable_failure();
        } else {
            show_404();
        }
    }

    /*Obtener todos las fallas activas especial para cargar combos o autocompletados*/
    public function get_failure_enable()
    {
        if ($this->input->is_ajax_request()) {

            $failure_list=$this->failure_model->get_failure_enable();
            echo json_encode($failure_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de marcas en el dataTable*/
    public function get_failure_list()
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

            echo json_encode($this->failure_model->get_failure_list($params));
        } else {
            show_404();
        }
    }

    public function get_failure_reception()
    {
        if ($this->input->is_ajax_request()) {
            $failure_list=$this->failure_model->get_failure_reception();
            echo json_encode($failure_list);
        } else {
            show_404();
        }
    }

    public function get_failure_reception_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('id');
            $failure_list=$this->failure_model->get_failure_reception_by_reception_id($reception_id);
            echo json_encode($failure_list);
        } else {
            show_404();
        }
    }

    public function get_failure_order_work_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $order_work_id = $this->input->post('id');
            $order_work_list=$this->failure_model->get_failure_order_work_by_order_work_id($order_work_id);
            echo json_encode($order_work_list);
        } else {
            show_404();
        }
    }
}