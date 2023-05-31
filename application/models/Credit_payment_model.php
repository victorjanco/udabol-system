<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 1/6/2018
 * Time: 15:19
 */

class Credit_payment_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sale_model');
    }

    /*Obtener datos de la pago credito apartir del pago_id*/
    public function get_payment_id($payment_id)
    {
        return $this->db->get_where('vista_transaccion_pago_venta_credito', array('id' => $payment_id))->row();
    }

    /*Obtener datos de la venta credito apartir del credito_id*/
    public function get_credit_sale_id($credit_sale_id)
    {
        return $this->db->get_where('venta_credito', array('id' => $credit_sale_id))->row();
    }

    public function get_credit_sale_list_by_customer_id($customer_id)
    {
        $this->db->select('*')
            ->from('vista_venta_credito')
            ->where('estado=1')
            ->where('monto_saldo>0')
            ->where('cliente_id', $customer_id)
            ->where('sucursal_id', get_branch_id_in_session())
            ->order_by('venta_id', 'ASC');
        return $this->db->get()->result();
    }

    /*Obtener lista de marcas para cargar la lista de dataTable*/
    public function get_payment_list_by_customer_id($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('vista_transaccion_pago_venta_credito')
            ->where('sucursal_id', get_branch_id_in_session())
            ->where('cliente_id', $params['customer_id']);
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
            $this->db->order_by($params['column'], $params['order']);
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(observacion)', strtolower($params['search']));
        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();
        $this->db->flush_cache();
        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
        );
        return $json_data;
    }

    public function get_credit_payment_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('vista_venta_credito_global_cliente')
            ->where('sucursal_id', get_branch_id_in_session())
            ->where('monto_saldo>0');
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
            if ($params['column'] != 'id' || $params['column'] != 'nombre_sucursal') {
                $this->db->order_by($params['column'], $params['order']);
            }
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(nombre)', strtolower($params['search']));
        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();
        $this->db->flush_cache();
        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
        );
        return $json_data;
    }

    /*Funcion registrar nueva marca para validar los datos de la vista */
    public function register_payment()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE,
                'cash' => FALSE
            );

            if (verify_session()) {
                if(verify_cash_session()){
                     // Reglas de validacion
                    $validation_rules = array(
                        array(
                            'field' => 'observation',
                            'label' => 'Debe agregar una observacion de la transaccion de pago',
                            'rules' => 'trim|required'
                        )

                    );

                    // Pasar reglas de validacion como parámetro
                    $this->form_validation->set_rules($validation_rules);
                    $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


                    if ($this->form_validation->run() === TRUE) {

                        // Inicio de transacción
                        $this->db->trans_begin();
                        $payment_array = $this->input->post("payment");
                        $credit_sale_array = $this->input->post("credit_sale_id");
                        $residue_array = $this->input->post("residue");
                        $customer_id = $this->input->post("customer_id");
                        $total_payment = $this->input->post("total_payment");
                        $observation = $this->input->post("observation");
                        $today = date('Y-m-d H:i:s');
                        $day_emission = date('Y-m-d');
                        $number_credit_sale = $this->last_number_by_branch_office_id(get_branch_id_in_session());

                        $data_credit_sale["nro_transaccion_pago"] = $number_credit_sale;
                        $data_credit_sale["observacion"] = $observation;
                        $data_credit_sale["fecha_emision"] = $day_emission;
                        $data_credit_sale["fecha_registro"] = $today;
                        $data_credit_sale["fecha_modificacion"] = $today;
                        $data_credit_sale["monto_total"] = $total_payment;
                        $data_credit_sale["estado"] = ACTIVO;
                        $data_credit_sale["cliente_id"] = $customer_id;
                        $data_credit_sale["sucursal_id"] = get_branch_id_in_session();
                        $data_credit_sale["usuario_id"] = get_user_id_in_session();
                        $data_credit_sale["usuario_cobrador_id"] = get_user_id_in_session();

                        $this->db->insert('transaccion_pago_venta_credito', $data_credit_sale);
                        $transaction_payment_inserted = $this->_get_transaction_payment($data_credit_sale);

                        ////////////////////////////////////INGRESO CAJA/////////////////////////////////////////
                        // if ($sale_type != 'VENTA CREDITO'){
                            $monto_efectivo = $this->input->post('monto_efectivo');
                            $monto_bs = $this->input->post('monto_bs');
                            $monto_sus = $this->input->post('monto_sus');
                            $monto_cheque = $this->input->post('monto_cheque');
                            $monto_tarjeta = $this->input->post('monto_tarjeta');
                            $monto_cambio = $this->input->post('monto_cambio');

                            if (strlen($monto_efectivo) == 0) {
                                $monto_efectivo = 0;
                            }
                            if (strlen($monto_bs) == 0) {
                                $monto_bs = 0;
                            }
                            if (strlen($monto_sus) == 0) {
                                $monto_sus = 0;
                            }
                            if (strlen($monto_cheque) == 0) {
                                $monto_cheque = 0;
                            }
                            if (strlen($monto_tarjeta) == 0) {
                                $monto_tarjeta = 0;
                            }
                            if (strlen($monto_cambio) == 0) {
                                $monto_cambio = 0;
                            }
                            $data_cash_income = array(
                                'nro_transaccion'=>$this->cash_income_model->get_number_transaction(),
                                'nro_ingreso'=> strval($this->cash_income_model->get_number_transaction()),
                                'detalle' => 'INGRESO POR PAGOS VENTAS CREDITOS',
                                'monto_venta' => number_format($total_payment, 2, '.', ''),
                                'monto_bs' => number_format($monto_bs, 2, '.', ''),
                                'monto_sus' => number_format($monto_sus, 2, '.', ''),
                                'monto_tarjeta' => number_format($monto_tarjeta, 2, '.', ''),
                                'monto_cheque' => number_format($monto_cheque, 2, '.', ''),
                                'monto_efectivo' => number_format($monto_efectivo, 2, '.', ''),
                                'monto_cambio' => number_format($monto_cambio, 2, '.', ''),
                                'fecha_ingreso' => date('Y-m-d'),
                                'fecha_registro' => date('Y-m-d H:i:s'),
                                'fecha_modificacion' => date('Y-m-d H:i:s'),
                                'estado' => ACTIVO,
                                'tipo_ingreso_caja_id'=> $this->cash_income_type_model->get_first()->id,
                                'caja_id'=> get_session_cash_id(),
                                'apertura_caja_id'=> get_session_cash_aperture_id(),
                                'sucursal_id' => get_branch_id_in_session(),
                                'user_created' => get_user_id_in_session()
                            );
                            $this->cash_income_model->_insert_cash_income($data_cash_income);
                            $cash_income_inserted = $this->cash_income_model->_get_cash_income($data_cash_income);

                            $data_cash_income_sale = array(
                                'ingreso_caja_id'=>$cash_income_inserted->id,
                                'transaccion_pago_venta_credito_id'=> $transaction_payment_inserted->id,
                                'estado' => ACTIVO
                            );
                            $this->db->insert('ingreso_caja_transaccion_pago', $data_cash_income_sale);
                        // }
                        /////////////////////////////////////////////////////////////////////////////////////////////


                        for ($pointer = 0; $pointer < count($credit_sale_array); $pointer++) {
                            if ($payment_array[$pointer] == '') {
                                $payment_array[$pointer] == 0;
                            }
                            if ($payment_array[$pointer] > 0) {
                                $credit_sale_specific_payment["observacion"] = $observation;
                                $credit_sale_specific_payment["fecha_emision"] = $day_emission;
                                $credit_sale_specific_payment["fecha_registro"] = $today;
                                $credit_sale_specific_payment["fecha_modificacion"] = $today;
                                $credit_sale_specific_payment["monto_total"] = $payment_array[$pointer];
                                $credit_sale_specific_payment["saldo_total_actual"] = $residue_array[$pointer];
                                $credit_sale_specific_payment["estado"] = ACTIVO;
                                $credit_sale_specific_payment["sucursal_id"] = get_branch_id_in_session();
                                $credit_sale_specific_payment["usuario_id"] = get_user_id_in_session();
                                $credit_sale_specific_payment["usuario_cobrador_id"] = get_user_id_in_session();
                                $credit_sale_specific_payment['venta_credito_id'] = $credit_sale_array[$pointer];
                                $credit_sale_specific_payment['transaccion_pago_venta_credito_id'] = $transaction_payment_inserted->id;
                                $this->db->insert('pago_cuota_venta_credito', $credit_sale_specific_payment);


                                $new_residue = $residue_array[$pointer] - $payment_array[$pointer];
                                $update_credit_sale['monto_saldo'] = $new_residue;
                                $this->db->where('id', $credit_sale_array[$pointer]);
                                $this->db->update('venta_credito', $update_credit_sale);

                                $credit_sale = $this->get_credit_sale_id($credit_sale_array[$pointer]);
                                if ($credit_sale->monto_saldo == 0) {
                                    $update_credit_sale['deuda'] = ANULADO;
                                    $this->db->where('id', $credit_sale_array[$pointer]);
                                    $this->db->update('venta_credito', $update_credit_sale);
                                }
                            }
                        }


                        // Obtener resultado de transacción
                        if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $response['success'] = TRUE;
                            $response['success'] = TRUE;
                            $response['payment_credit_sale_id'] = $transaction_payment_inserted->id;
                            $response['url_impression'] = 'print_sale/print_payment_credit_sale';
                        } else {
                            $this->db->trans_rollback();
                            $response['success'] = FALSE;
                        }
                    } else {
                        foreach ($_POST as $key => $value) {
                            $response['messages'][$key] = form_error($key);
                        }
                    }

                } else {
                    $response['cash'] = TRUE;
                }
            } else {
                $response['login'] = TRUE;
            }

            echo json_encode($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*Funcion para desabilitar el tipo almacen
    public function disable_credit_payment()
    {
		$id = $this->input->post('id');
		return $this->db->update(
			'transaccion_pago_venta_credito',
			['estado' => ANULADO],
			['id' => $id]
		);
    }*/
	///////////////////////
	public function get_payment_sale_credit_by_id($id)
	{
		return $this->db->get_where('transaccion_pago_venta_credito', array('id' => $id))->row();
	}
	public function get_detail_payment_sale_credit_by_payment_sale_credit_id($payment_sale_credit_id)
	{
		$this->db->select('det.*, cre.nro_venta_credito, cre.monto_credito, pla.nro_cuota, pla.monto as monto_plan_cuota')
			->from('detalle_pago_venta_credito det')
			->join('venta_credito cre', 'det.venta_credito_id=cre.id', 'left')
			->join('plan_cuota pla', 'det.plan_cuota_id=pla.id', 'left')
			->where('det.pago_venta_credito_id', $payment_sale_credit_id);
		$this->db->order_by('det.id', 'ASC');
		return $this->db->get()->result();
	}
	///////////////////////
	/*public function disable_credit_payment($credit_payment_id)
	{
		try {
			$this->db->trans_begin();

			// pago venta credito
			$payment_sale_credit = $this->get_payment_sale_credit_by_id($credit_payment_id);
			$data_payment_sale_credit['estado'] = ANULADO;
			$this->_update_payment_sale_credit($credit_payment_id, $data_payment_sale_credit);

			// Detalle pago venta credito
			$detail_payment_sale_credit = $this->get_detail_payment_sale_credit_by_payment_sale_credit_id($credit_payment_id);
			$sale_credit_id = 0;
			foreach ($detail_payment_sale_credit as $row) {

				if ($row->retraso == 0) {

					// PAGO DE CUOTA NORMAL
					$sale_credit_id = $row->venta_credito_id;
					$data_detail_payment_sale_credit['estado'] = ANULADO;
					$this->_update_detail_payment_sale_credit($credit_payment_id, $data_detail_payment_sale_credit);

					// Actualizacion de los montos del plan de pagos
					$fee_plan = $this->sale_model->get_fee_plan_by_id($row->plan_cuota_id);
					$data_fee_plan['monto_pagado'] = $fee_plan->monto_pagado - $row->monto_pagado;
					$data_fee_plan['saldo'] = $fee_plan->saldo + $row->monto_pagado;
					$this->sale_model->_update_fee_plan_by_id($row->plan_cuota_id, $data_fee_plan);

				}

				if ($row->retraso == 1) {

					// PAGO DE CUOTA CON RETRASO
					$sale_credit_id = $row->venta_credito_id;
					$data_detail_payment_sale_credit['estado'] = ANULADO;
					$this->_update_detail_payment_sale_credit($credit_payment_id, $data_detail_payment_sale_credit);

					// Actualizacion de los montos de retraso del plan de pagos
					$fee_plan = $this->sale_model->get_fee_plan_by_id($row->plan_cuota_id);
					$data_fee_plan['monto_atraso_pagado'] = $fee_plan->monto_atraso_pagado - $row->monto_pagado;
					$data_fee_plan['monto_atraso_saldo'] = $fee_plan->monto_atraso_saldo + $row->monto_pagado;
					$this->sale_model->_update_fee_plan_by_id($row->plan_cuota_id, $data_fee_plan);

				}

				if ($row->retraso == 2) {

					// PAGO DE CREDITO O DEUDA
					$sale_credit_id = $this->debt_model->get_sale_credit_by_debt_id($row->deuda_id)->venta_credito_id;
					$data_detail_payment_sale_credit['estado'] = ANULADO;
					$this->_update_detail_payment_sale_credit($credit_payment_id, $data_detail_payment_sale_credit);

					// Actualizacion de los montos de la deuda
					$debt = $this->debt_model->get_debt_by_id($row->deuda_id);
					$data_debt['monto_pagado'] = $debt->monto_pagado - $row->monto_pagado;
					$data_debt['saldo_deuda'] = $debt->saldo_deuda + $row->monto_pagado;
					$this->debt_model->_update_debt_by_id($row->deuda_id, $data_debt);

				}

			}

			// Actualizacion de los montos de la venta credito y estado pendiente
			$sale_credit = $this->sale_model->get_sale_credit_by_id($sale_credit_id);
			$amount_balance = $sale_credit->saldo_credito;
			$amount_paid = $sale_credit->monto_pagado;
			$payment_amount_made = $payment_sale_credit->monto_total_pagado;

			$new_amount_balance = $amount_balance + $payment_amount_made;
			$new_amount_paid = $amount_paid - $payment_amount_made;
			$data_sale_credit['saldo_credito'] = $new_amount_balance;
			$data_sale_credit['monto_pagado'] = $new_amount_paid;

			if ($new_amount_balance > 0) {
				$data_sale_credit['deuda'] = 1; // Pendiente

				$adeudo['adeudo'] = 1; // Pendiente
				$this->debt_model->_update_debt_by_id($row->deuda_id, $adeudo);
			}

			$this->sale_model->_update_sale_credit_by_id($sale_credit_id, $data_sale_credit);

			// INGRESO CAJA
			$cash_income_payment_sale_credit = $this->cash_income_model->get_cash_income_payment_sale_credit_by_payment_sale_credit_id($credit_payment_id);

			$cash_income = $this->cash_income_model->find($cash_income_payment_sale_credit->ingreso_caja_id);
			$cash_aperture = $this->cash_aperture_model->find($cash_income->apertura_caja_id);

			if($cash_aperture->estado_caja == ANULADO){ //CAJA CERRADA
				throw new Exception("Apertura de caja cerrada");
			}

			$data_cash_income['estado'] = ANULADO;
			$this->cash_income_model->_update_cash_income($cash_income_payment_sale_credit->ingreso_caja_id, $data_cash_income);

			// MONTO INGRESO CAJA
			$detail_cash_income = $this->cash_income_model->get_detail_amount_cash_income_by_cash_income_id($cash_income_payment_sale_credit->ingreso_caja_id);
			foreach ($detail_cash_income as $row) {
				$data_detail_cash_income['estado'] = ANULADO;
				$this->cash_income_model->_update_amount_cash_income($row->id, $data_detail_cash_income);
			}

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				return true;
			} else {
				$this->db->trans_rollback();
				return false;
			}
		} catch (\Throwable $th) {
			$this->db->trans_rollback();
			throw $th;
		}
	}
	/// //////////////////*/
	private function _update_payment_sale_credit($payment_sale_credit_id, $data_payment_sale_credit)
	{
		$where=array('id'=>$payment_sale_credit_id);
		return $this->db->update('pago_venta_credito', $data_payment_sale_credit, $where);
	}
	private function _update_detail_payment_sale_credit($detail_payment_sale_credit_id, $data_detail_payment_sale_credit)
	{
		$where = array('id' => $detail_payment_sale_credit_id);
		return $this->db->update('detalle_pago_venta_credito', $data_detail_payment_sale_credit, $where);
	}

	///
    function last_number_by_branch_office_id($branch_office_id)
    {
        $this->db->select_max('nro_transaccion_pago');
        $this->db->where('sucursal_id', $branch_office_id);
        $result = $this->db->get('transaccion_pago_venta_credito');

        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_transaccion_pago + 1;
        } else {
            return 1;
        }
    }

    private function _get_transaction_payment($sale)
    {
        return $this->db->get_where('transaccion_pago_venta_credito', $sale)->row();
    }

    public function get_detail_payment_by_payment_id($payment_id)
    {
        $this->db->select('vc.*, p.fecha_emision, p.monto_total as monto_pagado, p.saldo_total_actual as monto_saldo_actual')
            ->from(' pago_cuota_venta_credito p, vista_venta_credito vc')
            ->where('p.venta_credito_id=vc.id')
            ->where('p.transaccion_pago_venta_credito_id', $payment_id);
        return $this->db->get()->result();

    }

    public function get_payment_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('vista_transaccion_pago_venta_credito')
            ->where('sucursal_id', get_branch_id_in_session())
			->where('estado', ACTIVO);
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
            $this->db->order_by($params['column'], $params['order']);
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(nombre_cliente)', strtolower($params['search']));
            $this->db->or_like('lower(observacion)', strtolower($params['search']));
            $this->db->group_end();
        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();
        $this->db->flush_cache();
        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
        );
        return $json_data;
    }

    //obterner dosificaciones vencidas
	public function get_credit_sale_expired()
	{
        $now_date = date('Y-m-d');
		$this->db->select('count(id) AS vencidas');
		$this->db->from('venta_credito');
		$this->db->where('sucursal_id', get_branch_id_in_session());
        $this->db->where('fecha_vencimiento <', $now_date);
        $this->db->where('deuda=1');
		$this->db->where('estado=1');
		return $this->db->get()->result();
	}

    //obterner dosificaciones vencidas
	public function get_credit_sale_for_expired()
	{
        $Date = date('Y-m-d');
        $now_date = date('Y-m-d',strtotime($Date. ' + 10 days'));
		$this->db->select('count(id) AS vencidas');
		$this->db->from('venta_credito');
        $this->db->where('sucursal_id', get_branch_id_in_session());
        $this->db->where('fecha_vencimiento >=', $Date);
        $this->db->where('fecha_vencimiento <=', $now_date);
        $this->db->where('deuda=1');
		$this->db->where('estado=1');
		return $this->db->get()->result();
	}
}
