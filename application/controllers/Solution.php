<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 13/7/2017
 * Time: 6:15 PM
 */
class Solution extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('solution_model');
        $this->load->model('group_failure_solution_model');
    }

    /*Mandar al index para cargar la lista de soluciones*/
    public function index()
    {
        $group_for_new = $this->group_failure_solution_model->get_group_failure_solution_enable();
        $data = array(
            'group_for_new' => $group_for_new
        );
        template('solution/index',$data);
    }

    /*Para registrar la nueva solucion*/
    public function register_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->solution_model->register_solution();
        } else {
            show_404();
        }
    }

    /*Para modificar una solucion*/
    public function modify_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->solution_model->modify_solution();
        } else {
            show_404();
        }
    }

    /*Para eliminar una solucion seleccionado de la lista*/
    public function disable_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->solution_model->disable_solution();
        } else {
            show_404();
        }
    }
    /*Para eliminar un servicio seleccionado de la lista*/
    public function enable_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->solution_model->enable_solution();
        } else {
            show_404();
        }
    }

    /*Obtener todas las soluciones activas especial para cargar combos o autocompletados*/
    public function get_solution_enable()
    {
        if ($this->input->is_ajax_request()) {
            $soltion_list=$this->solution_model->get_solution_enable();
            echo json_encode($soltion_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de clientes en el dataTable*/
    public function get_solution_list()
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

            echo json_encode($this->solution_model->get_solution_list($params));
        } else {
            show_404();
        }
    }

    public function get_solution_reception()
    {
        if ($this->input->is_ajax_request()) {
            $solution_list=$this->solution_model->get_solution_reception();
            echo json_encode($solution_list);
        } else {
            show_404();
        }
    }

    public function get_solution_reception_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $reception_id = $this->input->post('id');
            $failure_list=$this->solution_model->get_solution_reception_by_reception_id($reception_id);
            echo json_encode($failure_list);
        } else {
            show_404();
        }
    }

    public function get_solution_order_work_by_id()
    {
        if ($this->input->is_ajax_request()) {
            $order_work_id = $this->input->post('id');
            $order_work_list=$this->solution_model->get_solution_order_work_by_order_work_id($order_work_id);
            echo json_encode($order_work_list);
        } else {
            show_404();
        }
    }

}