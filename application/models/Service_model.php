<?php

/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 31/07/2017
 * Time: 05:38 PM
 */
class Service_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_service_types($type = 'object')
    {
        return $this->db->get_where('tipo_servicio', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    public function get_service_by_type($id_service)
    {
        $this->db->select('s.id, s.nombre, s.precio');
        $this->db->from('servicio s, tipo_servicio t');
        $this->db->where('s.tipo_servicio_id = t.id');
        $this->db->where('s.estado', get_state_abm('ACTIVO'));
        $this->db->where('t.id', $id_service);
        return $this->db->get()->result();
    }


    /*Obtener todas las servicios activos especial para cargar combos o autocompletados*/
    public function get_service_enable($type = 'object')
    {
        return $this->db->get_where('servicio', array('estado' => get_state_abm('ACTIVO')))->result($type);
    }

    /*Obtener datos del servicio apartir del servicio_id*/
    public function get_service_id($service_id)
    {
        return $this->db->get_where('servicio', array('id' => $service_id))->row();
    }

    /*Obtener lista de servicio para cargar la lista de dataTable*/
    public function get_service_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('s.*, t.nombre as nombre_tipo')
            ->from('servicio s, tipo_servicio t')
            ->where('s.tipo_servicio_id = t.id');
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
            $this->db->order_by('s.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(s.nombre)', strtolower($params['search']));
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

    /*Funcion registrar nuevo servicio para validar los datos de la vista */
    public function register_service()
    {
        try {
            $response = array(
                'success' => FALSE,
                'messages' => array(),
                'login' => FALSE
            );
    
            if (verify_session()) {
    
                // Reglas de validacion
                $validation_rules = array(
                    array(
                        'field' => 'nombre_servicio',
                        'label' => 'El nombre del nuevo servicio debe ser unico y solo permite numeros y letras',
                        'rules' => 'trim|required|is_unique[servicio.nombre]'
                    ),
                    array(
                        'field' => 'descripcion_servicio',
                        'label' => '',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'precio_servicio',
                        'label' => 'Debe ingresar datos alfanumericos',
                        'rules' => 'trim|required'
                    ),
                    array(
                        'field' => 'tipo_servicio_servicio',
                        'label' => 'Debe seleccionar una marca por favor',
                        'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_service_model->get_type_service_enable(), 'id'))
                    )
                );
    
                // Pasar reglas de validacion como parámetro
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');
    
                if ($this->form_validation->run() === TRUE) {
                    $data_service = array(
                        'nombre' => $this->input->post('nombre_servicio'),
                        'descripcion' => $this->input->post('descripcion_servicio'),
                        'precio' => $this->input->post('precio_servicio'),
                        'estado' => get_state_abm('ACTIVO'),
                        'tipo_servicio_id' => $this->input->post('tipo_servicio_servicio'),
                        'sucursal_id' => get_branch_id_in_session(),
                        'usuario_id' => get_user_id_in_session()
                    );
    
                    // Inicio de transacción
                    $this->db->trans_begin();
    
                    // Registrar a la base de datos de nuevo servicio
                    $this->_insert_service($data_service);
                    $service_inserted = $this->_get_service($data_service);
                    $service_id = $service_inserted->id;

                    $data_service_category = array(
                        'precio_servicio' => $this->input->post('precio_servicio_baja'),
                        'estado' => ACTIVO,
                        'categoria_id' => 3,
                        'servicio_id' => $service_id
                    );
    
                    $this->_insert_service_categoria($data_service_category);
    
                    $data_service_category = array(
                        'precio_servicio' => $this->input->post('precio_servicio_media'),
                        'estado' => ACTIVO,
                        'categoria_id' => 2,
                        'servicio_id' => $service_id
                    );
    
                    $this->_insert_service_categoria($data_service_category);
    
                    $data_service_category = array(
                        'precio_servicio' => $this->input->post('precio_servicio_alta'),
                        'estado' => ACTIVO,
                        'categoria_id' => 1,
                        'servicio_id' => $service_id
                    );
    
                    $this->_insert_service_categoria($data_service_category);
    
                    // Obtener resultado de transacción
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
    
            // echo json_encode($response);
            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    /*Funcion para actualizar el servicio para validar los datos de la vista */
    public function modify_service()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $service_id = $this->input->post('id_servicio_edit');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nombre_servicio_edit',
                    'label' => 'El nombre servicio debe ser unico y solo permite numeros y letras',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, servicio, nombre]", $service_id)
                ),
                array(
                    'field' => 'descripcion_servicio_edit',
                    'label' => 'En nombre de la descripcion tiene caracteristicas especiales',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'precio_servicio_edit',
                    'label' => 'Debe ingresar datos alfanumericos',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'tipo_servicio_servicio_edit',
                    'label' => 'Debe seleccionar una marca por favor',
                    'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_service_model->get_type_service_enable(), 'id'))
                )

            );


            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

            $session = $this->session->userdata('user');
            $sucursal_id = $session['id_branch_office'];
            $usuario_id = $session['id_user'];
            if ($this->form_validation->run() === TRUE) {
                $data_service = array(
                    'nombre' => $this->input->post('nombre_servicio_edit'),
                    'descripcion' => $this->input->post('descripcion_servicio_edit'),
                    'precio' => $this->input->post('precio_servicio_edit'),
                    'tipo_servicio_id' => $this->input->post('tipo_servicio_servicio_edit'),
                    'sucursal_id' => $sucursal_id,
                    'usuario_id' => $usuario_id
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Actualizar el modelo
                $this->_update_service($service_id, $data_service);

                $data_service_category = array(
                    'precio_servicio' => $this->input->post('precio_servicio_baja_edit')
                );

                $this->_update_service_categoria($service_id, 3, $data_service_category);

                $data_service_category = array(
                    'precio_servicio' => $this->input->post('precio_servicio_media_edit')
                );

                $this->_update_service_categoria($service_id, 2, $data_service_category);

                $data_service_category = array(
                    'precio_servicio' => $this->input->post('precio_servicio_alta_edit')
                );

                $this->_update_service_categoria($service_id, 1, $data_service_category);


                // Obtener resultado de transacción
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

        echo json_encode($response);
    }

    /*Funcion para desabilitar el servicio*/
    public function disable_service()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'servicio',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion para desabilitar el servicio*/
    public function enable_service()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'servicio',
            ['estado' => ACTIVO],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo servicio*/
    private function _insert_service($service)
    {
        return $this->db->insert('servicio', $service);
    }

    /*Funcion privada para insertar en la base de datos del nuevo servicio*/
    private function _insert_service_categoria($service_categoria)
    {
        return $this->db->insert('precio_servicio_categoria', $service_categoria);
    }

    /*Funcion privada para actualizar en la base de datos de l servicio*/
    private function _update_service($service_id, $data_service)
    {
        $where = array('id' => $service_id);
        return $this->db->update('servicio', $data_service, $where);
    }

    /*Funcion privada para actualizar en la base de datos de l servicio*/
    private function _update_service_categoria($service_id, $category_id, $data_service)
    {
        $where = array('id' => $service_id, 'categoria_id' => $category_id);
        return $this->db->update('precio_servicio_categoria', $data_service, $where);
    }

    public function get_service_price()
    {
        $service_id = $this->input->post('id');
        $this->db->select('cat.nombre, prec.precio_servicio, cat.id')
            ->from('categoria cat, precio_servicio_categoria prec')
            ->where('cat.id = prec.categoria_id')
            ->where('cat.estado', ACTIVO)
            ->where('prec.estado', ACTIVO)
            ->where('prec.servicio_id', $service_id);
        return $this->db->get()->result();
    }

    /* Funcion para obtener los precio por servicio seleccionada por parametro*/
    public function get_service_category_by_service_id()
    {
        $service_id = $this->input->post('service_id');
        return $this->db->get_where('precio_servicio_categoria', array('estado' => ACTIVO, 'servicio_id' => $service_id))->result();
    }

    public function _get_service($obj_service)
	{
		return $this->db->get_where('servicio', $obj_service)->row();
	}
}