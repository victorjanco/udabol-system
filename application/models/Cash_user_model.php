<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 08/04/2020
 * Time: 14:02 PM
 */
class Cash_user_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las usuario_caja*/
    public function get_cash_user_enable($params = array())
    {
        $user_id = $this->input->post('user_id');
         // Se cachea la informacion que se arma en query builder
         $this->db->start_cache();
            $this->db->select('uc.*, u.nombre as usuario, c.nombre as caja')
                ->from('caja c, usuario_caja uc, usuario u')
                ->where('c.id=uc.caja_id')
                ->where('c.estado', ACTIVO)
                ->where('uc.usuario_id', $user_id)
                ->where('u.id', $user_id)
                ->where('c.sucursal_id',get_branch_id_in_session());
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
             $this->db->order_by('uc.id', 'ASC');
         }
 
         if (array_key_exists('search', $params)) {
             $this->db->like('lower(c.nombre)', strtolower($params['search']));
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

    /*Obtener datos del tipo almacen apartir del usuario_caja_id*/
    public function get_cash_user_id($cash_user_id)
    {
        return $this->db->get_where('usuario_caja', array('id' => $cash_user_id, 'estado' => ACTIVO))->row();
    }

    /*Obtener lista de usuario_caja para cargar la lista de dataTable*/
    public function get_cash_users_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('u.*, c.descripcion as cargo')
            ->from('usuario  u, usuario_sucursal us, cargo c')
            ->where('u.id=us.usuario_id')
            ->where('u.cargo_id=c.id')
            ->where('u.id <> 1')
            ->where('us.sucursal_id',get_branch_id_in_session());
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
            $this->db->order_by('u.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(u.nombre)', strtolower($params['search']));
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

    /*Funcion registrar nuevo usuario_caja para validar los datos de la vista */
    public function register_cash_user()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $validation_rules = array(
            array(
                'field' => 'user_id',
                'label' => 'Codigo',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'cash_id',
                'label' => 'Nombre',
                'rules' => 'trim|required'
            )
        );

        if (verify_session()) {

            // Pasar reglas de validacion como parámetro
           $this->form_validation->set_rules($validation_rules);
           $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');


            if ($this->form_validation->run() === TRUE) {
                $data_cash_user = array(
                    'usuario_id' => $this->input->post('user_id'),
                    'caja_id' => $this->input->post('cash_id')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo usuario_caja
                $this->_insert_cash_user($data_cash_user);

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

    /*Funcion para desabilitar el tipo almacen*/
    public function disable_cash_user()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'usuario_caja',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }
    
    /*Funcion para eliminar usuario_caja*/
    public function delete_cash_user()
    {
        $id = $this->input->post('id');
        return $this->db->delete(
            'usuario_caja',
            ['id' => $id]
        );
    }
    /*Funcion privada para insertar en la base de datos del nuevo usuario_caja*/
    private function _insert_cash_user($cash_user)
    {
        return $this->db->insert('usuario_caja', $cash_user);
    }

    /*Funcion privada para actualizar en la base de datos usuario_caja*/
    private function _update_cash_user($cash_user_id, $data_cash_user)
    {
        $where = array('id' => $cash_user_id);
        return $this->db->update('usuario_caja', $data_cash_user, $where);
    }

    public function _get_cash_user_aperture($cash_user_aperture)
	{
		return $this->db->get_where('apertura_usuario_caja', $cash_user_aperture)->row();
    }
    
}