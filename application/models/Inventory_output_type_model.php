<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 20/02/2020
 * Time: 09:02 AM
 */
class Inventory_output_type_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_inventory_output_type_enable()
    {
        $this->db->select('*')
            ->from('tipo_salida_inventario')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

	public function get_inventory_output_type_enable_access()
	{
		$this->db->select('*')
			->from('tipo_salida_inventario')
			->where('estado', ACTIVO)
			->where('acceso', ACTIVO);
		$this->db->order_by('id', 'ASC');
		return $this->db->get()->result();
	}

    /*Obtener datos del tipo almacen apartir del tipo_salida_inventario_id*/
    public function get_inventory_output_type_id($inventory_output_type_id)
    {
        return $this->db->get_where('tipo_salida_inventario', array('id' => $inventory_output_type_id))->row();
    }

    /*Obtener lista de tipo_salida_inventario para cargar la lista de dataTable*/
    public function get_inventory_output_type_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('tipo_salida_inventario');
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
            $this->db->like('lower(nombre)', strtolower($params['search']));
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

    public function register_inventory_output_type()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            $validation_rules = array(
                array(
                    'field' => 'add_name',
                    'label' => 'Nombre',
                    'rules' => 'trim|required|is_unique[tipo_salida_inventario.nombre]|strtoupper'
                ),
                array(
                    'field' => 'add_description',
                    'label' => 'Descripción',
                    'rules' => 'trim|required'
                )
            );
    
            if (verify_session()) {
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
    
                if ($this->form_validation->run() === TRUE) {
    
                    $this->db->trans_begin();
    
                    $date_hour = date('Y-m-d H:i:s');
    
                    $data_inventory_output_type = array(
                        'nombre' => strtoupper($this->input->post('add_name')),
                        'descripcion' => strtoupper($this->input->post('add_description')),
                        'fecha_registro' => $date_hour,
                        // 'fecha_modificacion' => $date_hour,
                        // 'acceso' => ACTIVO,
                        // 'contenido' => ACTIVO,
                        'estado' => ACTIVO,
                        // 'usuario_registro_id' => get_user_id_in_session(),
                        // 'usuario_modificacion_id' => get_user_id_in_session(),
                        // 'sucursal_registro_id' => get_branch_id_in_session(),
                        // 'sucursal_modificacion_id' => get_branch_id_in_session()
                    );
                    $this->_insert_inventory_output_type($data_inventory_output_type);
    
                    if ($this->db->trans_status() === TRUE) {
                        $this->db->trans_commit();
                        $response['success'] = TRUE;
                    } else {
                        $this->db->trans_rollback();
                        $response['success'] = FALSE;
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
            throw $th;
        }
        
    }


    public function modify_inventory_output_type()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array()
            );
    
            $inventory_output_type_id = $this->input->post('edit_id_inventory_output_type');
    
            $validation_rules = array(
                array(
                    'field' => 'edit_name',
                    'label' => 'Nombre',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, tipo_salida_inventario, nombre]|strtoupper", $inventory_output_type_id)
                ),
                array(
                    'field' => 'edit_description',
                    'label' => 'Descripción',
                    'rules' => 'trim|required'
                )
            );
    
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
    
            if ($this->form_validation->run() === TRUE) {
    
                $this->db->trans_begin();
    
                $data_inventory_output_type = array(
                    'nombre' => strtoupper($this->input->post('edit_name')),
                    'descripcion' => strtoupper($this->input->post('edit_description')),
                    // 'fecha_modificacion' => date('Y-m-d H:i:s'),
                    // 'usuario_modificacion_id' => get_user_id_in_session(),
                    // 'sucursal_modificacion_id' => get_branch_id_in_session()
                );
                $this->_update_inventory_output_type($inventory_output_type_id, $data_inventory_output_type);
    
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                } else {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                }
            } else {
                foreach ($_POST as $key => $value) {
                    $response['messages'][$key] = form_error($key);
                }
            }
    
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_inventory_output_type()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'tipo_salida_inventario',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo tipo_salida_inventario*/
    private function _insert_inventory_output_type($inventory_output_type)
    {
        return $this->db->insert('tipo_salida_inventario', $inventory_output_type);
    }

    /*Funcion privada para actualizar en la base de datos tipo_salida_inventario*/
    private function _update_inventory_output_type($inventory_output_type_id, $data_inventory_output_type)
    {
        $where = array('id' => $inventory_output_type_id);
        return $this->db->update('tipo_salida_inventario', $data_inventory_output_type, $where);
    }
}
