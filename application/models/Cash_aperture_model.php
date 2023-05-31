<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 28/6/2020
 * Time: 8:08 PM
 */

class Cash_aperture_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function get_cash_aperture_by_id($cash_aperture_id)
    {
        return $this->db->get_where('apertura_caja', array('id' => $cash_aperture_id))->row();
    }
    public function verify_cash_session($cash_aperture_id)
    {
        $cash_aperture=$this->get_cash_aperture_by_id($cash_aperture_id);
        if($cash_aperture->estado_caja==ACTIVO){
            return true;
        }else{
            $this->session->unset_userdata('cash_session');//eliminando datos de sesion de caja
            return false;
        }
        // return $this->db->get_where('apertura_caja', array('id' => $cash_aperture_id))->row();
    }
  
    public function register_cash_aperture()
    {
        $response = array(
            'success' => false,
            'login' => false
        );

        if (verify_session()) {
           
            //----------------------------------------------------------------------------------------------------------
            $cash_id = $this->input->post('aperture_cash_id');//ID DE LA CAJA SELECCIONADA
            $aperture_amount_bs = $this->input->post('aperture_amount_bs');        
            $aperture_amount_sus = $this->input->post('aperture_amount_sus'); 
          

            $name_cash = $this->cash_model->get_cash_id($cash_id)->descripcion;

            $cash_aperture['nro_transaccion_cierre_caja'] = $this->get_number_transaction_by_sucursal_id(get_branch_id_in_session());
            $cash_aperture['nro_cierre_caja'] = strval($this->get_number_transaction_by_sucursal_id(get_branch_id_in_session()));
            $cash_aperture['total_venta'] = 0;
            $cash_aperture['monto_apertura_bs'] = $aperture_amount_bs;
            $cash_aperture['monto_apertura_sus'] = $aperture_amount_sus;
            $cash_aperture['fecha_apertura'] = date('Y-m-d H:i:s');
            $cash_aperture['fecha_registro'] = date('Y-m-d H:i:s');
            $cash_aperture['fecha_modificacion'] = date('Y-m-d H:i:s');
            $cash_aperture['estado_caja'] = 1;//aperturado
            $cash_aperture['estado'] = 1;//activo
            $cash_aperture['caja_id'] = $cash_id;
            $cash_aperture['sucursal_id'] = get_branch_id_in_session();
            $cash_aperture["user_created"] = get_user_id_in_session();
			$cash_aperture["user_updated"] = get_user_id_in_session();
            
            $this->_insert_cash_aperture($cash_aperture);
            $cash_aperture_inserted = $this->_get_cash_aperture($cash_aperture);

            $response['cash_id'] = $cash_id;
            $response['name_cash'] = $name_cash;
            $response['cash_aperture_id'] = $cash_aperture_inserted->id;
            $this->set_sesion_cash($response);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $response['success'] = false;
            } else {
                $this->db->trans_commit();
                $response['success'] = true;
            }
        } else {
            $response['login'] = true;
        }

        return $response;
    }

  
    public function modify_cash_aperture()
    {
        $response = array(
            'success' => FALSE,
            'messages' => array(),
            'login' => FALSE
        );

        $cash_aperture_id = $this->input->post('cash_aperture_id');

        if (verify_session()) {

            $validation_rules = array(
                array(
                    'field' => 'edit_code',
                    'label' => 'El campo codigo no puede ser vacio',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, apertura_caja, codigo]|strtoupper",$cash_aperture_id)
                ),
                array(
                    'field' => 'edit_name',
                    'label' => 'El nombre de la apertura_caja debe ser unico y solo permite numeros y letras',
                    'rules' => sprintf("trim|required|is_unique_edit[%u, apertura_caja, nombre]|strtoupper", $cash_aperture_id)
                )

            );

            $this->form_validation->set_rules($validation_rules);
            $this->form_validation->set_error_delimiters('<p class="modal-error">', '</p>');

            if ($this->form_validation->run() === TRUE) {
                $data_cash_aperture = array(
                    'codigo' => strtoupper($this->input->post('edit_code')),
                    'nombre' => strtoupper($this->input->post('edit_name')),
                    'descripcion' => strtoupper($this->input->post('edit_description')),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'usuario_modificacion_id' => get_user_id_in_session(),
                    'sucursal_modificacion_id' => get_branch_id_in_session()
                );

                $this->db->trans_begin();
                $this->_update_cash_aperture($cash_aperture_id, $data_cash_aperture);

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

    public function disable_cash_aperture()
    {
        $id = $this->input->post('id');
        return $this->db->update(
            'apertura_caja',
            ['estado' => get_state_abm('INACTIVO')],
            ['id' => $id]
        );
    }

    /* Obtener todos las cajas que ya fueron aperturudas al menos una ves */
    public function get_active_cash_apertures($cash_id)
    {
        $this->db->select('ap.*, c.nombre as nombre_caja')
            ->from('apertura_caja ap, caja c')
            ->where('ap.caja_id=c.id')
            ->where('ap.estado_caja', ACTIVO)
            ->where('c.estado', ACTIVO)
            ->where('c.id', $cash_id);
        $this->db->order_by('ap.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_number_transaction_by_sucursal_id($sucursal_id)
    {
        $this->db->select_max('nro_transaccion_cierre_caja');
        $this->db->where('sucursal_id', $sucursal_id);
        $result = $this->db->get('apertura_caja');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_transaccion_cierre_caja + 1;
        } else {
            return 1;
        }
    }
    public function last_number_close_cash_by_sucursal_id($sucursal_id)
    {
        $this->db->select_max('nro_cierre_caja');
        $this->db->where('sucursal_id', $sucursal_id);
        $result = $this->db->get('apertura_caja');
        if ($result->num_rows() > 0) {
            $query = $result->row();
            return $query->nro_cierre_caja + 1;
        } else {
            return 1;
        }
    }
  
    /* Verifica en que estado se encuentra la caja (si fue Aperturada o Cerrada)*/
    public function check_cash()
    {
        $response = array(
            'login' => false,
            'check' => false
        );

        if (verify_session()) {
            $cash_id = $this->input->post('cash_id');
            $name_cash = $this->cash_model->get_cash_id($cash_id)->nombre;

            $this->db->select('c.id, c.descripcion, c.nombre, ac.id as apertura_caja_id')
                ->from('caja c, apertura_caja ac')
                ->where('ac.caja_id=c.id')
                ->where('ac.estado_caja', ACTIVO)
                ->where('c.estado', ACTIVO)
                ->where('ac.fecha_cierre ISNULL')
                ->where('ac.sucursal_id', get_branch_id_in_session())
                ->where('ac.sucursal_id', get_branch_id_in_session())
                ->where('ac.caja_id', $cash_id)
                ->where('ac.estado_caja', 1); //1 aperturado

            $result = $this->db->get();
            $data =  $result->row();
            $response['total_efective_bs']=0;
            $response['total_efective_sus']=0;
            if ($result->num_rows() > 0) {             // Caja aperturada
                $response['check'] = true;
                $response['cash_id'] = $cash_id;
                $response['name_cash'] = $name_cash;
                $response['cash_aperture_id'] = $data->apertura_caja_id;
            } else {             
                $response['check'] = false;
                $response['cash_id'] = $cash_id;
                $response['name_cash'] = $name_cash;
                $response['cash_aperture_id'] = 0;                     // Caja cerrada
                $cash_apaertures=$this->cash_model->get_cash_aperture_totals($cash_id);
                $size=sizeof($cash_apaertures)-1;
                if(sizeof($cash_apaertures)>0){
                    $response['total_efective_bs']=$cash_apaertures[$size]->monto_apertura_bs+$cash_apaertures[$size]->total_ingreso_bs-$cash_apaertures[$size]->total_egreso_bs;
                    $response['total_efective_sus']=$cash_apaertures[$size]->monto_apertura_sus+$cash_apaertures[$size]->total_ingreso_sus-$cash_apaertures[$size]->total_egreso_sus;
                }
            }

            /* Insertar en la sesion ID de ja caja y nombre de caja */
            $this->set_sesion_cash($response);

        } else {
            $response['login'] = true;
        }

        return $response;
    }
     /* Insertar en la sesion la caja */
     private function set_sesion_cash($response)
     {
         $cash = array(
             'cash_id' => $response['cash_id'],
             'name_cash' => $response['name_cash'],
             'cash_aperture_id' => $response['cash_aperture_id']
         );
         $this->session->set_userdata('cash_session', $cash);
     }
      /* Cerrar caja de ventas */
    public function close_cash()
    {
        try {
            $response = array(
                'success' => false,
                'login' => false
            );
    
            if (verify_session()) {
                $cash_aperture_id = $this->input->post('close_cash_aperture_id');
                $close_date_close = $this->input->post('close_date_close');
    
                $cash_total_bs = $this->input->post('cash_total_bs');  //contado
                $cash_total_sus = $this->input->post('cash_total_sus');//dolares
    
                $cash_income_total_tarjeta = $this->input->post('cash_income_total_tarjeta'); 
                $cash_income_total_cheque = $this->input->post('cash_income_total_cheque'); 
    
                $cash_income_total_bs = $this->input->post('cash_income_total_bs'); 
                $cash_income_total_sus = $this->input->post('cash_income_total_sus'); 
    
    
                $cash_output_total_bs = $this->input->post('cash_output_total_bs'); 
                $cash_output_total_sus = $this->input->post('cash_output_total_sus'); 
    
                $cash_aperture = $this->cash_model->get_cash_aperture_id($cash_aperture_id);
    
                $date_open = $cash_aperture->fecha_apertura;
                $date_close = date('Y-m-d H:i:s');
    
                // Inicio de transacción
                $this->db->trans_begin();
            
    
                $aperture_cash_output = $this->cash_output_model->get_cash_output_by_date($cash_aperture->caja_id, $cash_aperture->id, get_branch_id_in_session());
                $this->close_aperture_cash_output($aperture_cash_output);
                $aperture_cash_income = $this->cash_income_model->get_cash_income_by_date($cash_aperture->caja_id, $cash_aperture->id, get_branch_id_in_session());
                $this->close_aperture_cash_income($aperture_cash_income);
    
    
                $data_cash_aperture = array(
                    'total_venta' => $this->input->post("cash_total_bs"),
                    'monto_cierre_bs' => $this->input->post("cash_total_bs"),//contado
                    'monto_cierre_sus' => $this->input->post("cash_total_sus"),//dolares
                    'total_tarjeta' => $this->input->post("cash_income_total_tarjeta"),
                    'total_cheque' => $this->input->post("cash_income_total_cheque"),
                    'total_ingreso_bs' => $this->input->post("cash_income_total_bs"),
                    'total_ingreso_sus' => $this->input->post("cash_income_total_sus"),
                    'total_egreso_bs' => $this->input->post("cash_output_total_bs"),
                    'total_egreso_sus' => $this->input->post("cash_output_total_sus"),
                    'total_cambio' => $this->input->post("cash_income_total_cambio"),
                    'total_efectivo' => $this->input->post("cash_total_efective"),
                    'fecha_cierre' => $this->input->post("close_date_close"),
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'estado_caja' => CERRADO,
                    'user_updated' => get_user_id_in_session()
                    // 'estado' => CERRADO
                );
    
                $this->_update_cash_aperture($cash_aperture_id, $data_cash_aperture);
    
             
                // Obtener resultado de transacción
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $response['success'] = TRUE;
                    $this->session->unset_userdata('cash_session');//eliminando datos de sesion de caja
                  //  $response['data_cash'] = $data_cash;
                } else {
                    $this->db->trans_rollback();
                    $response['success'] = FALSE;
                }
            } else {
                $response['login'] = true;
            }
    
            return $response;
        } catch (\Throwable $th) {
            $this->db->trans_rollback();
            throw $th;
        }
    }

  
    public function close_aperture_cash_output($detail_cash_output)
    {
        foreach ($detail_cash_output as $cash_output){
            $this->db->set('estado_cierre', CERRADO);
            $this->db->where('id', $cash_output->id);
            $this->db->update('egreso_caja');
        }
    }
    public function close_aperture_cash_income($detail_cash_income)
    {
        foreach ($detail_cash_income as $cash_income){
            $this->db->set('estado_cierre', CERRADO);
            $this->db->where('id', $cash_income->id);
            $this->db->update('ingreso_caja');
        }
    }
   
    /* Seleccionar caja y permiso para cerrar caja */
    public function select_cash_and_permission_close_cash()
    {
        $response = array(
            'selected_cash' => false,
            'permission_close_cash' => false,
            'login' => false,
            'state_cash' => false
        );

        if (verify_session()) {
            $selected_cash = $this->check_selected_cash_to_close();

            if ($selected_cash == true) {
                $response['selected_cash'] = true;
                $response['cash_detail'] = $this->get_selected_cash_aperture(get_branch_id_in_session(), get_session_cash_id());

                if ($response['cash_detail'] != null) {
                    /* Pregunta si es el usuario es el que aperturo la caja o el administrador( Si tiene permiso de cerrar la caja el que entro en sesion )*/
                    // Solo el administrador puede cerrar caja
                    if (get_user_type_in_session() == 2) { //administrador general
                        $response['permission_close_cash'] = true;
                    }
                } else {
                    $response['state_cash'] = true;
                }

            }

        } else {
            $response['login'] = true;
        }
        return $response;
    }
    /* Obtener detalla de caja de venta seleccionado */
    public function get_selected_cash_aperture($branch_office_id, $cash_id)
    {
        $this->db->select('c.id, c.descripcion, ac.id as apertura_caja_id')
                ->from('caja c, apertura_caja ac')
                ->where('ac.caja_id=c.id')
                //  ->where('ac.id=am.apertura_caja_id')
                ->where('ac.estado_caja', ACTIVO)
                ->where('c.estado', ACTIVO)
                ->where('ac.fecha_cierre ISNULL')
                //->where('am.monto_cierre ISNULL')
                ->where('ac.sucursal_id', $branch_office_id)
                ->where('c.sucursal_id', $branch_office_id)
                ->where('ac.caja_id', $cash_id)
                ->where('ac.estado_caja', 1); //1 aperturado

        return $this->db->get()->row();
    }
    public function check_selected_cash_to_close()
    {
        $cash_id = get_session_cash_id();
        if ($cash_id == false) {
            return false;    // No tiene caja seleccionada
        } else {
            return true;     // Si tiene caja seleccionada
        }
    }
    //------------------------------------------------------------------------------
    public function _get_cash_aperture($data_cash_aperture)
    {
        return $this->db->get_where('apertura_caja', $data_cash_aperture)->row();
    }

    private function _insert_cash_aperture($cash_aperture)
    {
        return $this->db->insert('apertura_caja', $cash_aperture);
    }

    private function _update_cash_aperture($cash_aperture_id, $data_cash_aperture)
    {
        $where = array('id' => $cash_aperture_id);
        return $this->db->update('apertura_caja', $data_cash_aperture, $where);
    }
}