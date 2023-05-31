<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 19/03/2020
 * Time: 13:51 PM
 */
class Change_type_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    /*Obtener el primer tipo_cambio*/
    public function get_first()
    {
        $change_type = $this->db->get('tipo_cambio')->row();
        return $change_type;
    }
    /*Obtener todas las tipo_cambio activas especial para cargar combos o autocompletados*/
    public function get_change_type_enable()
    {
        $this->db->select('*')
            ->from('tipo_cambio')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    public function get_change_type_by_array_currency_id($params = array())
    {
        $this->db->select('*')
            ->from('tipo_cambio')
            ->where_in('moneda_id', $params)
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    /*Obtener datos del tipo almacen apartir del tipo_cambio_id*/
    public function get_change_type_id($change_type_id)
    {
        return $this->db->get_where('tipo_cambio', array('id' => $change_type_id))->row();
    }

    /*Obtener lista de tipo_cambio para cargar la lista de dataTable*/
    public function get_change_type_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('tipo_cambio');
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

        // if (array_key_exists('search', $params)) {
        //     $this->db->like('lower(monto_cambio_venta)', strtolower($params['search']));
        // }

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

    /*Funcion registrar nuevo tipo_cambio para validar los datos de la vista */
    public function register_change_type()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $validation_rules = array(
            array(
                'field' => 'add_sale_change_amount',
                'label' => 'Monto Cambio Venta',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'add_purchase_change_amount',
                'label' => 'Monto Cambio Compra',
                'rules' => 'trim|required'
            )
        );

        if (verify_session()) {

            // Pasar reglas de validacion como parámetro
           $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
            $this->form_validation->set_message('is_unique', 'El Nombre del tipo de almacen ingresado ya existe.');


            if ($this->form_validation->run() === TRUE) {
                $data_change_type = array(
                    'monto_cambio_venta' => $this->input->post('add_sale_change_amount'),
                    'monto_cambio_compra' => strtoupper($this->input->post('add_purchase_change_amount')),
                    'fecha_habilitado' => date('Y-m-d'),
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'estado' => ACTIVO,
                    'usuario_registro_id' => get_user_id_in_session(),
                    'sucursal_registro_id' => get_branch_id_in_session()
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo tipo_cambio
                $this->_insert_change_type($data_change_type);

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

    /*Funcion para actualizar tipo_cambio para validar los datos de la vista */
    public function modify_change_type()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $change_type_id = $this->input->post('edit_id_change_type');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'edit_sale_change_amount',
                'label' => 'Monto Cambio Venta',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'edit_purchase_change_amount',
                'label' => 'Monto Cambio Compra',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_change_type = array(
                'monto_cambio_venta' => $this->input->post('edit_sale_change_amount'),
                'monto_cambio_compra' => strtoupper($this->input->post('edit_purchase_change_amount')),
                'fecha_habilitado' => date('Y-m-d'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'usuario_modificacion_id' => get_user_id_in_session(),
                'sucursal_modificacion_id' => get_branch_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el tipo_cambio
            $this->_update_change_type($change_type_id, $data_change_type);

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
    public function disable_change_type()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'tipo_cambio',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    public function get_an_active_change_type_by_currency_company_id($currency_company_id)
    {
        $this->db->select('*')
            ->from('tipo_cambio')
            ->where('estado', ACTIVO)
            ->where('moneda_id', $currency_company_id)
            ->where('sucursal_id', get_branch_id_in_session());
        return $this->db->get()->row();
    }





    /*Funcion privada para insertar en la base de datos del nuevo tipo_cambio*/
    private function _insert_change_type($change_type)
    {
        return $this->db->insert('tipo_cambio', $change_type);
    }

    /*Funcion privada para actualizar en la base de datos tipo_cambio*/
    private function _update_change_type($change_type_id, $data_change_type)
    {
        $where = array('id' => $change_type_id);
        return $this->db->update('tipo_cambio', $data_change_type, $where);
    }
}