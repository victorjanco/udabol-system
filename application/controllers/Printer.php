<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 27/07/2017
 * Time: 04:55 PM
 */
class Printer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('printer_model');
        $this->load->model('office_model');
    }

    public function get_pinters(){
        echo json_encode($this->printer_model->get_printers());
    }

    public function index()
    {
        $offices_for_new = $this->office_model->get_offices();
        if ($offices_for_new) {
            $data = array(
                'branch_office_for_new' => $offices_for_new
            );
            template('printer/index',$data);

        } else {
            show_404();
        }
    }

    public function get_printer_list()
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
        echo json_encode($this->printer_model->get_printer_list($params));
    }

    public function get_printers_by_branch_id()
    {
        if ($this->input->is_ajax_request()) {
            $branch_id = $this->input->post('id');
            echo json_encode($this->printer_model->get_printers_by_branch_id($branch_id));
        } else {
            show_404();
        }
    }

    public function register_printer(){
        if($this->input->is_ajax_request()){
            echo json_encode($this->printer_model->register_printer());
        }else{
            show_404();
        }
    }

    /*Modificar Grupo*/
    public function modify_printer()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->printer_model->modify_printer();
        } else {
            show_404();
        }
    }

    /*Para eliminar el seleccionado de la lista*/
    public function disable()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->category_model->disable();
        } else {
            show_404();
        }
    }

}