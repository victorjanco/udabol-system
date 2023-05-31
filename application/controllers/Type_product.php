<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros L.
 * Date: 16/08/2017
 * Time: 15:07 PM
 */
class Type_product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_product_model');
    }

    /*Obtener todas los tipos de almacen activos especial para cargar combos o autocompletados*/
    public function get_type_product()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->type_product_model->get_type_product());
        } else {
            show_404();
        }
    }
}