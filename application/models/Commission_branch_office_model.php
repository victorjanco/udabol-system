<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 14:48 AM
 */

class Commission_branch_office_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_commission_branch_office(){
        $this->db->select('*')
            ->from('sucursal_comision')
            ->where('estado', ACTIVO)
            ->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function exists_commission_branch_office_by_name($name)
    {
        $this->db->select('*')
            ->from('sucursal_comision')
            ->where('nombre', $name)
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

    public function get_commission_branch_office_by_name($name)
    {
        $this->db->select('*')
            ->from('sucursal_comision')
            ->where('nombre', $name)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }

    public function get_commission_branch_office_by_id($id){
        $this->db->select('*')
            ->from('sucursal_comision')
            ->where('id', $id)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }
}