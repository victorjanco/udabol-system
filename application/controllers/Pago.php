<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros Leon
 * Date: 25/07/2017
 * Time: 05:01 PM
 */
class Pago extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pago_model');
    }

    public function get_pagos_by_reception_id()
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

            echo json_encode($this->pago_model->get_pago_by_reception_id($params));
        } else {
            show_404();
        }
    }

    public function get_reception_pago_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $id_reception = $this->input->post('id_reception');
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'id_reception' => $id_reception,
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->pago_model->get_reception_pago_list($params));
        } else {
            show_404();
        }
    }

    public function disable_pago()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->pago_model->disable_pago();
        } else {
            show_404();
        }
    }

    public function get_sum_pago_by_reception_id()
    {
        if ($this->input->is_ajax_request()) {
            $id_reception = $this->input->post('id');
            $pagos = $this->pago_model->get_sum_pago_by_reception_id($id_reception);
            echo $pagos;
        } else {
            show_404();
        }
    }


}
