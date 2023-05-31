<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 28/8/2017
 * Time: 5:28 PM
 */
class Group_failure_solution extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_failure_solution_model');
    }


    /*Mandar al index para cargar la lista de marcas*/
    public function index()
    {
        template('group_failure_solution/index');
    }

    /*Para registrar la nueva marca*/
    public function register_group_failure_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->group_failure_solution_model->register_group_failure_solution();
        } else {
            show_404();
        }
    }

    /*Para modificar una marca*/
    public function modify_group_failure_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->group_failure_solution_model->modify_group_failure_solution();
        } else {
            show_404();
        }
    }

    /*Para eliminar un marca seleccionado de la lista*/
    public function disable_group_failure_solution()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->group_failure_solution_model->disable_group_failure_solution();
        } else {
            show_404();
        }
    }

    /*Obtener todas las marcas activos especial para cargar combos o autocompletados*/
    public function get_group_failure_solution_enable()
    {
        if ($this->input->is_ajax_request()) {

            $group_failure_solution_list=$this->group_failure_solutionmodel->get_group_failure_solution_enable();
            echo json_encode($group_failure_solution_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de marcas en el dataTable*/
    public function get_group_failure_solution_list()
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

            echo json_encode($this->group_failure_solution_model->get_group_failure_solution_list($params));
        } else {
            show_404();
        }
    }

}