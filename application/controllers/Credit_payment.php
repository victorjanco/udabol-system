<?php

/**
 *  * Created by PhpStorm.
 * User: Ariel Alejandro Gomez Chavez ( @ArielGomez )
 * Date: 30/5/2018
 * Time: 12:06 PM
 */
class Credit_payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('credit_payment_model');
        $this->load->model('customer_model');
        $this->load->model('change_type_model');
    }

    /*Mandar al index para cargar la lista de marcas*/
    public function index()
    {
        template('credit_payment/index');
    }

    /*Formulario nuevo cliente*/
    public function new_payment()
    {
        if ($this->input->post()) {
            $customer_id = $this->input->post('id');
            $credit_payment = $this->credit_payment_model->get_credit_sale_list_by_customer_id($customer_id);
            $customer = $this->customer_model->get_customer_id($customer_id);

            $data = array(
                'customer' => $customer,
                'credit_payment' => $credit_payment,
                'cash_id'=>get_session_cash_id(), //caja aperturada
                'cash_aperture_id'=>get_session_cash_aperture_id(), //aperturada_caja_id,
                'change_type'=> $this->change_type_model->get_first()
            );
            template('credit_payment/new_payment', $data);
        } else {
            show_404();
        }
    }

    public function view_payment()
    {
        if ($this->input->post()) {
            $credit_payment_id = $this->input->post('id');
            $credit_payment = $this->credit_payment_model->get_payment_id($credit_payment_id);
            $detail_credit_payment = $this->credit_payment_model->get_detail_payment_by_payment_id($credit_payment_id);

            $data = array(
                'detail_credit_payment' => $detail_credit_payment,
                'credit_payment' => $credit_payment
            );
            template('credit_payment/view_payment', $data);
        } else {
            show_404();
        }

    }

    public function payments()
    {
        template('credit_payment/payments');
    }


    /*Para registrar la nueva marca*/
    public function register_payment()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo $this->credit_payment_model->register_payment();
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
//Para eliminar un listado de pago seleccionado de la lista
	public function disable_credit_payment()
	{
			if ($this->input->is_ajax_request()) {
				echo $this->credit_payment_model->disable_credit_payment();
			} else {
				show_404();
			}
	}
/////////////
/*
public function disable_credit_payment()
{
	try {
		if ($this->input->is_ajax_request()) {
			$credit_payment_id = $this->input->post("credit_payment_id");

			echo json_encode($this->credit_payment_model->disable_credit_payment($credit_payment_id));
		} else {
			show_404();
		}
	} catch (\Throwable $th) {
		throw $th;
	}
}
	*/
/////////////
/*Para cargar la lista de pagos de credito en el dataTable*/
    public function get_credit_payment_list()
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

            $data_credit_payment = $this->credit_payment_model->get_credit_payment_list($params);
            $data = array();
            foreach ($data_credit_payment['data'] as $row) {
                $array = array();
                $array['id'] = $row['cliente_id'];
                $array['sucursal_id'] = $row['sucursal_id'];
                $array['nombre'] = $row['nombre'];
                $array['nombre_sucursal'] = get_branch_office_name_in_session();
                $array['monto_total'] = $row['monto_total'];
                $array['monto_saldo'] = $row['monto_saldo'];
                $array['monto_credito'] = $row['monto_credito'];
                $array['fecha_vencimiento'] = $row['fecha_vencimiento'];
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_credit_payment['draw'],
                'recordsTotal' => $data_credit_payment['recordsTotal'],
                'recordsFiltered' => $data_credit_payment['recordsFiltered'],
                'data' => $data,
            );
            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function get_payment_customer_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            $customer_id = $this->input->post('customer_id');


            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'customer_id' => $customer_id
            );

            $data_credit_payment = $this->credit_payment_model->get_payment_list_by_customer_id($params);
            $data = array();
            foreach ($data_credit_payment['data'] as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['nro_transaccion_pago'] = $row['nro_transaccion_pago'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['fecha_modificacion'] = $row['fecha_modificacion'];
                $array['monto_total'] = $row['monto_total'];
                $array['observacion'] = $row['observacion'];
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_credit_payment['draw'],
                'recordsTotal' => $data_credit_payment['recordsTotal'],
                'recordsFiltered' => $data_credit_payment['recordsFiltered'],
                'data' => $data,
            );
            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function get_payment_list()
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

            $data_credit_payment = $this->credit_payment_model->get_payment_list($params);
            $data = array();
            foreach ($data_credit_payment['data'] as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['nro_transaccion_pago'] = $row['nro_transaccion_pago'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['fecha_modificacion'] = $row['fecha_modificacion'];
                $array['monto_total'] = $row['monto_total'];
                $array['observacion'] = $row['observacion'];
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_credit_payment['draw'],
                'recordsTotal' => $data_credit_payment['recordsTotal'],
                'recordsFiltered' => $data_credit_payment['recordsFiltered'],
                'data' => $data,
            );
            echo json_encode($json_data);
        } else {
            show_404();
        }
    }

    public function payment_customer()
    {
        if ($this->input->post()) {
            $customer_id = $this->input->post('id');
            $data = array(
                'customer' => $customer_id,
            );
            template('credit_payment/payment_customer', $data);
        } else {
            show_404();
        }
    }
}
