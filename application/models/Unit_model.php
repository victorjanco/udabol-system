<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 10:52 PM
 */
class Unit_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function first()
    {
        return $this->db->get('unidad_medida')->row();
    }

    public function get_units($type = 'object'){
        return $this->db->get_where('unidad_medida', array('estado'=>get_state_abm('ACTIVO')))->result($type);
    }

    public function exists_unit_name($unit_name)
    {
        $this->db->select('*')
            ->from('unidad_medida')
            ->where('nombre', $unit_name)
            ->where('estado', ACTIVO);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }

    public function get_unit_by_name($unit_name){
        $this->db->select('*')
            ->from('unidad_medida')
            ->where('nombre', $unit_name)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_unit($data_unit)
    {
        return $this->db->get_where('unidad_medida', $data_unit)->row();
    }
}