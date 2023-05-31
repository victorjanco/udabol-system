<?php

/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 26/08/2017
 * Time: 16:14
 */
class Purchase_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('company_model');
        $this->load->model('user_model');
        $this->load->model('provider_model');
        $this->load->model('office_model');
        $this->load->model('inventory_model');
        $this->load->model('product_model');
    }

    public function get_purchase_by_id($id)
    {
        return $this->db->get_where('compra', array('id' => $id))->row_array();
    }
    
    public function get_purchase_detail($id)
    {
        $this->db->select("d.*, p.codigo as producto_codigo, p.nombre_generico as producto_nombre");
        $this->db->from('detalle_compra d, producto p');
        $this->db->where('d.producto_id = p.id');
        $this->db->where("d.compra_id = $id");
        return $this->db->get()->result_array();
    }

    public function get_purchase_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('cmp.*, date(cmp.fecha_registro) as fecha_compra_registro, prv.nombre as nombre_proveedor')
            ->from('compra cmp, proveedor prv')
            ->where('cmp.proveedor_id = prv.id')
            ->where('cmp.sucursal_id ',get_branch_id_in_session());
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
        } else {
            $this->db->order_by('id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('nro_compra', strtolower($params['search']));
//            $this->db->like('lower(nit)', strtolower($params['search']));
//            $this->db->like('lower(contacto)', strtolower($params['search']));
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

    public function register_purchase()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
            $this->form_validation->set_rules('purchase_number', 'numero de la compra', 'trim|required');
            $this->form_validation->set_rules('purchase_type', 'tipo de la compra', 'trim|required');
            $this->form_validation->set_rules('purchase_provider_nit', 'nit del proveedor', 'trim|required');
            $this->form_validation->set_rules('purchase_provider_name', 'nombre del proveedor', 'trim|required');
            $this->form_validation->set_rules('purchase_description', 'glosa', 'trim|required');
            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');
            if ($this->form_validation->run() === FALSE) {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
                return $response;
            }

            $user = $this->session->userdata('user');
            $warehouse_id = $this->input->post("warehouse_id");

            // COMPRA
            $data = array(
                "tipo_compra" => $this->input->post("purchase_type"),
                "nro_compra" => $this->input->post("purchase_number"),
                "fecha_registro" => date('Y-m-d H:i:s'),
                "fecha_modificacion" => date('Y-m-d H:i:s'),
                "observacion" => $this->input->post("purchase_description"),
                "monto_subtotal" => $this->input->post("purchase_subtotal"),
                "descuento_uno" => $this->input->post("purchase_off1"),
                "descuento_dos" => $this->input->post("purchase_off2"),
                "descuento_tres" => $this->input->post("purchase_off3"),
                "monto_total" => $this->input->post("purchase_total"),
                "estado" => get_state_abm('ACTIVO'),
                "sucursal_id" => get_branch_id_in_session(),
                "usuario_id" => get_user_id_in_session(),
                "proveedor_id" => $this->input->post('purchase_provider_id'),
            );

            $this->db->trans_start();
            $this->db->insert('compra', $data);
            $purchase_id = $this->get_purchase($data)->id;

            // INGRESO INVENTARIO
            $data_entry_inventory = array(
                'nombre' => 'Ingreso inventario por Compras Nro. ' . $this->input->post("purchase_number"),
                'fecha_ingreso' => date('Y-m-d'),
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'estado' => ACTIVO,
                'tipo_ingreso_inventario_id' => 4, // ingreso inventario por compras
                'sucursal_id' => get_branch_id_in_session(),
                'usuario_id' => get_user_id_in_session(),
                'nro_ingreso_inventario' => $this->inventory_model->last_number_inventory_entry(),
                'nro_comprobante' => $this->input->post("purchase_number")
            );
            $this->db->insert('ingreso_inventario', $data_entry_inventory);
            $entry_inventory_id = $this->inventory_model->_get_inventory_entry($data_entry_inventory)->id;

            // Insertamos el detalle de la compra
            foreach ($this->input->post('detail_table') as $detail_purchase) {

                // DETALLE COMPRA
                $dataDetail = array(
                    "precio_unitario" => $detail_purchase['unit_price'],
                    "cantidad" => $detail_purchase['quantity'],
                    "monto_total" => $detail_purchase['total'],
                    "costo_adicional" => $detail_purchase['additional_cost'],
                    "costo_almacen" => $detail_purchase['storage_cost'],
                    "precio_costo" => $detail_purchase['cost_price'],
                    "precio_venta" => $detail_purchase['sale_price'],
                    "precio_venta_mayor" => $detail_purchase['sale_price'],
                    "cantidad_correcta" => $detail_purchase['quantity'], /*se carga la cantidad que se esta registrando*/
                    "cantidad_observada" => 0,
                    "cantidad_ingresada_inventario" => 0,
                    "observacion" => '',
                    "fecha_control" => date('Y-m-d'),
                    "fecha_registro" => date('Y-m-d H:i:s'),
                    "fecha_modificacion" => date('Y-m-d H:i:s'),
                    "estado" => ACTIVO,
                    "producto_id" => $detail_purchase['product_id'],
                    "compra_id" => $purchase_id,
                );
                $this->db->insert('detalle_compra', $dataDetail);
                $detail_purchase_inserted = $this->_get_detail_purchase($dataDetail);
                //INVENTARIO
                $data_inventory = array(
                    'codigo' => $this->input->post("purchase_number"),
                    'cantidad' => $detail_purchase['quantity'],
                    'precio_compra' => $detail_purchase['unit_price'],
                    'precio_costo' => $detail_purchase['cost_price'],
                    'precio_venta' => $detail_purchase['sale_price'],
                    'fecha_ingreso' => date('Y-m-d H:i:s'),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'estado' => ACTIVO,
                    'almacen_id' => $warehouse_id,
                    'ingreso_inventario_id' => $entry_inventory_id,
                    'producto_id' => $detail_purchase['product_id'],
                    'cantidad_ingresada' => $detail_purchase['quantity']
                );
                $this->db->insert('inventario', $data_inventory);

                $inventory_inserted = $this->db->get_where('inventario', $data_inventory)->row();
                $data_detail_purchase_inventory = array(
                    "detalle_compra_id" => $detail_purchase_inserted->id,
                    "inventario_id" => $inventory_inserted->id,
                );
                $this->db->insert('detalle_compra_inventario', $data_detail_purchase_inventory);
                /////////////////////////////////////////////////////////////////////////////////
                // $warehouse_id = $warehouse_array[$index];
                $branch_office_id = get_branch_id_in_session();
                $product_id = $detail_purchase['product_id'];
                $new_stock = $detail_purchase['quantity'];
                
                if ($this->product_model->exists_branch_office_inventory(get_branch_id_in_session(), $warehouse_id, $product_id) == 0) {
    
                    // Registra inventario sucursal
                    $data_branch_office_inventory = array(
                        'precio_compra' => $detail_purchase['cost_price'],
                        'precio_costo' => $detail_purchase['cost_price'],
                        'precio_costo_ponderado' =>$detail_purchase['cost_price'],
                        'precio_venta' => $detail_purchase['sale_price'],
                        'precio_venta_1' => $detail_purchase['sale_price'],
                        'precio_venta_2' => $detail_purchase['sale_price'],
                        'precio_venta_3' => $detail_purchase['sale_price'],
                        'porcentaje_precio_venta_3' => 0,
                        'stock' => $new_stock,
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'usuario_id' => get_user_id_in_session(),
                        'sucursal_registro_id' =>$branch_office_id,
                        'sucursal_id' => $branch_office_id,
                        'almacen_id' => $warehouse_id,
                        'producto_id' => $product_id,
                        'estado' => ACTIVO
                    );
                    $this->db->insert('inventario_sucursal', $data_branch_office_inventory);	
                }
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = FALSE;
            } else {
                $this->db->trans_commit();
				$this->inventory_model->update_product_in_inventory_branch_office($this->inventory_model->get_detail_inventory($entry_inventory_id));
                $response['success'] = TRUE;
                $response['messages'] = $purchase_id;
            }

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }
    
    public function update_purchase()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
            $this->form_validation->set_rules('purchase_number', 'numero de la compra', 'trim|required');
            $this->form_validation->set_rules('purchase_type', 'tipo de la compra', 'trim|required');
            $this->form_validation->set_rules('purchase_provider_nit', 'nit del proveedor', 'trim|required');
            $this->form_validation->set_rules('purchase_provider_name', 'nombre del proveedor', 'trim|required');
            $this->form_validation->set_rules('purchase_description', 'glosa', 'trim|required');
            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');
            if ($this->form_validation->run() === FALSE) {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
                return $response;
            }


            $purchase_id = $this->input->post('purchase_id');
            $data = array(
                "tipo_compra" => $this->input->post("purchase_type"),
                "nro_compra" => $this->input->post("purchase_number"),
                "fecha_modificacion" => date('Y-m-d'),
                "observacion" => $this->input->post("purchase_description"),
                "monto_subtotal" => $this->input->post("purchase_subtotal"),
                "descuento_uno" => $this->input->post("purchase_off1"),
                "descuento_dos" => $this->input->post("purchase_off2"),
                "descuento_tres" => $this->input->post("purchase_off3"),
                "monto_total" => $this->input->post("purchase_total"),
                "sucursal_id" => get_branch_id_in_session(),
                "usuario_id" => get_user_id_in_session(),
            );

            $this->db->trans_start();

            $where = array('id' => $purchase_id);
            $this->db->update('compra', $data, $where);

            $where = array('compra_id' => $purchase_id);
            $this->db->delete('detalle_compra', $where);

            // Insertamos el detalle de la compra
            foreach ($this->input->post('detail_table') as $detail_purchase) {
                $dataDetail = array(
                    "precio_unitario" => $detail_purchase['unit_price'],
                    "cantidad" => $detail_purchase['quantity'],
                    "monto_total" => $detail_purchase['total'],
                    "costo_adicional" => $detail_purchase['additional_cost'],
                    "costo_almacen" => $detail_purchase['storage_cost'],
                    "precio_costo" => $detail_purchase['cost_price'],
                    "precio_venta" => $detail_purchase['sale_price'],
                    "precio_venta_mayor" => $detail_purchase['sale_price'],
                    "cantidad_correcta" => $detail_purchase['quantity'], /*se carga la cantidad que se esta registrando*/
                    "cantidad_observada" => 0,
                    "cantidad_ingresada_inventario" => 0,
                    "observacion" => '',
                    "fecha_control" => date('Y-m-d'),
                    "fecha_registro" => date('Y-m-d H:i:s'),
                    "fecha_modificacion" => date('Y-m-d H:i:s'),
                    "estado" => ACTIVO,
                    "producto_id" => $detail_purchase['product_id'],
                    "compra_id" => $purchase_id,
                );
                $this->db->insert('detalle_compra', $dataDetail);
            }

            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $response['success'] = FALSE;
            } else {
                $this->db->trans_commit();
                $response['success'] = TRUE;
                $response['messages'] = $purchase_id;
            }

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }
    
    public function disable_purchase()
    {
        $id = $this->input->post('id');
       
        $detail_purchase_inventory_array = $this->get_purchase_detail_inventory($id);
        foreach ($detail_purchase_inventory_array as $row) {
            $data_purchase['estado']=ANULADO;
            $this->db->update('detalle_compra',$data_purchase, ['id' => $row->detalle_compra_id]);

            $data['cantidad']=0;
            $data['cantidad_ingresada']=0;
            $this->db->update('inventario',$data, ['id' => $row->inventario_id]);
        }

		$this->inventory_model->update_average_price_especific_branch_office();
		return $this->db->update(
			'compra',
			['estado' => get_state_abm('INACTIVO')],
			['id' => $id]
		);
    }

    public function get_data_print($purchase_id){/* Metodo que devuelve una compra con los datos detallados para imprimir*/
        $purchase = $this->purchase_model->get_purchase_by_id($purchase_id);
        $detail = $this->purchase_model->get_purchase_detail($purchase_id);
        $provider = $this->provider_model->get_provider_by_id($purchase["proveedor_id"]);
        $data['purchase'] = $purchase;
        $data['detail'] = $detail;
        $data['provider'] = $provider;
        $data['company'] = $this->company_model->get_company();
        //$data['user'] = $this->user_model->get_user_id($purchase->usuario_id);
        $data['branch_office'] = $this->office_model->get_branch_office_id($purchase['sucursal_id']);
       // echo $purchase;
        return $data;
    }

    public function get_purchase($data)
    {
        return $this->db->get_where('compra', $data)->row();
    }

    public function _get_detail_purchase($detail_purchase)
	{
		return $this->db->get_where('detalle_compra', $detail_purchase)->row();
    }
    
    public function get_purchase_detail_inventory($id)
    {
        $this->db->select("dci.*");
        $this->db->from('detalle_compra dc, detalle_compra_inventario dci');
        $this->db->where('dc.id = dci.detalle_compra_id');
        $this->db->where("dc.compra_id = $id");
        return $this->db->get()->result();
    }
}
