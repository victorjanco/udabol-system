<?php

/**
 * Created by PhpStorm.
 * User: Ronald Saucedo Tito
 * Date: 28/8/2017
 * Time: 2:54 PM
 */
class Sale extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sale_model');
        $this->load->model('pago_model');
        $this->load->model('group_model');
        $this->load->model('office_model');
        //$this->load->model('order_work_model');
        $this->load->model('change_type_model');
    }

    public function index()
    {
        $subgroup_for_add = $this->group_model->get_groups();
        $branch_office_for_add = $this->office_model->get_offices_session();
        $data = array(
            'subgroup_for_add' => $subgroup_for_add,
            'branch_office_for_add' => $branch_office_for_add,
            'cash_id'=>get_session_cash_id(), //caja aperturada
            'cash_aperture_id'=>get_session_cash_aperture_id(), //aperturada_caja_id,
            'change_type'=> $this->change_type_model->get_first()
        );
        template('sale/new_sale', $data);
    }

    public function new_sale()
    {
        $type_product_for_add = $this->type_product_model->get_type_product();
        $branch_office_for_add = $this->office_model->get_offices();
        
        $data = array(
            'type_product_for_add' => $type_product_for_add,
            'branch_office_for_add' => $branch_office_for_add,
        );
        template('sale/new_sale', $data);
    }

    public function new_invoice()
    {
        template('sale/new_invoice');
    }

    public function sale_note()
    {
        template('sale/sale_note');
    }

    public function sale_note_credit()
    {
        template('sale/sale_note_credit');
    }

    public function sale_invoice()
    {
        template('sale/sale_invoice');
    }

    public function sale_disable()
    {
        template('sale/sale_disable');
    }


    public function add_row_product_sale()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->sale_model->add_row_product_sale());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function delete_row_product_detail_sale()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->sale_model->delete_row_product_detail_sale());
        } else {
            show_404();
        }
    }

    public function cancel_sale()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->sale_model->cancel_sale());
        } else {
            show_404();
        }
    }

    /*Para registrar la nueva venta*/
    public function register_sale()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->sale_model->register_sale());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*Para registrar la nueva venta*/
    public function generate_invoice()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->sale_model->generate_invoice());
        } else {
            show_404();
        }
    }

    /*Para registrar la nueva venta apartir de un id orden trabajo*/
    public function generate_sale_by_order_work_id()
    {
        try {
            if ($this->input->is_ajax_request()) {
                $order_work_id = $this->input->post('order_work_id');
    
                echo json_encode($this->sale_model->generate_sale_by_order_work_id($order_work_id));
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }


    /*Para modificar una venta*/
    public function modify_sale()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->sale_model->modify_sale());
        } else {
            show_404();
        }
    }

    /*Para eliminar un venta seleccionado de la lista*/
    public function disable_sale()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->sale_model->disable_sale();
        } else {
            show_404();
        }
    }
     /*Para eliminar un venta seleccionado de la lista*/
     public function disable_sale_credit()
     {
         if ($this->input->is_ajax_request()) {
             echo $this->sale_model->disable_sale_credit();
         } else {
             show_404();
         }
     }

    /*Para eliminar un factura seleccionado de la lista*/
    public function disable_invoice()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->sale_model->disable_invoice();
        } else {
            show_404();
        }
    }

    /*Para obtener datos de un cliente en especifico por el id del cliente*/
    public function get_sale_id()
    {
        if ($this->input->is_ajax_request()) {
            $sale_id = $this->input->post('id');
            echo json_encode($this->sale_model->get_sale_id($sale_id));
        } else {
            show_404();
        }
    }

    /*Para obtener datos de un cliente en especifico por el id del cliente*/
    public function get_sale_for_invoice()
    {
        if ($this->input->is_ajax_request()) {
            $sale_id = $this->input->post('id');
            echo json_encode($this->sale_model->get_print_note_sale($sale_id));
        } else {
            show_404();
        }
    }

    /*Para obtener datos de un cliente en especifico por el id del cliente*/
    public function get_sale_invoice()
    {
        if ($this->input->is_ajax_request()) {
            $invoice_id = $this->input->post('id');
            $invoice_sale = $this->sale_model->get_invoice_sale($invoice_id);
            $sale_id = $invoice_sale->venta_id;
            echo json_encode($this->sale_model->get_print_note_sale($sale_id));
        } else {
            show_404();
        }
    }

    /*Obtener todas las ventas activos especial para cargar combos o autocompletados*/
    public function get_sale_enable()
    {
        if ($this->input->is_ajax_request()) {
            $sale_list = $this->bsalemodel->get_sale_enable();
            echo json_encode($sale_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de ventas en el dataTable*/
    public function get_sale_note_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];
            $filter_date_start = $this->input->post('filter_date_start');
            $filter_date_end = $this->input->post('filter_date_end');
            $sale_number = $this->input->post('filter_sale_number');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'sale_date_start' => $filter_date_start,
                'sale_date_end' => $filter_date_end,
                'sale_number' => $sale_number
            );

            echo json_encode($this->sale_model->get_sale_note_list($params));
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de ventas en el dataTable*/
    public function get_sale_invoice_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];
            $filter_date_start = $this->input->post('filter_date_start');
            $filter_date_end = $this->input->post('filter_date_end');
            $sale_number = $this->input->post('filter_sale_number');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'sale_date_start' => $filter_date_start,
                'sale_date_end' => $filter_date_end,
                'invoice_number' => $sale_number
            );

            echo json_encode($this->sale_model->get_sale_invoice_list($params));
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de ventas anuladas*/
    public function get_sale_note_list_disable()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];
            $filter_date_start = $this->input->post('filter_date_start');
            $filter_date_end = $this->input->post('filter_date_end');
            $sale_number = $this->input->post('filter_sale_number');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'sale_date_start' => $filter_date_start,
                'sale_date_end' => $filter_date_end,
                'sale_number' => $sale_number
            );

            echo json_encode($this->sale_model->get_sale_note_list_disable($params));
        } else {
            show_404();
        }
    }

    function credit_sale()
    {

        $this->db->select('*')
            ->from('venta')
            ->where('tipo_venta', 'VENTA CREDITO')
            ->order_by('id', 'ASC');
        $query = $this->db->get()->result();
        echo json_encode($query);
//        $this->db->trans_begin();
        foreach ($query as $row) {
            $number_credit_sale = $this->sale_model->last_number_credit_sale_by_sucursal_id($row->sucursal_id);

            $data_credit_sale["nro_venta_credito"] = $number_credit_sale;
            $data_credit_sale["nro_cuotas_credito"] = 1;
            $data_credit_sale["nro_cuotas_pagadas"] = 0;
            $data_credit_sale["monto_credito"] = $row->subtotal;
            $data_credit_sale["monto_saldo"] = $row->subtotal;
            $data_credit_sale["interes"] = 0;
            $data_credit_sale["fecha_registro"] = $row->fecha_registro;
            $data_credit_sale["fecha_modificacion"] = $row->fecha_registro;
            $data_credit_sale["fecha_vencimiento"] = $row->fecha_registro;
            $data_credit_sale["dias_plazo"] = 0;
            $data_credit_sale["deuda"] = 1;
            $data_credit_sale["estado"] = 1;
            $data_credit_sale["sucursal_id"] = $row->sucursal_id;
            $data_credit_sale["venta_id"] = $row->id;
            $data_credit_sale["usuario_id"] = $row->usuario_id;

            $this->db->insert('venta_credito', $data_credit_sale);
        }

        /*  if ($this->db->trans_status() === FALSE) {
              $this->db->trans_rollback();
          } else {
              $this->db->trans_commit();
              echo "yyyyyyyyyy Registrados Correctamente ya Puede trabajar con el Sistema The Best";
              echo json_encode($query);
          }*/
    }

    /*Para cargar la lista de ventas en el dataTable*/
    public function get_sale_note_credit_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];
            $filter_date_start = $this->input->post('filter_date_start');
            $filter_date_end = $this->input->post('filter_date_end');
            $sale_number = $this->input->post('filter_sale_number');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'sale_date_start' => $filter_date_start,
                'sale_date_end' => $filter_date_end,
                'sale_number' => $sale_number
            );

            echo json_encode($this->sale_model->get_sale_note_credit_list($params));
        } else {
            show_404();
        }
    }

    public function update_cost_by_sale()
    {

    }


}
