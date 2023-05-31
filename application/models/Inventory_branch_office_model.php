<?php

/**
 * Created by PhpStorm.
 * User: Gustavo Cisneros
 * Date: 20/07/2017
 * Time: 08:20 PM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Inventory_branch_office_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('provider_model');
		$this->load->model('warehouse_model');
		$this->load->model('product_model');
	}

	/*Obtener datos del tipo almacen apartir del caja_id*/
	public function find($inventory_branch_office_id)
	{
		return $this->db->get_where('inventario_sucursal', array('id' => $inventory_branch_office_id, 'estado' => ACTIVO))->row();
	}
	
}
