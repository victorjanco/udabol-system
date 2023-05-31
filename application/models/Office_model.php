<?php
/**
 * Created by PhpStorm.
 * User: Ariel
 * Date: 17/07/2017
 * Time: 02:49 PM
 */
class Office_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function get_branch_office_id($branch_office_id)
    {
        return $this->db->get_where('sucursal', array('id' => $branch_office_id))->row();
    }
    public function register_branch_office()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nit',
                    'label' => 'El nit solo debe tener numeros',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                ),
                array(
                    'field' => 'sucursal_sin',
                    'label' => 'El campo nombre sin no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_comercial',
                    'label' => 'El campo nombre comercial no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_telefono',
                    'label' => 'El campo telefono no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_direccion',
                    'label' => 'El campo direccion no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'ciudad_impuestos',
                    'label' => 'El campo ciudad de impuestos no puede ser vacio',
                    'rules' => 'trim|required'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_branch_office = array(
                    'nit' => $this->input->post('nit'),
                    'nombre' => $this->input->post('sucursal_sin'),
                    'nombre_comercial' => $this->input->post('sucursal_comercial'),
                    'codigo' => '',
                    'direccion' => $this->input->post('sucursal_direccion'),
                    'telefono' => $this->input->post('sucursal_telefono'),
                    'ciudad' => $this->input->post('departamento'),
                    'ciudad_impuestos' => $this->input->post('ciudad_impuestos'),
                    'correo' => $this->input->post('sucursal_correo'),
                    'web' => $this->input->post('url'),
                    'estado' => get_state_abm('ACTIVO'),
                    'tipo_sucursal' => $this->input->post('tipo'),
                    'user_created' => get_user_id_in_session(),
                    'user_updated' => get_user_id_in_session(),
                    'date_created' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s')   
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar solucion ala base de datos
                $this->_insert_branch_office($data_branch_office);
                $branch_office_inserted = $this->db->get_where('sucursal', $data_branch_office)->row();
                $branch_office_id =$branch_office_inserted->id;

                // // Datos para el almacen del tecnico
                // $data_warehouse = array(
                //     'nombre' => 'ALMACEN TECNICO',
                //     'descripcion' => 'ALMACEN TECNICO',
                //     'direccion' => 'TECNICO',
                //     'estado' => 0,
                //     'sucursal_id' => $branch_office_id,
                //     'usuario_id' => get_user_id_in_session(),
                //     'tipo_almacen_id' => 8
                // );

                // // Registrar almacen del tecnico
                // $this->_insert_warehouse($data_warehouse);

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

    public function edit_branch_office()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $branch_office_id = $this->input->post('id_branch_office');

        if (verify_session()) {

            // Reglas de validacion
            $validation_rules = array(
                array(
                    'field' => 'nit_edit',
                    'label' => 'El nit solo debe tener numeros',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                ),
                array(
                    'field' => 'sucursal_sin_edit',
                    'label' => 'El campo nombre sin no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_comercial_edit',
                    'label' => 'El campo nombre comercial no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_telefono_edit',
                    'label' => 'El campo telefono no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'sucursal_direccion_edit',
                    'label' => 'El campo direccion no puede ser vacio',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'ciudad_impuestos_edit',
                    'label' => 'El campo ciudad de impuestos no puede ser vacio',
                    'rules' => 'trim|required'
                )
            );

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data_branch_office = array(
                    'nit' => $this->input->post('nit_edit'),
                    'nombre' => $this->input->post('sucursal_sin_edit'),
                    'nombre_comercial' => $this->input->post('sucursal_comercial_edit'),
                    'direccion' => $this->input->post('sucursal_direccion_edit'),
                    'telefono' => $this->input->post('sucursal_telefono_edit'),
                    'ciudad' => $this->input->post('departamento_edit'),
                    'ciudad_impuestos' => $this->input->post('ciudad_impuestos_edit'),
                    'correo' => $this->input->post('sucursal_correo_edit'),
                    'web' => $this->input->post('url_edit'),
                    'tipo_sucursal' => $this->input->post('tipo_edit'),
                    'user_updated' => get_user_id_in_session(),
                    'date_updated' => date('Y-m-d H:i:s')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar solucion ala base de datos
                $this->_update_branch_office($branch_office_id, $data_branch_office);

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

    /** Metodo para eliminar la actividad seleccionada */
    public function delete_office()
    {
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('sucursal', 
            array('estado' => ANULADO, 
                'user_updated' => get_user_id_in_session(),
                'date_updated' => date('Y-m-d H:i:s'))
        );
    }
    /** Metodo para eliminar la actividad seleccionada */
    public function reactivate_branch_office()
    {
        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('sucursal', array('estado' => ACTIVO));
    }

    /** Obtiene las sucursales  */
    public function get_offices()
    {
        return $this->db->get_where('sucursal', array('estado' => ACTIVO))->result();
    }
    public function get_offices_session()
    {
        return $this->db->get_where('sucursal', array('estado' => ACTIVO, 'id'=>get_branch_id_in_session()))->result();
    }

    /** Obtiene las sucursales  */
    public function get_system_parameters()
    {
        return $this->db->get('system_parameters')->row();
    }
    /*
     * Obtiene las sucursales
     * */
    public function get_user_offices($id_user)
    {
        $this->db->select('s.id, s.nombre');
        $this->db->from('sucursal s, usuario_sucursal a, usuario u');
        $this->db->where('s.id = a.sucursal_id');
        $this->db->where('a.usuario_id = u.id');
        $this->db->where('u.id', $id_user);
        return $this->db->get()->result();
    }

    public function get_branch_office_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('sucursal');
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
            $this->db->order_by('id', 'DESC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->group_start();
            $this->db->like('lower(nombre)', strtolower($params['search']));
            $this->db->or_like('lower(nit)', $params['search']);
            $this->db->or_like('lower(nombre_comercial)', $params['search']);
            $this->db->group_end();
            $this->db->order_by('id', 'DESC');
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

    /*Funcion privada para insertar en la base de datos ala nueva sucursal*/
    private function _insert_branch_office($branch_office)
    {
        return $this->db->insert('sucursal', $branch_office);
    }

    /*Funcion privada para actualizar en la base de datos ala sucursal*/
    private function _update_branch_office($branch_office_id, $data_branch_office)
    {
        $where = array('id' => $branch_office_id);
        return $this->db->update('sucursal', $data_branch_office, $where);
    }

    private function _insert_warehouse($data_warehouse)
    {
        return $this->db->insert('almacen', $data_warehouse);
    }
}