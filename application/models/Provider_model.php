<?php

/**
 * Created by PhpStorm.
 * User: mendoza
 * Date: 07/08/2017
 * Time: 7:03 PM
 */
class Provider_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function get_provider_by_id($id) {
        return $this->db->get_where('proveedor', array('id' => $id))->row_array();
    }
    public function activate_provider()
	{
		// $id = $this->input->post('id');
		// $res = $this->get_activated_dosage_by_id($asignacion_dosficacion_id);
		// if ($res == false) {
			$id = $this->input->post('id');
			return $this->db->update(
				'proveedor',
				['estado' => ACTIVO, 
                'user_updated' => get_user_id_in_session(),
                'date_updated' => date('Y-m-d H:i:s')],
				['id' => $id]
			);
		// }
    }
    /*Obtener lista de proveedores para cargar la lista de dataTable*/
    public function get_provider_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('*')
            ->from('proveedor');
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

    /*Funcion registrar nuevo proveedor para validar los datos de la vista */
    public function register_provider()
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
                    'field' => 'nombre',
                    'label' => 'Nombre',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                ),
                array(
                    'field' => 'nit',
                    'label' => 'NIT',
                    'rules' => 'trim|required|alpha_numeric'
                ),
                array(
                    'field' => 'direccion',
                    'label' => 'Direccion',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'telefono',
                    'label' => 'Telefono',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                )
            );;

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'nombre' => $this->input->post('nombre'),
                    'nit' => $this->input->post('nit'),
                    'direccion' => $this->input->post('direccion'),
                    'telefono' => $this->input->post('telefono'),
                    'contacto' => $this->input->post('contacto'),
                    'estado' => get_state_abm('ACTIVO'),
                    'user_created' => get_user_id_in_session(),
                    'user_updated' => get_user_id_in_session(),
                    'date_created' => date('Y-m-d H:i:s'),
                    'date_updated' => date('Y-m-d H:i:s')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                // Registrar a la base de datos de nuevo modelo
                $this->db->insert('proveedor', $data);

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

    /*Funcion actualizar proveedor para validar los datos de la vista */
    public function modify_provider()
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
                    'field' => 'nombre_edit',
                    'label' => 'Nombre',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                ),
                array(
                    'field' => 'nit_edit',
                    'label' => 'NIT',
                    'rules' => 'trim|required|alpha_numeric'
                ),
                array(
                    'field' => 'direccion_edit',
                    'label' => 'Direccion',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'telefono_edit',
                    'label' => 'Telefono',
                    'rules' => 'trim|required|alpha_numeric_spaces'
                )
            );;

            // Pasar reglas de validacion como parámetro
            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');


            if ($this->form_validation->run() === TRUE) {
                $provider_id = $this->input->post('id');
                $data = array(
                    'nombre' => $this->input->post('nombre_edit'),
                    'nit' => $this->input->post('nit_edit'),
                    'direccion' => $this->input->post('direccion_edit'),
                    'telefono' => $this->input->post('telefono_edit'),
                    'contacto' => $this->input->post('contacto_edit'),
                    'user_updated' => get_user_id_in_session(),
                    'date_updated' => date('Y-m-d H:i:s')
                );

                // Inicio de transacción
                $this->db->trans_begin();

                $where = array('id' => $provider_id);
                $this->db->update('proveedor', $data, $where);

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

    /*Funcion para desabilitar el proveedor*/
    public function disable_provider()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'proveedor',
            ['estado' => get_state_abm('INACTIVO'),
            'user_updated' => get_user_id_in_session(),
            'date_updated' => date('Y-m-d H:i:s')],
            ['id' => $id]
        );
    }
    
    /* Obtene los proveedores de un producto*/
    public function get_providers_product()
    {
        $product_id = $this->input->post('product_id');
        $this->db->select()
            ->from('proveedor as prov')
            ->where('prov.estado',get_state_abm('ACTIVO'))
            ->join('producto_proveedor as prod_prov', 'prod_prov.proveedor_id = prov.id')
            ->where('prod_prov.producto_id',$product_id);
        return json_encode($this->db->get()->result());
    }

    /* Retorna el proveedor por id*/
    public function get_provider_by_id_inventory($provider_id)
    {
        $data = $this->db->get_where('proveedor',array('id' => $provider_id))->row();
        return $data;
    }

    public function search_provider($search)
    {
        $this->db->like('lower(nombre)', $search);
        $this->db->or_like('nit', $search);
        $res = $this->db->get('proveedor');
        $data = array();
        if ($res->num_rows() > 0) {
            foreach ($res->result_array() as $row) {
                $data[$row['id']] = $row['nit'] . '|' . $row['nombre'] . '|' .
                    $row['direccion'] . '|' . $row['telefono'] . '|' . $row['contacto'];
            }
        }
        return $data;
    }

    /* Obtene los proveedores de un producto*/
    public function get_providers_product_by_product($product_id)
    {
        $this->db->select('prov.*')
            ->from('proveedor as prov')
            ->where('prov.estado',get_state_abm('ACTIVO'))
            ->join('producto_proveedor as prod_prov', 'prod_prov.proveedor_id = prov.id')
            ->where('prod_prov.producto_id',$product_id);
        $data = $this->db->get()->result();
        $array_id_provider = [];
        foreach ($data as  $row):
            $array_id_provider[] = $row->id;
            endforeach;
        return $array_id_provider;
    }
}