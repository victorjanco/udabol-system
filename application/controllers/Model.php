<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 19/7/2017
 * Time: 12:06 PM
 */
class Model extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_model');
        $this->load->model('brand_model');
    }

    /*Mandar al index para cargar la lista de modelos*/
    public function index()
    {
        $brand_for_new = $this->brand_model->get_brand_enable();
//        if ($brand_for_new) {
            $data = array(
                'brand_for_new' => $brand_for_new
            );
            template('model/index',$data);

//        } else {
//            show_404();
//        }
    }

    /*Para registrar el nuevo modelo*/
    public function register_model()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->model_model->register_model();
        } else {
            show_404();
        }
    }
    /*Para modificar el modelo*/
    public function modify_model()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->model_model->modify_model();
        } else {
            show_404();
        }
    }

    /*Para eliminar el modelo seleccionado de la lista*/
    public function disable_model()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->model_model->disable_model();
        } else {
            show_404();
        }
    }

    /*Obtener todas los modelos activos especial para cargar combos o autocompletados*/
    public function get_model_enable()
    {
        if ($this->input->is_ajax_request()) {
            $model_list=$this->model_model->get_model_enable();
            echo json_encode($model_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de modelo en el dataTable*/
    public function get_model_list()
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

            echo json_encode($this->model_model->get_model_list($params));
        } else {
            show_404();
        }
    }

    /*Retorna listado de modelos por marca  */
    public function get_model_by_brand()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->model_model->get_model_by_brand());
        } else {
            show_404();
        }
    }

    /*Retorna listado de modelos por marca  */
    public function get_model_by_id()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->model_model->get_model_id($this->input->post("id")));
        } else {
            show_404();
        }
    }
}