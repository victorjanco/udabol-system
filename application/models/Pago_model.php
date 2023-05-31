<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
class Pago_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_pago_by_reception_id($reception_id)
    {
        $this->db->select('*');
        $this->db->from('pago');
        $this->db->where('recepcion_id', $reception_id);
        $this->db->where('estado=', 1);
        return $this->db->get()->result();
    }

    public function get_pago_by_id($pago_id)
    {
        $this->db->select('*');
        $this->db->from('pago');
        $this->db->where('id', $pago_id);
        return $this->db->get()->row();
    }

    public function get_reception_pago_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('p.*')
            ->from('pago_recepcion p')
            ->where('p.recepcion_id', $params['id_reception']);
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

    public function get_sum_pago_by_reception_id($recepcion_id)
    {
        $this->db->select_sum('pago');
        $this->db->from('pago_recepcion');
        $this->db->where('recepcion_id', $recepcion_id);
        $this->db->where('estado', ACTIVO);
        $response = $this->db->get();
        if ($response->num_rows() > 0) {
            return $response->row()->pago;

        } else {
            return 0;
        }
    }

    public function get_sum_descuento_by_reception_id($recepcion_id)
    {
        $this->db->select_sum('descuento');
        $this->db->from('pago');
        $this->db->where('recepcion_id', $recepcion_id);
        $this->db->where('estado', ACTIVO);
        $response = $this->db->get();
        if ($response->num_rows() > 0) {
            return $response->row()->pago;
        } else {
            return 0;
        }
    }

    public function register_pago()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'pago',
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
            // Registrar a la base de datos de nuevo tipo_almacen
            $this->save_pago();//$this->db->insert('pago', $data_pago);
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

        echo json_encode($response);
    }

    public function save_pago()
    {
        $reception_id = $this->input->post('id_reception');
        $pago = $this->input->post('pago');
        $monto_total = $this->reception_model->get_reception_by_id($reception_id)->monto_total;
        //$sum_descuentos = $this->pago_model->get_sum_descuento_by_reception_id();
        $descuento = $this->input->post('sale_discount');
        $descuento ? 0.00 : $descuento = 0.00;
        $monto_neto = $monto_total - $descuento;
        $pagos_anteriores = 0.00;
        $obj_pagos_anteriores = $this->pago_model->get_sum_pago_by_reception_id($reception_id);
        $obj_pagos_anteriores != '' ? $pagos_anteriores = $obj_pagos_anteriores : $pagos_anteriores = 0.00;
        $saldo = $monto_neto - $pagos_anteriores - $pago;
        $data = array(
            'pago' => $pago,
            'fecha_registro' => date('Y-m-d H:i:s'),
            'recepcion_id' => $this->input->post('id_reception'),
            'monto_total' => $monto_total,
            'descuento' => $descuento,
            'monto_neto' => $monto_neto,
            'pagos_anteriores' => $pagos_anteriores,
            'saldo' => $saldo,
            'user_id' => get_user_id_in_session(),
        );
        $this->db->insert('pago', $data);
    }

    public function save_pago_with_reception_id($reception_id)
    {
        $pago = $this->input->post('pago');
        $monto_total = $this->reception_model->get_reception_by_id($reception_id)->monto_total;
        //$sum_descuentos = $this->pago_model->get_sum_descuento_by_reception_id();
        $descuento = $this->input->post('sale_discount');
        $descuento ? 0.00 : $descuento = 0.00;
        $monto_neto = $monto_total - $descuento;
        $pagos_anteriores = 0.00;
        $obj_pagos_anteriores = $this->pago_model->get_sum_pago_by_reception_id($reception_id);
        $obj_pagos_anteriores != '' ? $pagos_anteriores = $obj_pagos_anteriores : $pagos_anteriores = 0.00;
        $saldo = $monto_neto - $pagos_anteriores - $descuento - $pago;
        $data = array(
            'pago' => $pago,
            'fecha_registro' => date('Y-m-d H:i:s'),
            'recepcion_id' => $reception_id,
            'monto_total' => $monto_total,
            'descuento' => $descuento,
            'monto_neto' => $monto_neto,
            'pagos_anteriores' => $pagos_anteriores,
            'saldo' => $saldo,
            'user_id' => get_user_id_in_session(),
        );
        $this->db->insert('pago', $data);
    }

    public function disable_pago()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'pago',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }
}
