<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 31/07/2017
 * Time: 05:32 PM
 */
class Service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('service_model');
        $this->load->model('type_service_model');
    }


    public function index()
    {
        template('service/index');
    }


    /*Mandar los parametros al modelo para registrar al servicio*/
    public function register_service()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->service_model->register_service());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*Mandar los parametros al modelo para modificar al serivicio*/
    public function modify_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->service_model->modify_service();
        } else {
            show_404();
        }
    }

    /*Para eliminar un servicio seleccionado de la lista*/
    public function disable_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->service_model->disable_service();
        } else {
            show_404();
        }
    }

    /*Para eliminar un servicio seleccionado de la lista*/
    public function enable_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->service_model->enable_service();
        } else {
            show_404();
        }
    }

    /*Obtener todas los servicio activos especial para cargar combos o autocompletados*/

    public function get_service_enable()
    {
        if ($this->input->is_ajax_request()) {

            $service_list = $this->service_model->get_service_enable();
            echo json_encode($service_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de servicios en el dataTable*/
    public function get_service_list()
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

            echo json_encode($this->service_model->get_service_list($params));
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para registrar el tipo de servicio*/
    public function register_type_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_service_model->register_type_service();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el tipo de servicio*/
    public function modify_type_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_service_model->modify_type_service();
        } else {
            show_404();
        }
    }

    /*Para eliminar un tipo de servicio seleccionado de la lista*/
    public function disable_type_service()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->type_service_model->disable_type_service();
        } else {
            show_404();
        }
    }

    /*Obtener todas los tipos de servicio activos especial para cargar combos o autocompletados*/
    public function get_type_service_enable()
    {
        if ($this->input->is_ajax_request()) {

            $type_service_list = $this->type_service_model->get_type_service_enable();
            echo json_encode($type_service_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de tipo de servicio en el dataTable*/
    public function get_type_service_list()
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

            echo json_encode($this->type_service_model->get_type_service_list($params));
        } else {
            show_404();
        }
    }


    public function get_service_types()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->service_model->get_service_types());
        } else {
            show_404();
        }
    }

    public function get_service_by_type()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo json_encode($this->service_model->get_service_by_type($id));
        } else {
            show_404();
        }
    }

    public function get_service_price()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->service_model->get_service_price());
        } else {
            show_404();
        }
    }

    function get_service_category_by_service_id()
    {
        if ($this->input->is_ajax_request()) {
            $service_category_list=$this->service_model->get_service_category_by_service_id();
            echo json_encode($service_category_list);
        } else {
            show_404();
        }
    }

}