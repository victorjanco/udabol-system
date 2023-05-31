<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 16/03/2020
 * Time: 16:02 PM
 */
class Cash_income_type_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
     /*Obtener la primera caja*/
     public function get_first()
     {
         return $this->db->get('tipo_ingreso_caja')->row();
     }
    public function get_cash_income_type_all_enable()
    {
        $this->db->select('*')
            ->from('tipo_ingreso_caja')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    /*Obtener todas las tipo_ingreso_caja activas especial para cargar combos o autocompletados*/
    public function get_cash_income_type_enable()
    {
        $this->db->select('*')
            ->from('tipo_ingreso_caja')
            ->where('id>1')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    /*Obtener datos del tipo almacen apartir del tipo_ingreso_caja_id*/
    public function get_cash_income_type_id($cash_income_type_id)
    {
        return $this->db->get_where('tipo_ingreso_caja', array('id' => $cash_income_type_id))->row();
    }

    /*Obtener lista de tipo_ingreso_caja para cargar la lista de dataTable*/
    public function get_cash_income_type_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('tipo_ingreso_caja');
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

    /*Funcion registrar nuevo tipo_ingreso_caja para validar los datos de la vista */
    public function register_cash_income_type()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $validation_rules = array(
            array(
                'field' => 'add_name',
                'label' => 'Nombre',
                'rules' => 'trim|required|alpha_numeric_spaces|is_unique[tipo_ingreso_caja.nombre]|strtoupper'
            ),
            array(
                'field' => 'add_description',
                'label' => 'Descripcion',
                'rules' => 'trim|required|alpha_numeric_spaces'
            )
        );

        if (verify_session()) {

            // Pasar reglas de validacion como parámetro
           $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
            $this->form_validation->set_message('is_unique', 'El Nombre tipo de ingreso caja ingresado ya existe.');


            if ($this->form_validation->run() === TRUE) {
                $data_cash_income_type = array(
                    'nombre' => strtoupper($this->input->post('add_name')),
                    'descripcion' => strtoupper($this->input->post('add_description')),
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'estado' => ACTIVO,
                    'usuario_registro_id' => get_user_id_in_session(),
                    'sucursal_registro_id' => get_branch_id_in_session()
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo tipo_ingreso_caja
                $this->_insert_cash_income_type($data_cash_income_type);

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

    /*Funcion para actualizar tipo_ingreso_caja para validar los datos de la vista */
    public function modify_cash_income_type()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $cash_income_type_id = $this->input->post('edit_id_cash_income_type');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'edit_name',
                'label' => 'Nombre',
                'rules' => sprintf("trim|required|is_unique_edit[%u, tipo_ingreso_caja, nombre]|strtoupper", $cash_income_type_id)
            ),
            array(
                'field' => 'edit_description',
                'label' => 'Descripcion',
                'rules' => 'trim|required'
            )

        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_cash_income_type = array(
                'nombre' => strtoupper($this->input->post('edit_name')),
                'descripcion' => strtoupper($this->input->post('edit_description')),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'usuario_modificacion_id' => get_user_id_in_session(),
                'sucursal_modificacion_id' => get_branch_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el tipo_ingreso_caja
            $this->_update_cash_income_type($cash_income_type_id, $data_cash_income_type);

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
    public function disable_cash_income_type()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'tipo_ingreso_caja',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /*Funcion privada para insertar en la base de datos del nuevo tipo_ingreso_caja*/
    private function _insert_cash_income_type($cash_income_type)
    {
        return $this->db->insert('tipo_ingreso_caja', $cash_income_type);
    }

    /*Funcion privada para actualizar en la base de datos tipo_ingreso_caja*/
    private function _update_cash_income_type($cash_income_type_id, $data_cash_income_type)
    {
        $where = array('id' => $cash_income_type_id);
        return $this->db->update('tipo_ingreso_caja', $data_cash_income_type, $where);
    }
}