<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 16/09/2019
 * Time: 17:56 PM
 */

class Serie_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_serie(){
        $this->db->select('*')
            ->from('serie')
            ->where('estado', ACTIVO)
            ->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_serie_by_id($id)
    {
        $this->db->select('*')
            ->from('serie')
            ->where('id', $id)
            ->where('estado', ACTIVO);
        return $this->db->get()->row();
    }
}