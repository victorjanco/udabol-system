<?php

class Reference extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reference_model');
    }

    public function get_reference_list()/* Lista index*/
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
        echo json_encode($this->reference_model->get_reference_list($params));
    }

    /*Mandar al index para cargar la lista de soluciones*/
    public function index()
    {
        template('reference/index');
    }

    /*Para registrar la nueva solucion*/
    public function register_reference()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reference_model->register_reference();
        } else {
            show_404();
        }
    }


    public function modify()/*Para modificar una referencia*/
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reference_model->modify();
        } else {
            show_404();
        }
    }


    public function disable()/*Para eliminar una referencia seleccionado de la lista*/
    {
        if ($this->input->is_ajax_request()) {
            echo $this->reference_model->disable();
        } else {
            show_404();
        }
    }

    /*Obtener todas las soluciones activas especial para cargar combos o autocompletados*/
    public function get_references_enable()
    {
        if ($this->input->is_ajax_request()) {
            $reference_list = $this->reference_model->get_references_enable();
            echo json_encode($reference_list);
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

}