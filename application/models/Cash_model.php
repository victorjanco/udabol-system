<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 16/03/2020
 * Time: 16:02 PM
 */
class Cash_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_model');
    }

    /*Obtener todas las caja activas especial para cargar combos o autocompletados*/
    public function get_all_cash_enable()
    {
        $this->db->select('*')
            ->from('caja')
            // ->where('id>1')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    /*Obtener todas las caja activas especial para cargar combos o autocompletados*/
    public function get_cash_enable()
    {
        $this->db->select('*')
            ->from('caja')
            // ->where('id>1')
            ->where('estado', ACTIVO)
            ->where('sucursal_id',get_branch_id_in_session());
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_cash_by_branch($branch_office_id)
    {
        $this->db->select('*')
            ->from('caja')
            // ->where('id>1')
            ->where('estado', ACTIVO)
            ->where('sucursal_id', $branch_office_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    /*Obtener la primera caja*/
    public function get_first()
    {
        return $this->db->get('caja')->row();
    }
    /*Obtener datos del tipo almacen apartir del caja_id*/
    public function get_cash_id($cash_id)
    {
        return $this->db->get_where('caja', array('id' => $cash_id, 'estado' => ACTIVO))->row();
    }
    public function get_cash_aperture_id($cash_aperture_id)
	{
		return $this->db->get_where('apertura_caja', array('id' => $cash_aperture_id, 'estado' => ACTIVO))->row();
    }
    /*Obtener lista de caja para cargar la lista de dataTable*/
    public function get_cash_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('caja')
            ->where('sucursal_id',get_branch_id_in_session());
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

    /*Funcion registrar nuevo caja para validar los datos de la vista */
    public function register_cash()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $validation_rules = array(
            array(
                'field' => 'add_code',
                'label' => 'Codigo',
                'rules' => 'trim|required|alpha_numeric_spaces|is_unique_edit[%u, caja, codigo]'
            ),
            array(
                'field' => 'add_name',
                'label' => 'Nombre',
                'rules' => 'trim|required|alpha_numeric_spaces|is_unique[caja.nombre]|strtoupper'
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
            $this->form_validation->set_message('is_unique', 'El Nombre de la caja ingresado ya existe.');


            if ($this->form_validation->run() === TRUE) {
                $data_cash = array(
                    'codigo' => $this->input->post('add_code'),
                    'nombre' => strtoupper($this->input->post('add_name')),
                    'descripcion' => strtoupper($this->input->post('add_description')),
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'estado' => ACTIVO,
                    'sucursal_id' => get_branch_id_in_session(),
                    'user_created' => get_user_id_in_session(),
                    'user_updated' => get_user_id_in_session()
                );

                // Inicio de transacción
                $this->db->trans_begin();
                // Registrar a la base de datos de nuevo caja
                $this->_insert_cash($data_cash);
                $cash_inserted=$this->_get_cash($data_cash);

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

    /*Funcion para actualizar caja para validar los datos de la vista */
    public function modify_cash()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $cash_id = $this->input->post('edit_id_cash');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'edit_code',
                'label' => 'Codigo',
                'rules' => sprintf('trim|required|alpha_numeric_spaces|is_unique_edit[%u, caja, codigo]', $cash_id)
            ),
            array(
                'field' => 'edit_name',
                'label' => 'Nombre',
                'rules' => sprintf("trim|required|is_unique_edit[%u, caja, nombre]|strtoupper", $cash_id)
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
            $data_cash = array(
                'codigo' => $this->input->post('edit_code'),
                'nombre' => strtoupper($this->input->post('edit_name')),
                'descripcion' => strtoupper($this->input->post('edit_description')),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'sucursal_id' => get_branch_id_in_session(),
                'user_updated' => get_user_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();
            // Actualizar el caja
            $this->_update_cash($cash_id, $data_cash);

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
    public function disable_cash()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'caja',
            ['estado' => ANULADO,
             'fecha_modificacion' => date('Y-m-d H:i:s'),
             'user_updated' => get_user_id_in_session()],
            ['id' => $id]
        );
    }
  
    /* Obtiene las cajas habilitadas */
    public function get_cash_enabled()
    {
        $response = array(
            'check' => false,
            'login' => false,
            'login_cash' => false
            // 'box_enable' => false
        );

        if (verify_session()) {

            // if (get_user_box_enable_in_session() == 1) {//validacion de permiso de caja

                $sesion_cash_id = get_session_cash_id();

                if ($sesion_cash_id != false) {
                    /* Ya esta logueado con su caja de session*/
                    $response['login_cash'] = true;
                } else {
                    $result = $this->get_all_cash_by_branch_office_id(get_branch_id_in_session(),get_user_id_in_session());
                    if (count($result) > 0) {
                        $response['check'] = true;
                        $response['result'] = $result;
                    } else {
                        $response['check'] = false;
                    }
                }
            // } else {
            //     $response['box_enable'] = true;
            // }

        } else {
            $response['login'] = true;
        }

        return json_encode($response);
    }
    
    /* Obtener las cajas por el id de la sucursal */
    public function get_all_cash_by_branch_office_id($branch_office_id, $user_id)
    {
        $this->db->select('c.*')
            ->from('caja c, usuario_caja uc')
            ->where('c.estado', ACTIVO)
            ->where('c.id=uc.caja_id')
            ->where('uc.usuario_id', $user_id)
            ->where('c.sucursal_id', $branch_office_id)
            ->order_by('c.id','ASC');
        return $this->db->get()->result();
    }
    function get_cash_aperture_totals($cash_id){
        $this->db->select('ap.id, ap.monto_apertura_bs, ap.monto_apertura_sus, ap.total_ingreso_bs, ap.total_ingreso_sus, ap.total_egreso_bs, ap.total_egreso_sus')
            ->from('caja c, apertura_caja ap')
            ->where('c.estado', ACTIVO)
            ->where('c.id=ap.caja_id')
            // ->where('ap.usuario_id', get_user_id_in_session())
            ->where('c.sucursal_id', get_branch_id_in_session())
            ->order_by('ap.id','ASC');
        return $this->db->get()->result();
    }
    public function get_permission_to_close_cas_by_administrador()
    {
        $response = array(
            'success' => FALSE,
        );

        if (get_user_type_in_session() == 2) {
            $response['success'] = true;
        }

        return $response;
    }
 
    /*Funcion privada para insertar en la base de datos del nuevo caja*/
    private function _insert_cash($cash)
    {
        return $this->db->insert('caja', $cash);
    }
    public function _get_cash($cash)
	{
		return $this->db->get_where('caja', $cash)->row();
    }

    /*Funcion privada para actualizar en la base de datos caja*/
    private function _update_cash($cash_id, $data_cash)
    {
        $where = array('id' => $cash_id);
        return $this->db->update('caja', $data_cash, $where);
    }

}