<?php

/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 07/08/2017
 * Time: 12:23
 */
class Provider extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('provider_model');
    }

    public function index()
    {
        template('provider/index');
    }

    /*Mandar los parametros al modelo para registrar*/
    public function register_provider()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->provider_model->register_provider();
        } else {
            show_404();
        }
    }

    public function modify_provider()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->provider_model->modify_provider();
        } else {
            show_404();
        }
    }

    public function disable_provider()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->provider_model->disable_provider();
        } else {
            show_404();
        }
    }

    public function activate_provider()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->provider_model->activate_provider();
        }
        else
        {
            show_404();
        }
    }
    /*Para cargar la lista de tipo de almacen en el dataTable*/
    public function get_provider_list()
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

            echo json_encode($this->provider_model->get_provider_list($params));
        } else {
            show_404();
        }
    }

    /* Obtene los proveedores de un producto*/

    public function get_providers_product()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->provider_model->get_providers_product();
        } else {
            show_404();
        }
    }

    public function search_provider() {
        $search = $this->input->post_get('search');
        echo json_encode($this->provider_model->search_provider($search));
    }

}