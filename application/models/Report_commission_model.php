<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 17/09/2019
 * Time: 11:36 AM
 */

class Report_commission_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_report_commission($params = array())
    {
        //parametros
        $brand = $params["brand"];
        $model = $params["model"];
        $serie = $params["serie"];
        $product = $params["product"];
        $commission_branch_office = $params["commission_branch_office"];
        $start_date = $params["start_date"];
        $end_date = $params["end_date"];

        //consulta vista
        $this->db->start_cache();

        $this->db->select('*');
        $this->db->from('vista_transaccion_comision');
        $this->db->where('estado', ACTIVO);

        if ($brand != '0') {
            $this->db->where('marca_id =', $brand);
        }
        if ($model != '0') {
            $this->db->where('model_id =', $model);
        }
        if ($serie != '0') {
            $this->db->where('serie_id =', $serie);
        }
        if ($product != '0') {
            $this->db->where('producto_id =', $product);
        }
        if ($commission_branch_office != '0') {
            $this->db->where('sucursal_comision_id =', $commission_branch_office);
        }
        if($start_date != ''){
            $this->db->where('fecha_transaccion >=',$start_date );
        }
        if($end_date!=''){
            $this->db->where('fecha_transaccion <=',$end_date );
        }

        $this->db->order_by('fecha_transaccion', 'ASC');
        $this->db->stop_cache();


        // Obtener la cantidad de registros NO filtrados.
        // Query builder se mantiene ya que se tiene almacenada la estructura de la consulta en memoria
        $records_total = count($this->db->get()->result_array());

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

    public function exists_transaction_commission($date_transaction, $code_product, $branch_office_commission, $glosa, $code_transaction)
    {
        $this->db->select('*')
            ->from('transaccion_comision')
            ->where('fecha_transaccion', $date_transaction)
            ->where('codigo_producto', $code_product)
            ->where('nombre_sucursal_comision', $branch_office_commission)
            ->where('glosa_productos', $glosa)
            ->where('codigo_transaccion', $code_transaction);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }

    public function get_transaction_commission($date_transaction, $code_product, $branch_office_commission, $glosa, $code_transaction)
    {
        $this->db->select('*')
            ->from('transaccion_comision')
            ->where('fecha_transaccion', $date_transaction)
            ->where('codigo_producto', $code_product)
            ->where('nombre_sucursal_comision', $branch_office_commission)
            ->where('glosa_productos', $glosa)
            ->where('codigo_transaccion', $code_transaction);
        return $this->db->get()->row();
    }
}