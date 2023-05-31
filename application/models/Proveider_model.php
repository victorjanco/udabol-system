<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 08:18 PM
 */
class Proveider_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_proveiders($type = 'object'){
        return $this->db->get_where('proveedor', array('estado'=>get_state_abm('ACTIVO')))->result($type);
    }
}