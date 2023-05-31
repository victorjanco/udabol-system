<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 14/07/2017
 * Time: 04:19 PM
 */
class Function_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_menu()
    {
        $this->db->select('id');
        $this->db->from('menu');
        $this->db->where('parent is null');

        $res = $this->db->get()->result();
        $menu = [];
        foreach ($res as $row) {
            $list = [];
            $list['modules'] = $this->get_modules($row->id);
            $list['functions'] = $this->get_functions($row->id);
            $menu[] = $list;
        }
        return $menu;
    }

    /*------------------------------------------------------
     * Obtiene un modulo en especifico
     * **/
    public function get_modules($id)
    {
        return $this->db->get_where('menu', array('id' => $id))->row();
    }

    /*----------------------------------------------------
     * Obtiene todas las funciones de un modulo padre
     ***/
    public function get_functions($id_module)
    {
        $res = $this->db->get_where('menu', array('parent' => $id_module))->result();
        return $res;
    }

    /*
     * Metodo que devuelve las funciones que tiene
     * seleccionada el usuario.
     *
     * @id_user
     * */
    public function get_user_functions($id_user)
    {
        $this->db->select('m.id, m.name');
        $this->db->from('menu m, acceso a, usuario u');
        $this->db->where('m.id = a.menu_id');
        $this->db->where('a.usuario_id = u.id');
        $this->db->where('u.id',$id_user);
        return $this->db->get()->result();
    }
}