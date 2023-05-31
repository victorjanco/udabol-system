<?php
/**
 * Created by PhpStorm.
 * User: Renato Reyes Fuentes (Green Ranger)
 * Date: 03/09/2018
 * Time: 04:56 PM
 */

class Reporte_caja_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function buscar_caja($params = array())
    {
       
        /*parametros*/
        $report_start_date = $params["report_start_date"];
        $report_end_date = $params["report_end_date"];
        $report_office = $params["report_office"];
        $report_cash = $params["report_cash"];
        $report_user = $params["report_user"];

        /*consulta vista*/
        $this->db->start_cache();

        $this->db->select('vista_cierre_caja.*, (monto_cierre_bs + (monto_cierre_sus * 6.95) - total_cambio) total_efective');
        $this->db->from('vista_cierre_caja');
        // $this->db->where('estado',ACTIVO);

        if ($report_office != '0') {
            $this->db->where('sucursal_id =', $report_office);
        }
        if($report_start_date != ''){
            $this->db->where('DATE(fecha_cierre) >=',$report_start_date);
        }
        if($report_end_date!=''){
            $this->db->where('DATE(fecha_cierre) <=',$report_end_date);
        }
        if ($report_cash != '0') {
            $this->db->where('caja_id =', $report_cash);
        }
        if ($report_user != '0') {
            $this->db->where('usuario_id =', $report_user);
        }


        $this->db->order_by('fecha_cierre', 'ASC');
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

    function get_select_cierre_caja($params = array(), $select, $group)
    {
        $reporte_sucursal = $params["reporte_sucursal"];
        $reporte_caja = $params["reporte_caja"];
        $reporte_usuario = $params["reporte_usuario"];
        $reporte_fecha_inicio = $params["reporte_fecha_inicio"];
        $reporte_fecha_fin = $params["reporte_fecha_fin"];


        $this->db->select($select)
            ->from('vista_cierre_caja');

        if ($reporte_sucursal != '0') {
            $this->db->where('sucursal_id', $reporte_sucursal);
        }

        if ($reporte_caja != '0') {
            $this->db->where('caja_id', $reporte_caja);
        }

        if ($reporte_usuario != '0') {
            $this->db->where('usuario_id', $reporte_usuario);
        }

        if (isset($reporte_fecha_inicio) && $reporte_fecha_inicio != '') {
            $this->db->where('date(fecha_cierre) >=', $reporte_fecha_inicio);
        }

        if (isset($reporte_fecha_fin) && $reporte_fecha_fin != '') {
            $this->db->where('date(fecha_cierre) <=', $reporte_fecha_fin);
        }
        
        $this->db->group_by($group);
        $this->db->order_by($group, 'ASC');

        $resultado = $this->db->get()->result();

        return $resultado;
    }

    function buscar_caja_excel($params = array())
    {
        $report_start_date = $params["report_start_date"];
        $report_end_date = $params["report_end_date"];
        $report_office = $params["report_office"];
        $report_cash = $params["report_cash"];
        $report_user = $params["report_user"];

        $this->db->start_cache();

        $this->db->select('*');
        $this->db->from('vista_cierre_caja');
        // $this->db->where('estado',ACTIVO);

        if ($report_office != '0') {
            $this->db->where('sucursal_id =', $report_office);
        }
        if($report_start_date != ''){
            $this->db->where('date(fecha_cierre) >=',$report_start_date);
        }
        if($report_end_date!=''){
            $this->db->where('date(fecha_cierre) <=',$report_end_date);
        }
        if ($report_cash != '0') {
            $this->db->where('caja_id =', $report_cash);
        }
        if ($report_user != '0') {
            $this->db->where('usuario_id =', $report_user);
        }


        $this->db->order_by('fecha_cierre', 'ASC');

        $this->db->stop_cache();

        $data = $this->db->get()->result_array();
        $this->db->flush_cache();

        return $data;

    }

}