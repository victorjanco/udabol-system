<?php
/**
 * User: Ing. Ariel Alejandro Gomez Chavez
 * Github: https://github.com/ariel-ssj
 * Date: 30/9/2019 16:28
 */

class Reception_payment_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_payment_by_reception_id($reception_id)
	{
		$this->db->select('*');
		$this->db->from('vista_pago_recepcion');
		$this->db->where('recepcion_id', $reception_id);
		$this->db->where('estado=', ACTIVO);
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result();
	}

	public function get_payment_by_id($payment_id)
	{
		$this->db->select('*');
		$this->db->from('pago_recepcion');
		$this->db->where('id', $payment_id);
		return $this->db->get()->row();
	}

	public function get_reception_payment_list($params = array())
	{
		// Se cachea la informacion que se arma en query builder
		$this->db->start_cache();
		$this->db->select('p.*')
			->from('vista_pago_recepcion p')
			->where('p.recepcion_id', $params['id_reception'])
			->where('p.sucursal_id', get_branch_id_in_session());
		$this->db->stop_cache();

		// Obtener la cantidad de registros NO filtrados.
		// Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
		$records_total = count($this->db->get()->result_array());

		// Concatenar parametros enviados (solo si existen)
		if (array_key_exists('start', $params) && array_key_exists('limit', $params)) {
			$this->db->limit($params['limit']);
			$this->db->offset($params['start']);
		}

		if (array_key_exists('column', $params) && array_key_exists('order', $params)) {
			if ($params['column'] == 'p.id') {
				$this->db->order_by('p.id', $params['order']);
			} else {
				$this->db->order_by($params['column'], $params['order']);
			}
		} else {
			$this->db->order_by('p.id', 'ASC');
		}

		// Obtencion de resultados finales
		$draw = $this->input->post('draw');
		$data = $this->db->get()->result_array();

		$json_data = array(
			'draw' => intval($draw),
			'recordsTotal' => $records_total,
			'recordsFiltered' => $records_total,
			'data' => $data,
		);
		return $json_data;
	}

	public function get_sum_reception_payments($reception_id)
	{
		$this->db->select('recepcion_id, SUM(descuento)as total_descuentos, SUM(pago)as total_pagados');
		$this->db->from('pago_recepcion');
		$this->db->where('recepcion_id', $reception_id);
		$this->db->where('estado', ACTIVO);
		$this->db->group_by(array('recepcion_id'));
		$response = $this->db->get();
		if ($response->num_rows() > 0) {
			return $response->row();

		} else {
			$response = new stdClass();
			$response->recepcion_id = $reception_id;
			$response->total_descuentos = 0;
			$response->total_pagados = 0;
			return $response ;
		}
	}

	public function register_payment()
	{
		$response = array(
			'success' => FALSE,
			'messages' => array(),
			'receipt_payment_id'=>''
		);
		// Reglas de validacion
		$validation_rules = array(
			array(
				'field' => 'reception_payment',
				'label' => 'Debe ingresar un monto de pago',
				'rules' => "trim|required"
			),
		);
		// Pasar reglas de validacion como parámetro
		$this->form_validation->set_rules($validation_rules);
		$this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
		if ($this->form_validation->run() === TRUE) {

			// Inicio de transacción
			$this->db->trans_begin();
			$id_reception = $this->input->post('id_reception');
			$code_reception = $this->input->post('code_reception');
			$date_payment = $this->input->post('date_payment');
			$reception_total = $this->input->post('reception_total');
			$reception_discount = $this->input->post('reception_discount');
			$reception_total_payment = $this->input->post('reception_total_payment');
			$reception_payment = $this->input->post('reception_payment');
			$reception_balance = $this->input->post('reception_balance');
			$reception_observation= $this->input->post('reception_payment_observation');
			$date_reception = $this->input->post('date_payment');


			if ($reception_payment > 0) {
				$payment['observacion'] = $reception_observation.' de ' . $code_reception .' en fecha '.date('d/m/Y');
				$payment['subtotal'] = $reception_total;
				$payment['descuento'] = $reception_discount;
				$payment['total'] = $reception_total_payment;
				$payment['pago'] = $reception_payment;
				$payment['pagos_anteriores'] = 0;
				$payment['saldo'] = $reception_balance;
				$payment['fecha_registro'] = date('Y-m-d H:i:s');
				$payment['fecha_modificacion'] = date('Y-m-d H:i:s');
				$payment['estado'] = ACTIVO;
				$payment['usuario_id'] = get_user_id_in_session();
				$payment['usuario_id_modificacion'] = get_user_id_in_session();
				$payment['recepcion_id'] = $id_reception;
				$payment['fecha_pago'] = $date_reception;
                $payment['sucursal_id'] = get_branch_id_in_session();

				$this->db->insert('pago_recepcion', $payment);

 				$payment_reception_inserted = $this->_get_payment_reception($payment);
				$payment_reception_id = $payment_reception_inserted->id;

				$response['receipt_payment_id'] = $payment_reception_id;
			}

			// Obtener resultado de transacción
			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$response['success'] = TRUE;
			} else {
				$this->db->trans_rollback();
				$response['success'] = FALSE;
			}
		} else {
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		return json_encode($response);
	}

	public function _get_payment_reception($payment_reception)
	{
		return $this->db->get_where('pago_recepcion', $payment_reception)->row();
	}



	public function disable()
	{
		$id = $this->input->post('id');
		return $this->db->update(
			'pago_recepcion',
			['estado' => ANULADO],
			['id' => $id]
		);
	}
}
