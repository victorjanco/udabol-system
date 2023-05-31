<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 27/07/2017
 * Time: 04:03 PM
 */
class Group extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model');
    }

    public function index()
    {
        template('group/index');
    }

    public function get_groups(){
//        if($this->input->is_ajax_request()){

        echo json_encode($this->group_model->get_groups());
//        }else{
//            show_404();
//        }
    }

    public function get_groups_list()
    {
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
        echo json_encode($this->group_model->get_groups_list($params));
    }

    public function get_subgroups_list()
    {
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
        echo json_encode($this->group_model->get_subgroups_list($params));
    }

    public function sub_group(){
        $data["group"] = $this->group_model->_getgroup_by_id($this->input->post('id'));
        template('group/subgroup',$data);
    }

    public function register_group(){
        if($this->input->is_ajax_request()){
            echo json_encode($this->group_model->register_group());
        }else{
            show_404();
        }
    }

    /*Para eliminar un marca seleccionado de la lista*/
    public function disable()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->group_model->disable();
        } else {
            show_404();
        }
    }

    /*Modificar Grupo*/
    public function modify()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->group_model->modify();
        } else {
            show_404();
        }
    }

    public function register_subgroup(){
        if($this->input->is_ajax_request()){
            echo json_encode($this->group_model->register_subgroup());
        }else{
            show_404();
        }
    }

    public function get_subgroups(){
        if($this->input->is_ajax_request()){
            echo json_encode($this->group_model->get_subgroups($this->input->post('group_id')));
        }else{
            show_404();
        }
    }
}