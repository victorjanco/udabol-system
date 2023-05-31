<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 31/03/2020
 * Time: 16:02 PM
 */
class Cash_output_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las egreso_caja activas */
    public function get_cash_output_enable()
    {
        $this->db->select('*')
            ->from('egreso_caja')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
    
    /*Obtener datos del Egreso de Caja apartir del egreso_caja_id*/
    public function get_cash_output_id($cash_output_id)
    {
        return $this->db->get_where('egreso_caja', array('id' => $cash_output_id))->row();
    }
     
    public function get_print_cash_output($cash_output_id)
	{
        $cash_output = $this->get_cash_output_id($cash_output_id);
        
        $data_cash_output['cash_output'] = $cash_output;
        $data_cash_output['cash_output_type'] = $this->cash_output_type_model->get_cash_output_type_id($cash_output->tipo_egreso_caja_id);
        $data_cash_output['cash'] = $this->cash_model->get_cash_id($cash_output->caja_id);
		$data_cash_output['user'] = $this->user_model->get_user_id($cash_output->usuario_id);
		$data_cash_output['branch_office'] = $this->office_model->get_branch_office_id($cash_output->sucursal_id);
		$data_cash_output['company'] = $this->company_model->get_company();
		return $data_cash_output;
	}
    /*Obtener lista de egreso_caja para cargar la lista de dataTable*/
    public function get_cash_output_list($params = array())
    {
        // Se cachea la informacion que se arma en query builder
        $this->db->start_cache();
        $this->db->select('ec.*, c.nombre as nombre_caja')
            ->from('egreso_caja ec, caja c')
            ->where('ec.caja_id=c.id')
            ->where('ec.sucursal_id',get_branch_id_in_session());
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
            $this->db->order_by('ec.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(ec.detalle)', strtolower($params['search']));
        }

        // Obtencion de resultados finales
        $draw = $this->input->post('draw');
        $data = $this->db->get()->result_array();

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => $records_total,
            'recordsFiltered' => $records_total,
            'data' => $data
        );
        return $json_data;
    }

    /*Funcion registrar nuevo egreso_caja para validar los datos de la vista */
    public function register_cash_output()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE,
            'cash' => FALSE
        );

        $validation_rules = array(
            array(
                'field' => 'cash_output_type',
                'label' => 'Tipo Egreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'add_detail',
                'label' => 'Detalle',
                'rules' => 'trim|required|alpha_numeric_spaces'
            ),
            array(
                'field' => 'add_date_output',
                'label' => 'Fecha Egreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_bs',
                'label' => 'Monto Egreso Bs',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_sus',
                'label' => 'Monto Egreso Sus',
                'rules' => 'trim|required'
            )
        );

        if (verify_session()) {
            if(verify_cash_session()){
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
                $this->form_validation->set_message('is_unique', 'El Nombre del tipo de almacen ingresado ya existe.');

                if ($this->form_validation->run() === TRUE) {
                    $data_cash_output = array(
                        'nro_transaccion'=>$this->get_number_transaction(),
                        'nro_egreso'=> strval($this->get_number_transaction()),
                        'detalle' => $this->input->post('add_detail'),
                        'monto_bs' => $this->input->post('amount_bs'),
                        'monto_sus' => $this->input->post('amount_sus'),
                        'fecha_egreso' => $this->input->post('add_date_output'),
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'estado' => ACTIVO,
                        'tipo_egreso_caja_id'=> $this->input->post('cash_output_type'),
                        'caja_id'=> get_session_cash_id(),
                        'apertura_caja_id'=> get_session_cash_aperture_id(),
                        'sucursal_id' => get_branch_id_in_session(),
                        'user_created' => get_user_id_in_session(),
                        'user_updated' => get_user_id_in_session()
                        // 'sucursal_registro_id' => get_branch_id_in_session()
                    );

                    // Inicio de transacción
                    $this->db->trans_begin();

                    // Registrar a la base de datos de nuevo egreso_caja
                    $this->_insert_cash_output($data_cash_output);
                    $cash_output_inserted = $this->_get_cash_output($data_cash_output);

                
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
                $response['cash'] = TRUE;
            }
        } else {
            $response['login'] = TRUE;
        }

        echo json_encode($response);
    }

    /*Funcion para actualizar egreso_caja para validar los datos de la vista */
    public function edit_cash_output()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $cash_output_id = $this->input->post('edit_id_cash_output');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'edit_detail',
                'label' => 'Detalle',
                'rules' => 'trim|required|alpha_numeric_spaces'
            ),
            array(
                'field' => 'edit_date_output',
                'label' => 'Fecha Egreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_bs',
                'label' => 'Monto Egreso Bs',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_sus',
                'label' => 'Monto Egreso Sus',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_cash_output = array(
                // 'nro_egreso'=>'0002',
                'detalle' => $this->input->post('edit_detail'),
                'monto_bs' => $this->input->post('amount_bs'),
                'monto_sus' => $this->input->post('amount_sus'),
                'fecha_egreso' => $this->input->post('edit_date_output'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'estado' => ACTIVO,
                'tipo_egreso_caja_id'=> $this->input->post('cash_output_type'),
                'caja_id'=> $this->input->post('cash'),
                'sucursal_id' => get_branch_id_in_session(),
                'user_updated' => get_user_id_in_session()
                // 'usuario_modificacion_id' => get_user_id_in_session(),
                // 'sucursal_modificacion_id' => get_branch_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();
            // Actualizar el egreso_caja
            $this->_update_cash_output($cash_output_id, $data_cash_output);
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
    public function get_cash_output_by_date($cash_id, $cash_aperture_id, $branch_office_id)
    {
        $this->db->select('ec.*, c.descripcion as nombre_caja, tec.descripcion as nombre_tipo_egreso_caja')
            ->from('tipo_egreso_caja tec, egreso_caja ec, caja c')
            ->where('tec.id=ec.tipo_egreso_caja_id')
            ->where('ec.caja_id=c.id')
            ->where('ec.estado', ACTIVO)
            ->where('c.id', $cash_id)
            ->where('ec.apertura_caja_id', $cash_aperture_id)
            ->where('ec.sucursal_id', $branch_office_id)
            ->order_by('ec.id', 'ASC');
        
        $result= $this->db->get()->result();

        return $result;
    }
    /**
     * Obtener todos los montos por moneda
     */
  
  
    /*Funcion para desabilitar in Egreso de Caja*/
    public function disable_cash_output($id)
    {
     
        return $this->db->update(
            'egreso_caja',
            ['estado' => 0,
             'fecha_modificacion' => date('Y-m-d H:i:s'),
             'user_updated' => get_user_id_in_session()],
            ['id' => $id]
        );
    }
    public function get_number_transaction()
	{
		$this->db->select_max('nro_transaccion');
        $result = $this->db->get('egreso_caja');
        if ($result->num_rows() > 0)
        {
            $res2 = $result->result_array();
            return $res2[0]['nro_transaccion'] + 1;
        }else{
            return 1;
        }
    }
    public function get_total_amount_cash_output($cash_id, $cash_aperture_id, $branch_office_id)
	{
        $data=$this->db->select('c.id, c.nombre, SUM(ic.monto_bs) as total_bs, SUM(ic.monto_sus) as total_sus')
        ->from('egreso_caja ic, caja c')
        ->where('ic.caja_id=c.id') //EFECTIVO
        ->where('ic.caja_id', $cash_id)
        ->where('ic.apertura_caja_id', $cash_aperture_id)
        ->where('ic.sucursal_id', $branch_office_id)
        ->where('ic.estado', ACTIVO)
        ->group_by('c.id')
        ->order_by('c.id', 'ASC')
        ->get();
        return $data->row();
    }
    
   
   
    /*Funcion privada para insertar en la base de datos del nuevo egreso_caja*/
    public function _insert_cash_output($cash_output)
    {
        return $this->db->insert('egreso_caja', $cash_output);
    }
    
    /*Funcion privada para actualizar en la base de datos egreso_caja*/
    private function _update_cash_output($cash_output_id, $data_cash_output)
    {
        $where = array('id' => $cash_output_id);
        return $this->db->update('egreso_caja', $data_cash_output, $where);
    }

    public function _get_cash_output($cash_output)
	{
		return $this->db->get_where('egreso_caja', $cash_output)->row();
	}
   
}