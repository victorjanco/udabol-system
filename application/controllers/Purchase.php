<?php

/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 18/08/2017
 * Time: 23:02
 */
class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model');
        $this->load->model('provider_model');
        $this->load->model('warehouse_model');
    }

    public function index()
    {
        template('purchase/index');
    }

    public function new_purchase()
    {
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_enable();
        template('purchase/new_purchase', $data);
    }

    public function edit_purchase()
    {
        $idPurchase = $this->input->post('id');
        if ($idPurchase == null) {
            template('purchase/index');
            return;
        }
        $purchase = $this->purchase_model->get_purchase_by_id($idPurchase);
        $detail = $this->purchase_model->get_purchase_detail($idPurchase);

        $provider = $this->provider_model->get_provider_by_id($purchase["proveedor_id"]);

        $data = array(
            "purchase" => $purchase,
            "provider" => $provider,
            "detail" => $detail
        );
        
        template('purchase/new_purchase', $data);
    }

    public function get_purchase_list()
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

            echo json_encode($this->purchase_model->get_purchase_list($params));
        } else {
            show_404();
        }
    }

    public function register_purchase()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->purchase_model->register_purchase());
        } else {
            show_404();
        }
    }

    public function update_purchase()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->purchase_model->update_purchase());
        } else {
            show_404();
        }
    }

    public function disable_purchase()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->purchase_model->disable_purchase();
        } else {
            show_404();
        }
    }
}