<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 16/03/2020
 * Time: 16:02 PM
 */
class Cash_income_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /*Obtener todas las ingreso_caja activas */
    public function get_cash_income_enable()
    {
        $this->db->select('*')
            ->from('ingreso_caja')
            ->where('estado', ACTIVO);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
     /*
    /*Obtener datos del Ingreso de Caja apartir del ingreso_caja_id*/
    public function get_cash_income_id($cash_income_id)
    {
        return $this->db->get_where('ingreso_caja', array('id' => $cash_income_id))->row();
    }
    
    public function get_cash_income_sale_by_sale_id($sale_id)
    {
        return $this->db->get_where('ingreso_caja_venta', array('venta_id' => $sale_id))->row();
    }
   
    /*Obtener lista de ingreso_caja para cargar la lista de dataTable*/
    public function get_cash_income_list($params = array())
    {   
        $user=$this->user_model->get_user_id(get_user_id_in_session());

        // Se cachea la informacion que se arma en query builder
        if($user->cargo_id==1){//ADMINISTRADOR
            $this->db->start_cache();
            $this->db->select('ic.*, c.nombre as nombre_caja')
                ->from('ingreso_caja ic, caja c')
                ->where('ic.caja_id=c.id')
                ->where('ic.sucursal_id',get_branch_id_in_session());
            $this->db->stop_cache();
        }else{//USUARIO NORMAL
            $cash_id=get_session_cash_id();
            $this->db->start_cache();
            $quey=$this->db->select('ic.*, c.nombre as nombre_caja')
                ->from('ingreso_caja ic, caja c')
                ->where('ic.caja_id=c.id')
                ->where('ic.sucursal_id',get_branch_id_in_session());
                if($cash_id==false){//CAJA NO APERTURADA
                    // $quey=$quey->where('ic.caja_id',$cash_id);
                    $quey=$quey->where('ic.caja_id',0);
                }else{//CAJA APERTURADA
                    $quey=$quey->where('ic.caja_id',$cash_id);
                }
            $this->db->stop_cache();
        }

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
            $this->db->order_by('ic.id', 'ASC');
        }

        if (array_key_exists('search', $params)) {
            $this->db->like('lower(detalle)', strtolower($params['search']));
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

    /*Funcion registrar nuevo ingreso_caja para validar los datos de la vista */
    public function register_cash_income()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE,
            'cash' => FALSE,
        );

        $validation_rules = array(
            array(
                'field' => 'cash_income_type',
                'label' => 'Tipo de Ingreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'add_detail',
                'label' => 'Detalle',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'add_date_income',
                'label' => 'Fecha Ingreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_bs',
                'label' => 'Monto Bs',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_sus',
                'label' => 'Monto Sus',
                'rules' => 'trim|required'
            )
        );

        if (verify_session()) {

            if(verify_cash_session()){
                $this->form_validation->set_rules($validation_rules);
                $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');
                // $this->form_validation->set_message('is_unique', 'El Nombre del tipo de almacen ingresado ya existe.');
                if ($this->form_validation->run() === TRUE) {
                    $data_cash_income = array(
                        'nro_transaccion'=>$this->get_number_transaction(),
                        'nro_ingreso'=> strval($this->get_number_transaction()),
                        'detalle' => $this->input->post('add_detail'),
                        'monto_bs' => $this->input->post('amount_bs'),
                        'monto_sus' => $this->input->post('amount_sus'),
                        'monto_tarjeta' => $this->input->post('amount_tarjeta'),
                        'monto_cheque' => $this->input->post('amount_cheque'),
                        'fecha_ingreso' => $this->input->post('add_date_income'),
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'estado' => ACTIVO,
                        'tipo_ingreso_caja_id'=> $this->input->post('cash_income_type'),
                        'caja_id'=> get_session_cash_id(),
                        'apertura_caja_id'=> get_session_cash_aperture_id(),
                        'sucursal_id' => get_branch_id_in_session(),
                        'user_created' => get_user_id_in_session(),
                        'user_updated' => get_user_id_in_session()
                    );

                    // Inicio de transacción
                    $this->db->trans_begin();

                    // Registrar a la base de datos de nuevo ingreso_caja
                    $this->_insert_cash_income($data_cash_income);
                    $cash_income_inserted = $this->_get_cash_income($data_cash_income);

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
   
    /*Funcion para actualizar ingreso_caja para validar los datos de la vista */
    public function edit_cash_income()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array()
        );
        $cash_income_id = $this->input->post('edit_id_cash_income');
        // Reglas de validacion
        $validation_rules = array(
            array(
                'field' => 'edit_detail',
                'label' => 'Detalle',
                'rules' => 'trim|required|alpha_numeric_spaces'
            ),
            array(
                'field' => 'edit_date_income',
                'label' => 'Fecha Ingreso',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_bs',
                'label' => 'Monto',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'amount_sus',
                'label' => 'Monto',
                'rules' => 'trim|required'
            )
        );

        // Pasar reglas de validacion como parámetro
        $this->form_validation->set_rules($validation_rules);
        $this->form_validation->set_error_delimiters('<label class="modal-error">', '</label>');

        if ($this->form_validation->run() === TRUE) {
            $data_cash_income = array(
                'detalle' => $this->input->post('edit_detail'),
                'monto_bs' => $this->input->post('amount_bs'),
                'monto_sus' => $this->input->post('amount_sus'),
                'monto_tarjeta' => $this->input->post('amount_tarjeta'),
                'monto_cheque' => $this->input->post('amount_cheque'),
                'fecha_ingreso' => $this->input->post('edit_date_income'),
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'tipo_ingreso_caja_id'=> $this->input->post('cash_income_type'),
                'caja_id'=> $this->input->post('cash'),
                'sucursal_id' => get_branch_id_in_session(),
                'user_updated' => get_user_id_in_session()
            );

            // Inicio de transacción
            $this->db->trans_begin();

            // Actualizar el ingreso_caja
            $this->_update_cash_income($cash_income_id, $data_cash_income);

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

    /*Funcion para desabilitar in Ingreso de Caja*/
    public function disable_cash_income($cash_income_id)
    {
        return $this->db->update(
            'ingreso_caja',
            ['estado' => 0,
             'fecha_modificacion' => date('Y-m-d H:i:s'),
             'user_updated' => get_user_id_in_session()],
            ['id' => $cash_income_id]
        );
    }
    public function get_number_transaction()
	{
		$this->db->select_max('nro_transaccion');
        $result = $this->db->get('ingreso_caja');
        if ($result->num_rows() > 0)
        {
            $res2 = $result->result_array();
            return $res2[0]['nro_transaccion'] + 1;
        }else{
            return 1;
        }
    }
    
    public function get_total_amount_cash_income($cash_id, $cash_aperture_id, $branch_office_id)
	{
        // return $currencys;
        $data=$this->db->select('c.id, 
        c.nombre, SUM(ic.monto_efectivo) as total_efectivo,
        SUM(ic.monto_venta) as total_venta, 
        SUM(ic.monto_bs) as total_bs,
        SUM(ic.monto_tarjeta) as total_tarjeta,
        SUM(ic.monto_cheque) as total_cheque,
        SUM(ic.monto_sus) as total_sus,
        SUM(ic.monto_cambio) as total_cambio')
        ->from('ingreso_caja ic, caja c')
        // ->where('ic.caja_id=c.id') //EFECTIVO
        ->where('ic.caja_id', $cash_id)
        ->where('ic.apertura_caja_id', $cash_aperture_id)
        // ->where('ic.fecha_registro>=', $date_open)
        // ->where('ic.fecha_registro<=', $date_close)
        ->where('ic.sucursal_id', $branch_office_id)
        ->where('ic.estado', ACTIVO)
        // ->where('ic.moneda_id', $currency_id)
        ->group_by('c.id')
        ->order_by('c.id', 'ASC')
        ->get();
        return $data->row();
    }
    
    public function get_cash_income_by_date($cash_id, $cash_aperture_id, $branch_office_id)
    {
        $this->db->select('ic.*, c.descripcion as nombre_caja, tic.descripcion as nombre_tipo_ingreso_caja')
            ->from('tipo_ingreso_caja tic, ingreso_caja ic, caja c')
            ->where('tic.id=ic.tipo_ingreso_caja_id')
            ->where('ic.caja_id=c.id')
            ->where('ic.estado', ACTIVO)
            ->where('c.id', $cash_id)
            ->where('ic.apertura_caja_id', $cash_aperture_id)
            // ->where('ic.fecha_registro>=', $date_open)
            // ->where('ic.fecha_registro<=', $date_close)
            ->where('ic.sucursal_id', $branch_office_id)
            ->order_by('ic.id', 'ASC');
        
        $result= $this->db->get()->result();

        // for ($i=0; $i < sizeof($result); $i++) {
        //     $result[$i]->monto_ingreso_caja=$this->get_amount_cash_income($result[$i]->id, $bank_account_cash_apertures);
        // }
        return $result;
    }

    //--------------------------------------------------------------------------------------------------------
    /*Funcion privada para insertar en la base de datos del nuevo ingreso_caja*/
    public function _insert_cash_income($cash_income)
    {
        return $this->db->insert('ingreso_caja', $cash_income);
    }
    
    /*Funcion privada para actualizar en la base de datos ingreso_caja*/
    public function _update_cash_income($cash_income_id, $data_cash_income)
    {
        $where = array('id' => $cash_income_id);
        return $this->db->update('ingreso_caja', $data_cash_income, $where);
    }

    public function _get_cash_income($cash_income)
	{
		return $this->db->get_where('ingreso_caja', $cash_income)->row();
	}
}