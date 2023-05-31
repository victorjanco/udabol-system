<?php

/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 27/7/2017
 * Time: 11:11 AM
 */
class Reception_notification_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_notification_model');
    }

    /*Obtener todas los tipo de notificaciones de recepcion activas especial para cargar combos o autocompletados*/
    public function get_notification_reception_enable($type = 'object')
    {
        $this->db->select('*')
            ->from('historial_recepcion')
            ->where('estado', get_state_abm('ACTIVO'))
            ->where('sucursal_id', get_branch_id_in_session());
        return $this->db->get()->result($type);
    }

    /*Obtener datos del tipo notificaion apartir del tipo_notificacion_id*/
    public function get_notification_id($notification_id)
    {
        return $this->db->get_where('historial_recepcion', array('id' => $notification_id))->row();
    }

    /*Obtener lista de tipo_almacen para cargar la lista de dataTable*/
    public function get_reception_notification_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('h.*,t.nombre as tipo,r.codigo_recepcion,c.nombre')
            ->from('historial_recepcion h, tipo_notificacion t, recepcion r, equipo_cliente e, cliente c')
            ->where('r.equipo_cliente_id= e.id')
            ->where('e.cliente_id= c.id')
            ->where('h.recepcion_id= r.id')
            ->where('h.sucursal_id',get_branch_id_in_session())
            ->where('h.tipo_notificacion_id= t.id');
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
            if ($params['column']=='r.codigo_recepcion'){
                $this->db->order_by('h.id', $params['order']);
            }else{
                $this->db->order_by($params['column'], $params['order']);
            }
        } else {
            $this->db->order_by('h.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(c.nombre)', strtolower($params['search']));
//            $this->db->or_like('lower(r.codigo_recepcion)', strtolower($params['search']));
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

    /*Obtener lista de tipo_almacen para cargar la lista de dataTable*/
    public function get_reception_notification_specific_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('h.*,t.nombre as tipo,r.codigo_recepcion,c.nombre')
            ->from('historial_recepcion h, tipo_notificacion t, recepcion r, equipo_cliente e, cliente c')
            ->where('r.equipo_cliente_id= e.id')
            ->where('e.cliente_id= c.id')
            ->where('h.recepcion_id= r.id')
            ->where('h.tipo_notificacion_id= t.id')
            ->where('h.recepcion_id', $params['id_reception']);
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
            $this->db->order_by('h.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(c.nombre)', strtolower($params['search']));
//            $this->db->or_like('lower(r.codigo_recepcion)', strtolower($params['search']));
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

    /*Funcion registrar nuevo tipo_almacen para validar los datos de la vista */
    public function register_reception_notification()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );

        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'tipo_notificacion',
                'label' => 'Debe seleccionar un tipo de notificacion',
                'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_notification_model->get_type_notification_reception_enable(), 'id'))
            ),
            array(
                'field' => 'glosa_notificacion',
                'label' => 'El campo codigo no puede ser vacio',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_notification = array(
                'glosa' => $this->input->post('glosa_notificacion'),
                'fecha_recepcionada' => $this->input->post('fecha_notificacion'),
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'estado' => get_state_abm('ACTIVO'),
                'recepcion_id' => $this->input->post('id_reception'),
                'sucursal_id' => get_branch_id_in_session(),
                'usuario_id' => get_user_id_in_session(),
                'tipo_notificacion_id' => $this->input->post('tipo_notificacion')
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Registrar a la base de datos de nuevo tipo_almacen
            $this->_insert_notification($data_notification);

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

        echo json_encode($response);
    }

    /*Funcion para actualizar tipo_almacen para validar los datos de la vista */
    public function modify_reception_notification()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $notification_id = $this->input->post('id_notification_edit');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'tipo_notificacion_edit',
                'label' => 'Debe seleccionar un tipo de notificacion',
                'rules' => sprintf("trim|required|in_list[%s]", implode_array($this->type_notification_model->get_type_notification_reception_enable(), 'id'))
            ),
            array(
                'field' => 'glosa_notificacion_edit',
                'label' => 'El campo codigo no puede ser vacio',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_notification = array(
                'glosa' => $this->input->post('glosa_notificacion_edit'),
                'fecha_recepcionada' => $this->input->post('fecha_notificacion_edit'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'recepcion_id' => $this->input->post('id_reception_edit'),
                'sucursal_id' => get_branch_id_in_session(),
                'usuario_id' => get_user_id_in_session(),
                'tipo_notificacion_id' => $this->input->post('tipo_notificacion_edit')
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el tipo_almacen
            $this->_update_notification($notification_id, $data_notification);

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

        echo json_encode($response);
    }

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_reception_notification()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'historial_recepcion',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo tipo_almacen*/
    private function _insert_notification($notification)
    {
        return $this->db->insert('historial_recepcion', $notification);
    }

    /*Funcion privada para actualizar en la base de datos tipo_almacen*/
    private function _update_notification($notification_id, $data_notification)
    {
        $where = array('id' => $notification_id);
        return $this->db->update('historial_recepcion', $data_notification, $where);
    }


}