<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 20/07/2017
 * Time: 06:18 PM
 */

class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('proveider_model');
        $this->load->model('model_model');
        $this->load->model('group_model');
        //$this->load->model('category_model');
        $this->load->model('unit_model');
        $this->load->model('office_model');
        $this->load->model('warehouse_model');
    }

    public function find($id)
	{
		return $this->db->get_where('producto', array( 'id' => $id))->row();
	}
    public function activate_product()
	{
		// $id = $this->input->post('id');
		// $res = $this->get_activated_dosage_by_id($asignacion_dosficacion_id);
		// if ($res == false) {
			$id = $this->input->post('id');
			return $this->db->update(
				'producto',
				['estado' => ACTIVO,
                'user_updated' => get_user_id_in_session(),
                'fecha_modificacion' => date('Y-m-d H:i:s')],
				['id' => $id]
			);
		// }
    }
    public function delete_product()
	{
        $id = $this->input->post('id');
        return $this->db->update(
            'producto',
            ['estado' => ELIMINADO,
            'user_updated' => get_user_id_in_session(),
            'fecha_modificacion' => date('Y-m-d H:i:s')],
            ['id' => $id]
        );
	}
    public function get_product_enable($type = 'object')
    {
        //return $this->db->get_where('producto', array('estado' => get_state_abm('ACTIVO'), 'id!=' => $this->get_producto_type_service()->id))->result($type);
        return $this->db->get_where('producto', array('estado' => get_state_abm('ACTIVO'), 'id!=' => isset($this->get_producto_type_service()->id)? $this->get_producto_type_service()->id:0))->result($type);
    }

	public function get_current_average_cost($warehouse_id, $product_id)
	{
		return $this->db->get_where('inventario_sucursal', array( 'almacen_id=' => $warehouse_id,'producto_id=' => $product_id))->row();
	}
    

    public function get_count_product_minium_stock()
    {
        $this->db->select('count(pr.id) as stock_minimo');
        $this->db->from('producto pr , inventario_stock_general inv');
        $this->db->where('pr.id = inv.producto_id');
        $this->db->where('inv.sucursal_id', get_branch_id_in_session());
        $this->db->where('pr.stock_minimo >= inv.stock');
        return $this->db->get()->row();
    }

    /*
     * Metodo para registrar un nuevo producto
     * */
    public function





	register_product()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
            $this->form_validation->set_rules('codigo', 'codigo', 'trim|required|is_unique[producto.codigo]');
            $this->form_validation->set_rules('comercial', 'nombre comercial', 'trim|required');
            // $this->form_validation->set_rules('generico', 'nombre generico', 'trim');
            $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required');
            $this->form_validation->set_rules('precio_costo', 'precio costo', 'trim|required');
            $this->form_validation->set_rules('minimum_stock', 'Stock minimo', 'trim|required');
            $this->form_validation->set_rules('percent_commission', 'Porcentaje comision', 'trim|required');
            // $this->form_validation->set_rules('grupo', 'Debe seleccionar una marca por favor', sprintf("trim|required|in_list[%s]", implode_array($this->group_model->get_groups(), 'id')));
            $this->form_validation->set_rules('modelo', 'modelo', sprintf("trim|required|in_list[%s]", implode_array($this->model_model->get_model_enable(), 'id')));
            // $this->form_validation->set_rules('medida', 'medida', sprintf("trim|required|in_list[%s]", implode_array($this->unit_model->get_units(), 'id')));
            // $this->form_validation->set_rules('serie', 'serie', sprintf("trim|required|in_list[%s]", implode_array($this->serie_model->get_all_serie(), 'id')));
            $this->form_validation->set_rules('proveedores[]', 'proveedor', sprintf("trim|required|in_list[%s]", implode_array($this->proveider_model->get_proveiders(), 'id')));

            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');
            $serie = $this->serie_model->first();
            $group = $this->group_model->first();
            $unit_measure = $this->unit_model->first();

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                $provider_array = $this->input->post('proveedores');
                $codigo=$this->input->post('codigo');
                /** OBTENERMOS VALORES DE LOS INPUT **/
                $obj_product['codigo'] = $codigo;
                $obj_product['nombre_comercial'] = strtoupper($this->input->post('comercial'));
                $obj_product['nombre_generico'] = strtoupper($this->input->post('comercial'));
                $obj_product['dimension'] = strtoupper($this->input->post('dimension'));
                $obj_product['precio_venta'] = $this->input->post('precio_venta');
                $obj_product['precio_compra'] = $this->input->post('precio_costo');
                $obj_product['precio_venta_mayor'] = $this->input->post('precio_venta');
                // $obj_product['precio_venta_express'] = $this->input->post('express_price');
                // $obj_product['precio_venta_laboratorio'] = $this->input->post('lab_price');
                $obj_product['precio_venta_express'] = $this->input->post('precio_venta');
                $obj_product['precio_venta_laboratorio'] = $this->input->post('precio_venta');
                $obj_product['porcentaje_precioventa_laboratorio'] = $this->input->post('percent_price') / 100;
                $obj_product['stock_minimo'] = round($this->input->post('minimum_stock'));
                $obj_product['fecha_registro'] = date('Y-m-d');
                $obj_product['fecha_modificacion'] = date('Y-m-d');
                $obj_product['estado'] = ACTIVO;
                // $obj_product['grupo_id'] = $this->input->post('grupo');
                // $obj_product['subgrupo_id'] = $this->input->post('subgrupo');
                $obj_product['grupo_id'] = $group->id;
                $obj_product['subgrupo_id'] = $group->id;
                $obj_product['modelo_id'] = $this->input->post('modelo');
                // $obj_product['unidad_medida_id'] = $this->input->post('medida');
                $obj_product['unidad_medida_id'] = $unit_measure->id;
                $obj_product['user_created'] = get_user_id_in_session();
                $obj_product['user_updated'] = get_user_id_in_session();
                $obj_product['tipo_producto_id'] = 1;
                // $obj_product['serie_id'] = $this->input->post('serie');
                $obj_product['serie_id'] = $serie->id;
                $obj_product['porcentaje_comision'] = $this->input->post('percent_commission') / 100;
                //$obj_product['imei1'] = $this->input->post('imei1');
                //$obj_product['imei2'] = $this->input->post('imei2');
                

                $this->db->insert('producto', $obj_product);
                $product_inserted = $this->db->get_where('producto', $obj_product)->row();
                /***********************            Insertamos los proveedores seleccionados  **********************************/
                $length_provider = count($provider_array);
                for ($index = 0; $index < $length_provider; $index++) {
                    $provider_id = $provider_array[$index];
                    $producto_provedor['producto_id'] = $product_inserted->id;
                    $producto_provedor['proveedor_id'] = $provider_id;
                    $this->db->insert('producto_proveedor', $producto_provedor);
                }

                $list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
                // foreach ($list_warehouse as $warehouse) {
                //     $warehouse_id = $warehouse->id;

                //     $data_branch_office_inventory = array(
                //         'precio_compra' => $this->input->post('precio_costo'),
                //         'precio_costo' => $this->input->post('precio_costo'),
                //         'precio_costo_ponderado' => $this->input->post('precio_costo'),
                //         'precio_venta' => $this->input->post('precio_venta'),
                //         'stock' => 0,
                //         'fecha_modificacion' => date('Y-m-d H:i:s'),
                //         'usuario_id' => get_user_id_in_session(),
                //         'sucursal_registro_id' => get_branch_id_in_session(),
                //         'sucursal_id' => get_branch_id_in_session(),
                //         'almacen_id' => $warehouse_id,
                //         'producto_id' => $product_inserted->id,
                //         'precio_venta_1' => $this->input->post('higher_price'),
                //         'precio_venta_2' => $this->input->post('express_price'),
                //         'precio_venta_3' => $this->input->post('lab_price'),
                //         'porcentaje_precio_venta_3' => $this->input->post('percent_price') / 100
                //     );
                //     $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
                // }
                
                // include APPPATH . '/libraries/barcode.php';
                // $barcode = new Barcode($codigo, 4, '');
                // imagepng($barcode->image(), DIRECTORY_RAIZ_PATH_BARCODE. $codigo . ".png");

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = false;
                } else {
                    $this->db->trans_commit();
                    $response['success'] = true;
                    // Se crea la carpeta para almacenar imagenes de productos
                    //$this->create_directory_product($product_inserted->id);
                }

            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }

    /*
     * Metodo para editar un producto seleccionado
     * */
    public function modify_product()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            $id_product = $this->input->post('id_product');
    
            if (verify_session()) {
    
                /* VALIDACION DEL LOS CAMPOS DEL FORMULARIO */
                $this->form_validation->set_rules('codigo', 'codigo', sprintf('trim|required|is_unique_edit[%u, producto, codigo]', $id_product));
                $this->form_validation->set_rules('comercial', 'nombre comercial', 'trim|required');
                $this->form_validation->set_rules('generico', 'nombre generico', 'trim');
                $this->form_validation->set_rules('precio_venta', 'precio venta', 'trim|required');
                $this->form_validation->set_rules('precio_costo', 'precio costo', 'trim|required');
                // $this->form_validation->set_rules('grupo', 'Debe seleccionar una marca por favor', sprintf("trim|required|in_list[%s]", implode_array($this->group_model->get_groups(), 'id')));
                $this->form_validation->set_rules('grupo', 'grupo', 'trim|required');
                $this->form_validation->set_rules('subgrupo', 'subgrupo', 'trim|required');
                $this->form_validation->set_rules('modelo', 'modelo', sprintf("trim|required|in_list[%s]", implode_array($this->model_model->get_model_enable(), 'id')));
                $this->form_validation->set_rules('medida', 'medida', sprintf("trim|required|in_list[%s]", implode_array($this->unit_model->get_units(), 'id')));
                $this->form_validation->set_rules('proveedores[]', 'proveedor', sprintf("trim|required|in_list[%s]", implode_array($this->proveider_model->get_proveiders(), 'id')));
    
                $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');
    
                if ($this->form_validation->run() === true) {
    
                    $this->db->trans_start();
                    /** OBTENERMOS VALORES DE LOS INPUT **/
                    $provider_array = $this->input->post('proveedores');
    
                    $obj_product['codigo'] = strtoupper($this->input->post('codigo'));
                    $obj_product['nombre_comercial'] = strtoupper($this->input->post('comercial'));
                    $obj_product['nombre_generico'] = strtoupper($this->input->post('generico'));
                    $obj_product['dimension'] = strtoupper($this->input->post('dimension'));
                    $obj_product['precio_venta'] = $this->input->post('precio_venta');
                    $obj_product['precio_compra'] = $this->input->post('precio_costo');
    
                    $obj_product['precio_venta_mayor'] = $this->input->post('higher_price');
                    $obj_product['precio_venta_express'] = $this->input->post('express_price');
                    $obj_product['precio_venta_laboratorio'] = $this->input->post('precio_venta');
                    $obj_product['porcentaje_precioventa_laboratorio'] = $this->input->post('percent_price') / 100;
                    $obj_product['fecha_modificacion'] = date('Y-m-d');
                    $obj_product['grupo_id'] = $this->input->post('grupo');
                    $obj_product['subgrupo_id'] = $this->input->post('subgrupo');
                    $obj_product['modelo_id'] = $this->input->post('modelo');
                    $obj_product['unidad_medida_id'] = $this->input->post('medida');
                    // $obj_product['usuario_id'] = get_user_id_in_session();
                    $obj_product['serie_id'] = $this->input->post('serie');
                    $obj_product['porcentaje_comision'] = $this->input->post('percent_commission') / 100;
                    //$obj_product['imei1'] = $this->input->post('imei1');
                    //$obj_product['imei2'] = $this->input->post('imei2');
                    $obj_product['user_updated'] = get_user_id_in_session();
    
                    $this->db->where('id', $id_product);
                    $this->db->update('producto', $obj_product);
    
                    /***********************            Insertamos los proveedores seleccionados  **********************************/
                    $this->db->where('producto_id', $id_product);
                    $this->db->delete('producto_proveedor'); /* Eliminamos del listado de producto_proveedor*/
    
                    $length_provider = count($provider_array);
                    for ($index = 0; $index < $length_provider; $index++) {
                        $provider_id = $provider_array[$index];
                        $producto_provedor['producto_id'] = $id_product;
                        $producto_provedor['proveedor_id'] = $provider_id;
                        $this->db->insert('producto_proveedor', $producto_provedor);
                    }
                    $this->update_price_product_all_branch($id_product);

                    $this->db->trans_complete();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $response['success'] = false;
                    } else {
                        $this->db->trans_commit();
                        $response['success'] = true;
                    }
                } else {
                    foreach ($_POST as $key => $value) {
                        $response['messages'][$key] = form_error($key);
                    }
                }
    
            } else {
                $response['login'] = TRUE;
            }
    
            return $response;
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
       
    }

    private function update_price_product_all_branch($product_id)
    {
        $product = $this->find($product_id);
        $data_inventory['precio_compra'] = $product->precio_compra;
        $data_inventory['precio_costo'] = $product->precio_compra;
        $data_inventory['precio_venta'] = $product->precio_venta;
        $data_inventory['user_updated'] = get_user_id_in_session();

        $data_branch['precio_compra'] = $product->precio_compra;
        $data_branch['precio_costo'] = $product->precio_compra;
        $data_branch['precio_venta'] = $product->precio_venta;
        $data_branch['precio_venta_1'] = $product->precio_venta_mayor;
        $data_branch['precio_venta_2'] = $product->precio_venta;

        $this->db->where('producto_id', $product_id);
        $this->db->where('estado', ACTIVO);
        $this->db->update('inventario', $data_inventory);

        $this->db->where('producto_id', $product_id);
        $this->db->where('estado', ACTIVO);
        return $this->db->update('inventario_sucursal', $data_branch);

    }

    /*
     * Metodo para dar de baja los productos
     * */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('producto', array('estado' => get_state_abm('INACTIVO')));
    }


    /* Metodo para obtener el listado de los productos usando
     * datatable server proccesing
     * */
    public function get_products_list($start, $length, $search, $column, $order)
    {
        $this->db->start_cache();
        $this->db->select('p.id, p.codigo, p.nombre_comercial, p.nombre_generico, p.dimension, p.precio_venta, p.estado, m.nombre as modelo, grupo.nombre as grupo, subgrupo.nombre as subgrupo, ma.nombre as marca');
        $this->db->from('producto p, modelo m, grupo grupo, grupo subgrupo, marca ma');
        $this->db->where('p.modelo_id = m.id');
        $this->db->where('p.tipo_producto_id = 1');
        $this->db->where('p.grupo_id = grupo.id');
        // $this->db->where('a.grupo_hijo_id = subgrupo.id');
        $this->db->where('p.subgrupo_id = subgrupo.id');
        $this->db->where('m.marca_id = ma.id');
        // $this->db->where('p.sucursal_id', get_branch_id_in_session());
        $this->db->where('p.estado', ACTIVO);
        $this->db->stop_cache();
        $count_products = count($this->db->get()->result());
//        $data_products = $this->db->get()->result();
        if (isset($start) && isset($length)) {
            $this->db->limit($length);
            $this->db->offset($start);
        }
        if (isset($column) && isset($order)) {
            $this->db->order_by($column, $order);
        } else {
            $this->db->order_by('p.id', 'ASC');
        }


        if ($search != '') {
            $this->db->group_start();
//            $array = array('lower(p.codigo)'=>strtolower($search),'lower(p.nombre_generico)'=> strtolower($search));
            $this->db->like('lower(p.codigo)', strtolower($search));
            $this->db->or_like('lower(p.nombre_comercial)', strtolower($search));
            $this->db->or_like('lower(p.nombre_generico)', strtolower($search));
            $this->db->or_like('lower(p.dimension)', strtolower($search));
            $this->db->or_like('lower(p.imei1)', strtolower($search));
            $this->db->or_like('lower(p.imei2)', strtolower($search));
            $this->db->or_like('lower(m.nombre)', strtolower($search));
            $this->db->or_like('lower(grupo.nombre)', strtolower($search));
            $this->db->or_like('lower(subgrupo.nombre)', strtolower($search));
            $this->db->or_like('lower(ma.nombre)', strtolower($search));
            $this->db->group_end();
        }

        $response = $this->db->get();
        $result = array(
            'total_register' => $count_products,
            'data_products' => $response,
        );
        return $result;
    }

    public function get_products_inactive_list($start, $length, $search, $column, $order)
    {
        $this->db->start_cache();
        $this->db->select('p.id, p.codigo, p.nombre_comercial, p.nombre_generico, p.dimension, p.precio_venta, p.estado, m.nombre as modelo, grupo.nombre as grupo, subgrupo.nombre as subgrupo, ma.nombre as marca');
        $this->db->from('producto p, modelo m, grupo grupo, grupo subgrupo, marca ma');
        $this->db->where('p.modelo_id = m.id');
        $this->db->where('p.tipo_producto_id = 1');
        $this->db->where('p.grupo_id = grupo.id');
        // $this->db->where('a.grupo_hijo_id = subgrupo.id');
        $this->db->where('p.subgrupo_id = subgrupo.id');
        $this->db->where('m.marca_id = ma.id');
        $this->db->where('p.estado', ANULADO);
        $this->db->stop_cache();
        $count_products = count($this->db->get()->result());
//        $data_products = $this->db->get()->result();
        if (isset($start) && isset($length)) {
            $this->db->limit($length);
            $this->db->offset($start);
        }
        if (isset($column) && isset($order)) {
            $this->db->order_by($column, $order);
        } else {
            $this->db->order_by('p.id', 'ASC');
        }


        if ($search != '') {
            $this->db->group_start();
//            $array = array('lower(p.codigo)'=>strtolower($search),'lower(p.nombre_generico)'=> strtolower($search));
            $this->db->like('lower(p.codigo)', strtolower($search));
            $this->db->or_like('lower(p.nombre_comercial)', strtolower($search));
            $this->db->or_like('lower(p.nombre_generico)', strtolower($search));
            $this->db->or_like('lower(p.dimension)', strtolower($search));
            $this->db->or_like('lower(m.nombre)', strtolower($search));
            $this->db->or_like('lower(grupo.nombre)', strtolower($search));
            $this->db->or_like('lower(subgrupo.nombre)', strtolower($search));
            $this->db->or_like('lower(ma.nombre)', strtolower($search));
            $this->db->group_end();
        }

        $response = $this->db->get();
        $result = array(
            'total_register' => $count_products,
            'data_products' => $response,
        );
        return $result;
    }
    /*
     * Metodo para obtener un producto por su id
     * */
    public function get_product_by_id($id)
    {
        // $this->db->select('p.*,
        // m.id as modelo_id, m.nombre as modelo, g.id as grupo_id, p.stock_minimo, 
        // g.nombre as grupo, u.id as unidad_id, u.nombre as medida,pr.id as proveedor_id, pr.nombre as proveedor, m.marca_id,
        // s.id as serie_id, s.nombre as nombre_serie')
        //     ->from('producto p, modelo m, grupo g, unidad_medida u, proveedor pr, producto_proveedor pprov, serie s')
        //     ->where('p.modelo_id = m.id')
        //     ->where('p.grupo_id = g.id')
        //     ->where('p.unidad_medida_id = u.id')
        //     ->where('p.id = pprov.producto_id')
        //     ->where('pr.id = pprov.proveedor_id')
        //     ->where('p.serie_id = s.id')
        //     ->where('p.id', $id);
        $this->db->select('p.*,
        m.id as modelo_id, m.nombre as modelo, g.id as grupo_id, p.stock_minimo, 
        g.nombre as grupo, u.id as unidad_id, u.nombre as medida,pr.id as proveedor_id, pr.nombre as proveedor, m.marca_id,
        s.id as serie_id, s.nombre as nombre_serie, ma.nombre as nombre_marca')
            ->from('producto p, modelo m, grupo g, unidad_medida u, proveedor pr, producto_proveedor pprov, serie s, marca ma')
            ->where('p.modelo_id = m.id')
            ->where('p.grupo_id = g.id')
            ->where('p.unidad_medida_id = u.id')
            ->where('p.id = pprov.producto_id')
            ->where('pr.id = pprov.proveedor_id')
            ->where('p.serie_id = s.id')
            ->where('m.marca_id = ma.id')
            ->where('p.id', $id);
        return $this->db->get()->row();
    }

    public function get_product_print_id($id)
    {
        $this->db->select('*')
            ->from('vista_lista_producto')
            ->where('producto_id', $id);
        return $this->db->get()->row();
    }
    public function get_marca_by_id($id)
    {
        $this->db->select('*')
            ->from('marca ')
            ->where('id', $id);
        return $this->db->get()->row();
    }

    /*
     * Metodo para obtener todos los productos activos
     * */
    public function get_products($type = 'object')
    {
        return $this->db->get_where('producto', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*
     * Metodo para obtener los productos y modelos de una marca en especifico
     * **/
    public function get_producto_and_model_by_brand($id_brand)
    {
        $this->db->select('mod.id, mod.nombre as modelo')
            ->from('modelo mod, marca mar')
            ->where('mod.marca_id = mar.id')
            ->where('mod.estado', ACTIVO)
            ->where('mar.id', $id_brand)
            ->order_by('mod.id', 'ASC');
        return $this->db->get()->result();
    }

    // Obtener producto por autocompletado
    public function get_code_name_product_autocomplete()
    {
        $name_startsWith = $this->input->post('name_startsWith');
        $type = $this->input->post('type');

        switch ($type) {
            case 'code':
                $this->db->start_cache();
                $this->db->select('*')
                    ->from('producto')
                    ->where('tipo_producto_id', 1)
                    ->where('estado', get_state_abm('ACTIVO'))
                    ->group_start()
                    ->like('lower(codigo)', strtolower($name_startsWith))
                    ->limit(12)
                    ->group_end();
                $this->db->stop_cache();

                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    foreach ($result->result_array() as $row) {
                        $data[$row['codigo'] . '/' . $row['nombre_comercial'] .' - '. $row['nombre_generico']] =
                            $row['id'] . '/' .
                            $row['nombre_comercial'] .' - '. $row['nombre_generico']. '/' .
                            number_format($row['precio_venta'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                            number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '');
                    };
                    return $data;
                } else {
                    $data["No existen datos"] = "No existe datos";
                    return $data;
                }
                break;

            case 'name':
                $this->db->start_cache();
                $this->db->select('vlp.*, subgrupo.nombre as subgrupo_name')
                    ->from('vista_lista_producto vlp, grupo subgrupo')
                    ->where('vlp.subgrupo_id=subgrupo.id')
                    ->where('vlp.tipo_producto_id', 1)
                    ->where('vlp.estado_producto', get_state_abm('ACTIVO'))
                    ->group_start()
                    ->like('lower(vlp.nombre_comercial)', strtolower($name_startsWith))
                    ->or_like('lower(vlp.nombre_generico)', strtolower($name_startsWith))
                    ->or_like('lower(vlp.nombre_modelo)', strtolower($name_startsWith))
                    ->limit(12)
                    ->group_end();
                $this->db->stop_cache();

                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    foreach ($result->result_array() as $row) {
                        $data[$row['codigo_producto'] . '/' . $row['nombre_comercial'].' - '. $row['nombre_generico'].' - '. $row['dimension'].' - '. $row['subgrupo_name']] =
                            $row['producto_id'] . '/' .
                            $row['nombre_comercial'] . '/' .
                            number_format($row['precio_venta'], CANTIDAD_MONTO_DECIMAL, '.', '') . '/' .
                            number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL, '.', '');
                    };
                    return $data;
                } else {
                    $data["No existen datos"] = "No existe datos";
                    return $data;
                }
                break;

            case 'bar_code':
                $this->db->select('*')
                    ->from('producto')
                    ->where('estado', get_state_abm('ACTIVO'))
                    ->group_start()
                    ->where('codigo', $name_startsWith)
                    ->group_end();

                $result = $this->db->get();
                $data = $result->row();

                if ($result->num_rows() > 0) {
                    $data = $result->row();
                    $data->validacion = 1;
                    return $data;
                } else {
                    $data["validacion"] = 0;
                    return $data;
                }

                break;
        }

    }

    /* Retorna un producto con parametro id, sin relacionar con ninguna tabla*/
    public function get_product_entity_by_id($product_id)
    {
        $data = $this->db->get_where('producto', array('id' => $product_id))->row();
        return $data;
    }

    public function get_producto_type_service()
    {
        $data = $this->db->get_where('producto', array('tipo_producto_id' => 2))->row();
        return $data;
    }

    public function search_product($search)
    {
        $this->db->group_start();
        $this->db->like('lower(nombre_comercial)', strtolower($search));
        $this->db->or_like('lower(nombre_generico)', strtolower($search));
        $this->db->or_like('lower(codigo_producto)', strtolower($search));
        $this->db->limit(12);
        $this->db->group_end();
        $this->db->where('estado_producto', ACTIVO);
        $res = $this->db->get('vista_lista_producto');
        $data = array();
        if ($res->num_rows() > 0) {
            foreach ($res->result_array() as $row) {
                $data[$row['producto_id']] = $row['codigo_producto'] . '|' . $row['nombre_generico'] . ' - ' . $row['dimension'] . ' - ' . $row['nombre_marca'] . '|' . 
                    number_format($row['precio_compra'], CANTIDAD_MONTO_DECIMAL) . '|' . number_format($row['precio_venta'], CANTIDAD_MONTO_DECIMAL) . '|' . number_format($row['precio_venta_mayor'], CANTIDAD_MONTO_DECIMAL);
            }
        }
        return $data;
    }

    public function get_model_by_model()
    {
        $model_id = intval($this->input->post("model_id"));
        $this->db->select('pro.nombre_comercial, pro.id')
            ->from('producto pro, modelo mod')
            ->where('pro.modelo_id = mod.id');
        if ($model_id != 0) {
            $this->db->where('modelo_id', $model_id);
        }
        $this->db->where('pro.estado', 1);
        return $this->db->get()->result();
    }

    /* Crea un directorio para almacenar las imagenes de producto */
    public function create_directory_product($product_id)
    {
        $directory = DIRECTORY_RAIZ_PATH_PRODUCT;

        $file_directory = DIRECTORY_RAIZ_PATH_PRODUCT . $product_id;

        //verificamos si la carpeta galeria_recepcion se creo
        if (!is_dir($directory)) {
            mkdir($directory, 0777);
        }

        //verificamos si el directorio ya existe
        if (!is_dir($file_directory)) {
            mkdir($file_directory, 0777);
        }
    }

    /* Registrar la Imagen (BD=>imagen_producto) con atributos de id_producto y nombre de la imagen */
    public function register_image_product($producto_id, $name_image)
    {
        $imagen_producto['nombre'] = $name_image;
        $imagen_producto['url'] = base_url() . 'product_gallery/' . $producto_id . '/' . $name_image;
        $imagen_producto['fecha_registro'] = date('Y-m-d H:i:s');
        $imagen_producto['estado'] = ACTIVO;
        $imagen_producto['producto_id'] = $producto_id;
        $imagen_producto['sucursal_id'] = get_branch_id_in_session();
        $imagen_producto['user_created'] = get_user_id_in_session();
        $this->db->insert('imagen_producto', $imagen_producto);

        $image_product_inserted = $this->_get_image_product($imagen_producto);
		$image_product_id = $image_product_inserted->id;

        return $image_product_id;
    }

    public function _get_image_product($image_product)
	{
		return $this->db->get_where('imagen_producto', $image_product)->row();
	}

    public function get_images_product($product_id)
    {
        $this->db->select('*')
            ->from('imagen_producto')
            ->where('producto_id', $product_id);
        $data = $this->db->get()->result();
        $html_image = '';
        foreach ($data as $row_data):
            $html_image .= '<div class="col-md-3" id="div_' . $row_data->id . '">
                                <img class="img-responsive" width="100%" height="100%" src="' . $row_data->url . '">
                                <input type="text" class="btn form-control btn-danger" onclick="delete_product_photo(' . $row_data->id . ')" value="Eliminar' . $row_data->id . '">
                            </div>';
        endforeach;
        return $html_image;
    }

    public function delete_image_product($image_id)
    {
        $this->db->where('id', $image_id);
        return $this->db->delete('imagen_producto');
    }

    public function get_all_products()
    {
        $this->db->select('*')
            ->from('vista_lista_producto')
            ->where('estado_producto', ACTIVO)
            ->order_by('producto_id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_products_type_product()
    {
        $this->db->select('*')
            ->from('vista_lista_producto')
            ->where('estado_producto', ACTIVO)
            ->where('tipo_producto_id', 1) //TIPO PRODUCTO
            ->order_by('producto_id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_type_reason($tipo_motivo)
    {
        $this->db->select('*')
            ->from('tipo_motivo')
            ->where('tipo', $tipo_motivo);
        return $this->db->get()->result();
    }

    public function get_type_reason_by_id($id)
    {
        $this->db->select('*')
            ->from('tipo_motivo')
            ->where('id', $id);
        return $this->db->get()->row();
    }

    public function register_product_reason()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            $this->form_validation->set_rules('type_reason', 'tipo de motivo', 'trim|required');
            $this->form_validation->set_error_delimiters('<label class="abm-error">', '</label>');

            if ($this->form_validation->run() === true) {

                $this->db->trans_start();
                $product_id = $this->input->post('product_id');

                $obj_product_reason['observacion'] = strtoupper($this->input->post('observations'));
                $obj_product_reason['estado'] = ACTIVO;
                $obj_product_reason['tipo_motivo_id'] = $this->input->post('type_reason');
                $obj_product_reason['producto_id'] = $product_id;
                $obj_product_reason['fecha_registro'] = date('Y-m-d H:i:s');
                $obj_product_reason['user_created'] = get_user_id_in_session();
                $obj_product_reason['sucursal_id'] = get_branch_id_in_session();

                $this->_insert_product_reason($obj_product_reason);

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response['success'] = false;
                } else {
                    $this->db->trans_commit();
                    $response['success'] = true;
                }

            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }

        } else {
            $response['login'] = TRUE;
        }

        return $response;
    }

    private function _insert_product_reason($product_reason)
    {
        return $this->db->insert('no_recepcionado', $product_reason);
    }

    public function exists_product($product_id)
    {
        $this->db->select('*')
            ->from('producto')
            ->where('id', $product_id)
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

    public function exists_code_product($code_product)
    {
        $this->db->select('*')
            ->from('producto')
            ->where('codigo', $code_product)
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

    // Metodo generico para registrar o actualizar Inventario Sucursal
    // $params -> Son los atributos de la tabla inventario sucursal
    public function register_update_branch_office_inventory($params = array())
    {
        $product_id = $params["producto_id"];

        $list_branch_office = $this->office_model->get_offices();
        foreach ($list_branch_office as $branch_office) {
            $branch_office_id = $branch_office->id;

            $list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
            foreach ($list_warehouse as $warehouse) {
                $warehouse_id = $warehouse->id;

                if ($this->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) {
                    $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);
                    // Registra inventario sucursal
                    $data_branch_office_inventory = array(
                        'precio_compra' => $params["precio_compra"],
                        'precio_costo' => $params["precio_costo"],
                        'precio_costo_ponderado' => $params["precio_costo_poderado"],
                        'precio_venta' => $params["precio_venta"],
                        'stock' => $new_stock,
                        'fecha_modificacion' => $params["fecha_modificacion"],
                        'usuario_id' => $params["usuario_id"],
                        'sucursal_registro_id' => $params["sucursal_registro_id"],
                        'sucursal_id' => $branch_office_id,
                        'almacen_id' => $warehouse_id,
                        'producto_id' => $product_id,
                        'estado' => ACTIVO
                    );

                    $this->db->insert('inventario_sucursal', $data_branch_office_inventory);

                    $data_product = array(
                        /*'precio_venta' => $params["precio_venta"],
                        'precio_venta_mayor' => $params["precio_venta_mayor"],
                        'precio_venta_express' => $params["precio_venta_express"],
                        'precio_venta_laboratorio' => $params["precio_venta_laboratorio"],*/
                        'nombre_comercial' => $params["nombre_comercial"],
                        'nombre_generico' => $params["nombre_generico"],
                        'fecha_modificacion' => $params["fecha_modificacion"]
                    );
                    $this->db->where('id', $product_id);
                    $this->db->update('producto', $data_product);

                } else {

                    $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                    // Actualiza inventario sucursal
                    $data_branch_office_inventory = array(
                        'precio_compra' => $params["precio_compra"],
                        'precio_costo' => $params["precio_costo"],
                        'precio_costo_ponderado' => $params["precio_costo_poderado"],
                        'precio_venta' => $params["precio_venta"],
                        'stock' => $new_stock,
                        'fecha_modificacion' => $params["fecha_modificacion"],
                        'usuario_id' => $params["usuario_id"],
                        'sucursal_registro_id' => $params["sucursal_registro_id"],
                        'sucursal_id' => $branch_office_id,
                        'almacen_id' => $warehouse_id,
                        'producto_id' => $product_id,
                    );

                    $this->db->where('sucursal_id', $branch_office_id);
                    $this->db->where('almacen_id', $warehouse_id);
                    $this->db->where('producto_id', $product_id);
                    $this->db->update('inventario_sucursal', $data_branch_office_inventory);

                    $data_product = array(
                        /*'precio_venta' => $params["precio_venta"],
                        'precio_venta_mayor' => $params["precio_venta_mayor"],
                        'precio_venta_express' => $params["precio_venta_express"],
                        'precio_venta_laboratorio' => $params["precio_venta_laboratorio"],*/
                        'nombre_comercial' => $params["nombre_comercial"],
                        'nombre_generico' => $params["nombre_generico"],
                        'fecha_modificacion' => $params["fecha_modificacion"]
                    );
                    $this->db->where('id', $product_id);
                    $this->db->update('producto', $data_product);
                }
            }
        }

    }

    public function register_update_branch_office_inventory_by_warehouse($params = array())
    {
        $product_id = $params["producto_id"];
        $branch_office_id = get_branch_id_in_session();

        if ($params['warehouse_id'] > 0) {      // Si selecciono un Almacen

            $warehouse_id = $params['warehouse_id'];

            if ($this->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) {
                $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                // REGISTRA: inventario sucursal
                $data_branch_office_inventory = array(
                    'precio_compra' => $params["precio_compra"],
                    'precio_costo' => $params["precio_costo"],
                    'precio_costo_ponderado' => $params["precio_costo_poderado"],
                    'precio_venta' => $params["precio_venta"],
                    'stock' => $new_stock,
                    'fecha_modificacion' => $params["fecha_modificacion"],
                    'usuario_id' => $params["usuario_id"],
                    'sucursal_registro_id' => $params["sucursal_registro_id"],
                    'sucursal_id' => $branch_office_id,
                    'almacen_id' => $warehouse_id,
                    'producto_id' => $product_id,
                    'precio_venta_1' => $params["precio_venta_mayor"],
                    'precio_venta_2' => $params["precio_venta_express"],
                    'precio_venta_3' => $params["precio_venta_laboratorio"],
                    'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"],
                    'estado' => ACTIVO
                );
                $this->db->insert('inventario_sucursal', $data_branch_office_inventory);

                //ACTUALIZA: producto
                $data_product = array(
                    'nombre_comercial' => $params["nombre_comercial"],
                    'nombre_generico' => $params["nombre_generico"],
                    'fecha_modificacion' => $params["fecha_modificacion"]
                );
                $this->db->where('id', $product_id);
                $this->db->update('producto', $data_product);

            } else {

                $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                // ACTUALIZA: inventario sucursal
                $data_branch_office_inventory = array(
                    'precio_compra' => $params["precio_compra"],
                    'precio_costo' => $params["precio_costo"],
                    'precio_costo_ponderado' => $params["precio_costo_poderado"],
                    'precio_venta' => $params["precio_venta"],
                    'stock' => $new_stock,
                    'fecha_modificacion' => $params["fecha_modificacion"],
                    'usuario_id' => $params["usuario_id"],
                    'sucursal_registro_id' => $params["sucursal_registro_id"],
                    'sucursal_id' => $branch_office_id,
                    'almacen_id' => $warehouse_id,
                    'producto_id' => $product_id,
                    'precio_venta_1' => $params["precio_venta_mayor"],
                    'precio_venta_2' => $params["precio_venta_express"],
                    'precio_venta_3' => $params["precio_venta_laboratorio"],
                    'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"]
                );

                $this->db->where('sucursal_id', $branch_office_id);
                $this->db->where('almacen_id', $warehouse_id);
                $this->db->where('producto_id', $product_id);
                $this->db->update('inventario_sucursal', $data_branch_office_inventory);

                $data_product = array(
                    'precio_venta' => $params["precio_venta"],
                    'precio_venta_mayor' => $params["precio_venta_mayor"],
                    'precio_venta_express' => $params["precio_venta_express"],
                    'precio_venta_laboratorio' => $params["precio_venta_laboratorio"],
                    'nombre_comercial' => $params["nombre_comercial"],
                    'nombre_generico' => $params["nombre_generico"],
                    'fecha_modificacion' => $params["fecha_modificacion"]
                );
                $this->db->where('id', $product_id);
                $this->db->update('producto', $data_product);
            }

        } else {  // Si selecciono TODOS los almacenes

            $list_warehouse = $this->warehouse_model->get_warehouse_branch_office_id($branch_office_id);
            foreach ($list_warehouse as $warehouse) {
                $warehouse_id = $warehouse->id;
                if ($this->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) {
                    $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                    // REGISTRA: inventario sucursal
                    $data_branch_office_inventory = array(
                        'precio_compra' => $params["precio_compra"],
                        'precio_costo' => $params["precio_costo"],
                        'precio_costo_ponderado' => $params["precio_costo_poderado"],
                        'precio_venta' => $params["precio_venta"],
                        'stock' => $new_stock,
                        'fecha_modificacion' => $params["fecha_modificacion"],
                        'usuario_id' => $params["usuario_id"],
                        'sucursal_registro_id' => $params["sucursal_registro_id"],
                        'sucursal_id' => $branch_office_id,
                        'almacen_id' => $warehouse_id,
                        'producto_id' => $product_id,
                        'precio_venta_1' => $params["precio_venta_mayor"],
                        'precio_venta_2' => $params["precio_venta_express"],
                        'precio_venta_3' => $params["precio_venta_laboratorio"],
                        'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"],
                        'estado' => ACTIVO
                    );
                    $this->db->insert('inventario_sucursal', $data_branch_office_inventory);

                    // ACTUALIZA: producto
                    $data_product = array(
                        'nombre_comercial' => $params["nombre_comercial"],
                        'nombre_generico' => $params["nombre_generico"],
                        'fecha_modificacion' => $params["fecha_modificacion"]
                    );
                    $this->db->where('id', $product_id);
                    $this->db->update('producto', $data_product);

                } else {

                    $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                    // ACTUALIZA: inventario sucursal
                    $data_branch_office_inventory = array(
                        'precio_compra' => $params["precio_compra"],
                        'precio_costo' => $params["precio_costo"],
                        'precio_costo_ponderado' => $params["precio_costo_poderado"],
                        'precio_venta' => $params["precio_venta"],
                        'stock' => $new_stock,
                        'fecha_modificacion' => $params["fecha_modificacion"],
                        'usuario_id' => $params["usuario_id"],
                        'sucursal_registro_id' => $params["sucursal_registro_id"],
                        'sucursal_id' => $branch_office_id,
                        'almacen_id' => $warehouse_id,
                        'producto_id' => $product_id,
                        'precio_venta_1' => $params["precio_venta_mayor"],
                        'precio_venta_2' => $params["precio_venta_express"],
                        'precio_venta_3' => $params["precio_venta_laboratorio"],
                        'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"]
                    );
                    $this->db->where('sucursal_id', $branch_office_id);
                    $this->db->where('almacen_id', $warehouse_id);
                    $this->db->where('producto_id', $product_id);
                    $this->db->update('inventario_sucursal', $data_branch_office_inventory);

                    // ACTUALIZA: producto
                    $data_product = array(
                        'nombre_comercial' => $params["nombre_comercial"],
                        'nombre_generico' => $params["nombre_generico"],
                        'fecha_modificacion' => $params["fecha_modificacion"]
                    );
                    $this->db->where('id', $product_id);
                    $this->db->update('producto', $data_product);
                }
            }
        }


    }
    public function register_update_branch_office_inventory_by_warehouse2($params = array())
    {
        $product_id = $params["producto_id"];
        $branch_office_id = get_branch_id_in_session();

        if ($params['warehouse_id'] > 0) {      // Si selecciono un Almacen

            $warehouse_id = $params['warehouse_id'];

            if ($this->exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id) == 0) {//si no existe
                $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);
                
                // REGISTRA: inventario sucursal
                $data_branch_office_inventory = array(
                    'precio_compra' => $params["precio_compra"],
                    'precio_costo' => $params["precio_costo"],
                    'precio_costo_ponderado' => $params["precio_costo_ponderado"],
                    'precio_venta' => $params["precio_venta"],
                    'stock' => $params["cantidad"],
                    'fecha_modificacion' => $params["fecha_modificacion"],
                    'usuario_id' => $params["usuario_id"],
                    'sucursal_registro_id' => $params["sucursal_registro_id"],
                    'sucursal_id' => $branch_office_id,
                    'almacen_id' => $warehouse_id,
                    'producto_id' => $product_id,
                    'precio_venta_1' => $params["precio_venta_mayor"],
                    'precio_venta_2' => $params["precio_venta_express"],
                    'precio_venta_3' => $params["precio_venta_laboratorio"],
                    'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"],
                    'estado' => ACTIVO
                );
                $this->db->insert('inventario_sucursal', $data_branch_office_inventory);
                /*
                //ACTUALIZA: producto
                $data_product = array(
                    'nombre_comercial' => $params["nombre_comercial"],
                    'nombre_generico' => $params["nombre_generico"],
                    'fecha_modificacion' => $params["fecha_modificacion"]
                );
                $this->db->where('id', $product_id);
                $this->db->update('producto', $data_product);
                */
            } else {//si existe

                $new_stock = $this->get_stock_inventory_and_warehouse($warehouse_id, $product_id);

                // ACTUALIZA: inventario sucursal
                $data_branch_office_inventory = array(
                    'precio_compra' => $params["precio_compra"],
                    'precio_costo' => $params["precio_costo"],
                    'precio_costo_ponderado' => $params["precio_costo_ponderado"],
                    'precio_venta' => $params["precio_venta"],
                    'stock' =>  $new_stock,
                    'fecha_modificacion' => $params["fecha_modificacion"],
                    'usuario_id' => $params["usuario_id"],
                    'sucursal_registro_id' => $params["sucursal_registro_id"],
                    'sucursal_id' => $branch_office_id,
                    'almacen_id' => $warehouse_id,
                    'producto_id' => $product_id,
                    'precio_venta_1' => $params["precio_venta_mayor"],
                    'precio_venta_2' => $params["precio_venta_express"],
                    'precio_venta_3' => $params["precio_venta_laboratorio"],
                    'porcentaje_precio_venta_3' => $params["porcentaje_precioventa_laboratorio"]
                );

                $this->db->where('sucursal_id', $branch_office_id);
                $this->db->where('almacen_id', $warehouse_id);
                $this->db->where('producto_id', $product_id);
                $this->db->update('inventario_sucursal', $data_branch_office_inventory);
                /*
                $data_product = array(
                    /*'precio_venta' => $params["precio_venta"],
                    'precio_venta_mayor' => $params["precio_venta_mayor"],
                    'precio_venta_express' => $params["precio_venta_express"],
                    'precio_venta_laboratorio' => $params["precio_venta_laboratorio"],*/
                    /*'nombre_comercial' => $params["nombre_comercial"],
                    'nombre_generico' => $params["nombre_generico"],
                    'fecha_modificacion' => $params["fecha_modificacion"]
                );
                $this->db->where('id', $product_id);
                $this->db->update('producto', $data_product);*/

                
            }

        }
    }

    // Obtiene la cantidad de stock que tiene el producto en el almacen
    public function get_stock_inventory_and_warehouse($warehouse_id, $product_id)
    {
        $this->db->select('COALESCE(SUM(i.cantidad),0) as stock')
            ->from('inventario i, almacen a')
            ->where('i.almacen_id=a.id')
            ->where('i.almacen_id', $warehouse_id)
            ->where('i.producto_id', $product_id)
            ->where('i.estado', ACTIVO)
            ->where('a.estado', ACTIVO)
            ->where('i.cantidad>0');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->stock;
        } else {
            return 0;
        }

    }

    public function exists_branch_office_inventory($branch_office_id, $warehouse_id, $product_id)
    {
        $this->db->select('*')
            ->from('inventario_sucursal')
            ->where('sucursal_id', $branch_office_id)
            ->where('almacen_id', $warehouse_id)
            ->where('producto_id', $product_id);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            // SI EXISTE
            return 1;
        } else {
            // NO EXISTE
            return 0;
        }
    }

    /*obtener lista de productos para consulta*/
    public function get_product_reason_list($params = array())
    {

        $branch_office_report = $params["branch_office_report"];
        $warehouse_report = $params["warehouse_report"];

        $this->load->model('warehouse_model');
        $warehouse = $this->warehouse_model->get_warehouse_without_guarantee(get_branch_id_in_session());
		$search=$params['search'];
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('p.producto_id as id,
        					p.codigo_producto as codigo,
        					p.nombre_comercial,
        					p.nombre_generico,
        					p.nombre_grupo,
        					p.nombre_marca,
        					p.codigo_modelo,
        					p.nombre_modelo,
        					p.nombre_unidad_medida,
                            p.dimension,
                            p.nombre_marca,
                            s.nombre as nombre_sucursal,
                            subgrupo.nombre as subgrupo,
                            i.estado,
                            i.id as inventario_sucursal_id,
                            a.id as almacen_id,
        					TRUNC(i.stock,0) as stock,
        					TRUNC(i.precio_costo,2) as precio_costo,
                            TRUNC(i.precio_venta,2) as precio_venta,
                            TRUNC(i.precio_venta_1,2) as precio_venta_1,
                            TRUNC(i.precio_venta_2,2) as precio_venta_2,
                            TRUNC(i.precio_venta_3,2) as precio_venta_3,
        					TRUNC(i.precio_costo_ponderado,2) as precio_costo_ponderado,
        					TRUNC(i.precio_venta,2) as precio_venta,a.nombre as almacen')
            ->from('vista_lista_producto p, inventario_sucursal i, almacen a, sucursal s, grupo subgrupo')
            ->where('i.producto_id=p.producto_id')
            // ->where('i.producto_id!=106')
            ->where('i.almacen_id=a.id')
            ->where('a.sucursal_id=s.id')
            ->where('p.subgrupo_id=subgrupo.id')
            ->where('a.estado', ACTIVO)
            ->where('p.estado_producto', ACTIVO)
            ->where('i.estado', ACTIVO)
            ->where('p.producto_id>1');
            // ->where('a.sucursal_id', get_branch_id_in_session());
        /*if (count($warehouse) > 0) {
            $this->db->where_in('i.almacen_id', $warehouse);
        }else{
            $this->db->where('i.almacen_id', 0);
        }*/
        if ($branch_office_report != '0') {
            $this->db->where('a.sucursal_id =', $branch_office_report);
        }   
        if ($warehouse_report != '0') {
            $this->db->where('i.almacen_id =', $warehouse_report);
        }
           

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
            $this->db->order_by('p.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
        	if ($search!=''){
				$this->db->group_start();
				$this->db->like('lower(p.codigo_producto)', strtolower($search));
				$this->db->or_like('lower(p.nombre_comercial)', strtolower($search));
				$this->db->or_like('lower(p.nombre_generico)', strtolower($search));
				$this->db->or_like('lower(p.nombre_grupo)', strtolower($search));
				$this->db->or_like('lower(p.nombre_modelo)', strtolower($search));
                $this->db->or_like('lower(p.dimension)', strtolower($search));
                $this->db->or_like('lower(p.imei1)', strtolower($search));
                $this->db->or_like('lower(p.imei2)', strtolower($search));
				$this->db->group_end();
			}

        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data,
            'warehouse' => $warehouse
        );
        return $json_data;
    }

     /*obtener lista de productos para consulta*/
     public function get_inactive_product_reason_list($params = array())
     {
         $this->load->model('warehouse_model');
         $warehouse = $this->warehouse_model->get_warehouse_without_guarantee(get_branch_id_in_session());
         $search=$params['search'];
         // Se cachea la informacion que se arma en query builder
         $this->db->start_cache();
         $this->db->select('p.producto_id as id,
                             p.codigo_producto as codigo,
                             p.nombre_comercial,
                             p.nombre_generico,
                             p.nombre_grupo,
                             p.nombre_marca,
                             p.codigo_modelo,
                             p.nombre_modelo,
                             p.nombre_unidad_medida,
                             p.dimension,
                             p.nombre_marca,
                             s.nombre as nombre_sucursal,
                             subgrupo.nombre as subgrupo,
                             i.estado,
                             i.id as inventario_sucursal_id,
                             a.id as almacen_id,
                             TRUNC(i.stock,0) as stock,
                             TRUNC(i.precio_costo,2) as precio_costo,
                             TRUNC(i.precio_costo_ponderado,2) as precio_costo_ponderado,
                             TRUNC(i.precio_venta,2) as precio_venta,a.nombre as almacen')
             ->from('vista_lista_producto p, inventario_sucursal i, almacen a, sucursal s, grupo subgrupo')
             ->where('i.producto_id=p.producto_id')
             // ->where('i.producto_id!=106')
             ->where('i.almacen_id=a.id')
             ->where('a.sucursal_id=s.id')
             ->where('p.subgrupo_id=subgrupo.id')
             ->where('a.estado', ACTIVO)
             ->where('p.estado_producto', ACTIVO)
             ->where('i.estado', ANULADO)
             ->where('p.producto_id>1');
             // ->where('a.sucursal_id', get_branch_id_in_session());
        
 
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
             $this->db->order_by('p.id', 'ASC');
         }
 
         if (array_key_exists('search', $params)) {
             if ($search!=''){
                 $this->db->group_start();
                 $this->db->like('lower(p.codigo_producto)', strtolower($search));
                 $this->db->or_like('lower(p.nombre_comercial)', strtolower($search));
                 $this->db->or_like('lower(p.nombre_generico)', strtolower($search));
                 $this->db->or_like('lower(p.nombre_grupo)', strtolower($search));
                 $this->db->or_like('lower(p.nombre_modelo)', strtolower($search));
                 $this->db->or_like('lower(p.dimension)', strtolower($search));
                 $this->db->or_like('lower(p.imei1)', strtolower($search));
                 $this->db->or_like('lower(p.imei2)', strtolower($search));
                 $this->db->group_end();
             }
 
         }
 
         // Obtencion de resultados finales
         $draw = $this->input->post('draw');
         $data = $this->db->get()->result_array();
 
         $json_data = array(
             'draw' => intval($draw),
             'recordsTotal' => $records_total,
             'recordsFiltered' => $records_total,
             'data' => $data,
             'warehouse' => $warehouse
         );
         return $json_data;
     }

    public function exists_product_by_code($code)
    {
        $this->db->select('*')
            ->from('producto')
            ->where('codigo', $code)
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

    public function get_product_by_code($code)
    {
        $this->db->select('*')
            ->from('producto')
            ->where('codigo', $code)
            ->where('estado', ACTIVO);
         return $this->db->get()->row();
    }

    public function rellenar($dato, $numero_limite)
    {
        $rellenar = '';
        $numero = intval($numero_limite) - intval(strlen(strval($dato)));
        if ($numero > 0) {
            for ($i = 1; $i <= $numero; $i++) {
                $rellenar .= '0';
            }
        }
        $dato = $rellenar . $dato;
        return $dato;
    }

    public function generar_codigo_ean($producto_id)
    {
        $ultimo_id = $producto_id;

        $codigo_pais = '777';
        $codigo_empresa = '5026';
        $codigo_correlativo = $this->rellenar($ultimo_id, 5);
        $codigo_producto = $codigo_pais . $codigo_empresa . $codigo_correlativo;

        $digito_control = $this->digito_control($codigo_producto);

        return $codigo_producto . $digito_control;
    }
    public function digito_control($ean)
    {
        $par = 0;
        $impar = 0;
        $first = 1;

        // Empezamos por el final
        for ($i = strlen($ean) - 1; $i >= 0; $i--) {
            if ($first % 2 == 0) {
                $par += $ean[$i];
            } else {
                $impar += $ean[$i] * 3;
            }
            $first++;
        }
        $control = ($par + $impar) % 10;
        if ($control > 0) {
            $control = 10 - $control;
        }
        return $control;
    }
    public function get_ultimo_producto_id()
    {
        $this->db->select('max(id) as id')
            ->from('producto');
        return $this->db->get()->row()->id;
    }
}
