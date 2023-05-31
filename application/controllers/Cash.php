<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 16/03/2020
 * Time: 16:02 PM
 */
class Cash extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cash_model');
        $this->load->model('cash_income_model');
        $this->load->model('cash_output_model');
        $this->load->model('cash_aperture_model');
        $this->load->model('office_model');
        $this->load->model('user_model');
        $this->load->model('cash_aperture_model');
        $this->load->model('change_type_model');
    }

    public function index()
    {
        // $ci = get_instance();
        // $data_option=[];
        // if(isset($ci->session->userdata('option')['cash'])){
            // $data_option = $ci->session->userdata('option')['cash'];
            // $response['option']=json_encode($data_option);
            // $response['currencys']=$this->currency_model->get_currency_enable();
            template('cash/index');
        // }
    }

    /*Mandar los parametros al modelo para registrar la caja*/
    public function register_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_model->register_cash();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar la caja*/
    public function modify_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_model->modify_cash();
        } else {
            show_404();
        }
    }

    /*Para eliminar una caja seleccionado de la lista*/
    public function disable_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_model->disable_cash();
        } else {
            show_404();
        }
    }
    /*Obtener todas las almacenes activos especial para cargar combos o autocompletados*/

    public function get_cash_enable()
    {
        if ($this->input->is_ajax_request()) {
            $cash_list=$this->cash_model->get_cash_enable();
            echo json_encode($cash_list);
        } else {
            show_404();
        }
    }

     /*Obtener todas las cuentas bancarias activas de la caja*/
     public function get_bank_account_cash_enable()
     {
         if ($this->input->is_ajax_request()) {
             $response['cash_aperture']=false;
             $cash_id = $this->input->post('id');
             $cash=$this->cash_model->get_cash_id($cash_id);
             $cash_apaertures=$this->cash_aperture_model->get_active_cash_apertures($cash_id);//toda la aperturas que hubiera tenido la caja.
             $response['bank_account_list']=$this->bank_account_model->get_bank_account_by_cash_id($cash_id);
             if(sizeof($cash_apaertures) > 0){ //caja aperturada
                $response['cash_aperture']=true;
             }
             echo json_encode($response);
         } else {
             show_404();
         }
     }

    /*Para cargar la lista de caja en el dataTable*/
    public function get_cash_list()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order
            );

            echo json_encode($this->cash_model->get_cash_list($params));
        } else {
            show_404();
        }
    }
    /* Verifica en que estado se encuentra la caja (si fue Aperturada o Cerrada)*/
    public function check_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->cash_aperture_model->check_cash());
        } else {
            show_404();
        }
    }
    /* Aperturar caja*/
    public function cash_aperture()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->cash_aperture_model->register_cash_aperture());
        } else {
            show_404();
        }
    }
    /* Obtiene las cajas habilitadas para escoger que caja empezara hacer ventas */
    public function get_cash_enabled()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->cash_model->get_cash_enabled());
        } else {
            show_404();
        }
    }
    public function get_cash_by_branch()
    {
        if ($this->input->is_ajax_request()) {
            $branch_office_id= $this->input->post('branch_id'); 
            echo json_encode($this->cash_model->get_cash_by_branch($branch_office_id));
        } else {
            show_404();
        }
    }

    /* Seleccionar caja y permiso para cerrar caja */
    public function select_cash_and_permission_close_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->cash_aperture_model->select_cash_and_permission_close_cash());
        } else {
            show_404();
        }
    }
    
    /* Muestra los datos para cerrar la caja */
    public function show_closing_cash()
    {
        // if (verify_session()) {
            $response=$this->get_data_closing_cash();
            template('cash/close_cash', $response);
    }

    public function get_data_closing_cash()
    {
        if(get_session_cash_id()!=0 && get_session_cash_aperture_id()!=FALSE){
            $cash_aperture=$this->cash_model->get_cash_aperture_id(get_session_cash_aperture_id());
            $response['date_close']=date('Y-m-d H:i:s');
            $response['cash']=$this->cash_model->get_cash_id(get_session_cash_id());
            $response['cash_aperture']=$cash_aperture;
            $response['change_type']= $this->change_type_model->get_first();
       
            $response['cash_incomes']=$this->cash_income_model->get_cash_income_by_date(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $response['cash_outputs']=$this->cash_output_model->get_cash_output_by_date(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
    
            $cash_income_totals = $this->cash_income_model->get_total_amount_cash_income(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            // $cash_income_total = isset($cash_income_totals->total)? $cash_income_totals->total:0;
            $cash_income_total_efectivo = isset($cash_income_totals->total_efectivo)? $cash_income_totals->total_efectivo:0;
            $cash_income_total_bs = isset($cash_income_totals->total_bs)? $cash_income_totals->total_bs:0;
            $cash_income_total_tarjeta = isset($cash_income_totals->total_tarjeta)? $cash_income_totals->total_tarjeta:0;
            $cash_income_total_cheque = isset($cash_income_totals->total_cheque)? $cash_income_totals->total_cheque:0;
            $cash_income_total_sus = isset($cash_income_totals->total_sus)? $cash_income_totals->total_sus:0;
            $cash_income_total_cambio = isset($cash_income_totals->total_cambio)? $cash_income_totals->total_cambio:0;
            $cash_income_total_venta = isset($cash_income_totals->total_venta)? $cash_income_totals->total_venta:0;
    
            $cash_output_total = $this->cash_output_model->get_total_amount_cash_output(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $cash_output_total_bs = isset($cash_output_total->total_bs)? $cash_output_total->total_bs:0;
            $cash_output_total_sus = isset($cash_output_total->total_sus)? $cash_output_total->total_sus:0;
            
            $response['cash_total_bs'] = $cash_income_total_bs + $cash_aperture->monto_apertura_bs - $cash_output_total_bs; //monto_cierre_bs
            $response['cash_total_sus'] = $cash_income_total_sus + $cash_aperture->monto_apertura_sus - $cash_output_total_sus; //monto_cierre_sus
    
           
            $response['cash_income_total_efectivos']=$cash_income_total_efectivo;
            // $response['cash_income_total_bs']=$cash_income_total_bs - $cash_income_total_cambio; //bs
            $response['cash_income_total_bss']=$cash_income_total_bs;
            $response['cash_income_total_tarjetas']=$cash_income_total_tarjeta;
            $response['cash_income_total_cheques']=$cash_income_total_cheque;
            $response['cash_income_total_sus']=$cash_income_total_sus; //sus
            $response['cash_income_total_cambios']=$cash_income_total_cambio;
            $response['cash_income_total_venta']=$cash_income_total_venta;
    
            $response['cash_output_total_bs']=$cash_output_total_bs;
            $response['cash_output_total_sus']=$cash_output_total_sus;

            $response['cash_total_efective'] =  $response['cash_total_bs'] + ($response['cash_total_sus'] * $response['change_type']->monto_cambio_venta) - $cash_income_total_cambio;
            $response['cash_total_general'] =  $response['cash_total_efective'] + $cash_income_total_tarjeta + $cash_income_total_cheque;

        }
       
        $response['cash_id']=get_session_cash_id(); //caja aperturada
        $response['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id
        return $response;
    }

    // Permiso para cerrar caja solamente si es Administrador
    public function get_permission_to_close_cash()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->cash_model->get_permission_to_close_cas_by_administrador());
        } else {
            show_404();
        }
    }
    /* Cerrar caja de ventas */
    public function close_cash()
    {
        try {
            if ($this->input->is_ajax_request()) {
                echo json_encode($this->cash_aperture_model->close_cash());
            } else {
                show_404();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    /*Obtener todas las cuentas bancarias activas de la caja*/
    public function get_bank_account_cash_and_cash_central()
    {
        if ($this->input->is_ajax_request()) {
            $cash_id = $this->input->post('cash_id');//DESTINO
            $cash_central_id= $this->cash_model->get_first()->id; //ORIGEN
            $cash_apaertures=$this->cash_aperture_model->get_active_cash_apertures($cash_id);//toda la aperturas que hubiera tenido la caja.
            $response['bank_account_cash_origin']=$this->bank_account_model->get_bank_account_by_cash_id($cash_central_id);//CUENTAS BANCARIAS DE LA CAJA CENTRAL
            $response['bank_account_cash_destination']=$this->bank_account_model->get_bank_account_by_cash_id($cash_id);//CUENTAS BANCARIAS DE LA CAJA A APERTURAR
            echo json_encode($response);
        } else {
            show_404();
        }
    }
    public function prubas_consultas()
    {
        // $cash_aperture=$this->cash_model->get_cash_aperture_id(get_session_cash_aperture_id());
        // //$cash_id, $date_open, $date_close, $branch_office_id
        // $response['date_close']=date('Y-m-d H:i:s');
        // $response['currencys']=$this->currency_model->get_currency_enable();
        // $response['cash_incomes']=$this->cash_income_model->get_cash_income_by_date(get_session_cash_id(), $cash_aperture->fecha_apertura, $response['date_close'], get_branch_id_in_session());
        // $response['cash_outputs']=$this->cash_output_model->get_cash_output_by_date(get_session_cash_id(), $cash_aperture->fecha_apertura, $response['date_close'], get_branch_id_in_session());
        // $response['totales']=$this->cash_income_model->get_total_amount_cash_income(get_session_cash_id(), $cash_aperture->fecha_apertura, $response['date_close'], get_branch_id_in_session());
        // $response['cash_output_totals']=$this->cash_output_model->get_total_amount_cash_output(get_session_cash_id(), $cash_aperture->fecha_apertura, $response['date_close'], get_branch_id_in_session());
        // $response['amount_aperture']=$this->cash_model->get_amount_aperture(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
        //  echo json_encode($response['cash_output_totals']);
    }

    public function print_close_cash()
    {

        // $data_cash_output =  $response=$this->get_data_closing_cash();
        /* Datos generales para generar PDF */
        // Nota: el ancho total de la pagina es 192
        if ($this->input->post()) {

            $data_cash_aperture_close['cash_total_bs'] = $this->input->post('cash_total_bs');  //contado
            $data_cash_aperture_close['cash_total_sus'] = $this->input->post('cash_total_sus');//dolares

            $data_cash_aperture_close['cash_income_total_tarjeta'] = $this->input->post('cash_income_total_tarjeta'); 
            $data_cash_aperture_close['cash_income_total_cheque'] = $this->input->post('cash_income_total_cheque'); 

            $data_cash_aperture_close['cash_income_total_bs']= $this->input->post('cash_income_total_bs'); 
    
            $data_cash_aperture_close['cash_income_total_sus']= $this->input->post('cash_income_total_sus'); 


            $data_cash_aperture_close['cash_output_total_bs'] = $this->input->post('cash_output_total_bs'); 
            $data_cash_aperture_close['cash_output_total_sus'] = $this->input->post('cash_output_total_sus'); 

            $data_cash_aperture_close['close_date_close'] = $this->input->post('close_date_close'); 
            $data_cash_aperture_close['close_date_open'] = $this->input->post('close_date_open'); 

            $close_branch_office_id = $this->input->post('close_branch_office_id'); 
            $close_cash_id = $this->input->post('close_cash_id'); 
            $close_cash_aperture_id = $this->input->post('close_cash_aperture_id'); 
            $close_user_id = $this->input->post('close_user_id'); 


            $data_cash_aperture_close['cash_aperture'] = $this->cash_model->get_cash_aperture_id($close_cash_aperture_id);
            $data_cash_aperture_close['branch_office'] = $this->office_model->get_branch_office_id($close_branch_office_id);
            $data_cash_aperture_close['user'] = $this->user_model->get_user_id($close_user_id);
            $data_cash_aperture_close['cash'] = $this->cash_model->get_cash_id($close_cash_id);
            $data_cash_aperture_close['company'] = $this->company_model->get_company();

            $height = 5;
            $newline = 5;

            // Bordes
            $no_frame = 0;
            $frame = 1;
            $left = 'L';
            $right = 'R';
            $top = 'T';
            $below = 'B';

            // Alinear
            $align_left = 'L';
            $align_center = 'C';
            $align_right = 'R';

            // Estilo
            $style_font = 'Arial';
            $style_border = 'B';
            $style_size = 8;

            $this->load->library('pdf');
            $this->pdf = new Pdf('P', 'mm', 'Legal');
            $this->pdf->AddPage();
            $this->pdf->AliasNbPages();

            /* TITULO */
            $this->pdf->SetTitle("RECIBO");

            /* IMAGEN LOGO */
            $var_img = base_url() . 'assets/images/'.$data_cash_aperture_close['branch_office']->imagen;
            $this->pdf->Image($var_img, 13, 10, 80, 28);

            /*  ENCABEZADO: DATOS SUCURSAL */
            // Primera fila
            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(130, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(60, $height, utf8_decode($data_cash_aperture_close['company']->nombre_empresa), $no_frame, 0, $align_center);

            // Segunda fila
            $this->pdf->Ln(5);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(95, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(30, $height, utf8_decode('CIERRE DE CAJA'), $no_frame, 0, $align_center);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 1);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, $height, utf8_decode($data_cash_aperture_close['branch_office']->nombre_comercial), $no_frame, $align_center);

            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(000, 000, 000);
            $this->pdf->Cell(107, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(1, $height, 'Nro. ', $no_frame, 0, $align_center);
            $this->pdf->Cell(12, $height, utf8_decode($data_cash_aperture_close['cash_aperture']->nro_transaccion_cierre_caja), $no_frame, 0, $align_center);
            $this->pdf->SetFont($style_font, '', $style_size + 1);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(79, $height, utf8_decode($data_cash_aperture_close['branch_office']->direccion), $no_frame, $align_center);

            $this->pdf->Ln(0);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
            $this->pdf->SetFont($style_font, '', $style_size + 1);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, $height - 1, utf8_decode('telf. ' . $data_cash_aperture_close['branch_office']->telefono), $no_frame, $align_center);

            $this->pdf->Ln(0);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
            $this->pdf->SetFont($style_font, '', $style_size + 1);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, $height - 1, utf8_decode($data_cash_aperture_close['branch_office']->ciudad), $no_frame, $align_center);

            // Tercera fila
            $this->pdf->Ln(0);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
            $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);

            // Cuarta fila

            // Quinta fila
            $this->pdf->Ln(4);
            $this->pdf->Cell(85, $height, '', $no_frame);
            $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
            $this->pdf->SetTextColor(000, 000, 000);
            $this->pdf->Ln($newline + 1);

            // DATOS

            //$this->pdf->Ln($newline + 2);
            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(192, $height, utf8_decode('DATOS'), $frame, 0, $align_center);
            $this->pdf->Ln($newline + 1);

            $this->pdf->SetFont($style_font, $style_border, $style_size + 1);
            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(22, $height, utf8_decode('Caja'), $top . $left);
            $this->pdf->SetFont($style_font, '', $style_size);
            $this->pdf->Cell(70, $height,  ': ' . utf8_decode($data_cash_aperture_close['cash']->descripcion), $top);

            $year = substr($data_cash_aperture_close['cash_aperture']->fecha_apertura, 0, 4);
            $month = substr($data_cash_aperture_close['cash_aperture']->fecha_apertura, 5, 2);
            $day = substr($data_cash_aperture_close['cash_aperture']->fecha_apertura, 8, 2);
            $aperture_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $year = substr($data_cash_aperture_close['close_date_close'], 0, 4);
            $month = substr($data_cash_aperture_close['close_date_close'], 5, 2);
            $day = substr($data_cash_aperture_close['close_date_close'], 8, 2);
            $close_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(22, $height, utf8_decode('Fecha Apertura'), $top);
            $this->pdf->SetFont($style_font, '', $style_size);
            $this->pdf->Cell(77, $height, ': ' . $aperture_date, $top);
            $this->pdf->Cell(1, $height, '', $top . $right);

            $this->pdf->Ln($newline);
            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(22, $height, utf8_decode('Usuario'), $left . $below);
            $this->pdf->SetFont($style_font, '', $style_size);
            $this->pdf->Cell(70, $height,  ': ' . utf8_decode($data_cash_aperture_close['user']->usuario), $below);

            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(22, $height, utf8_decode('Fecha Cierre'),  $below);
            $this->pdf->SetFont($style_font, '', $style_size);
            $this->pdf->Cell(78, $height,  ': ' . $close_date, $right. $below);

            // DETALLE ITEMS
            $this->pdf->Ln($newline + 2);
            $this->pdf->SetFont($style_font, $style_border, $style_size);
            $this->pdf->Cell(192, $height, 'BALANCE DE CAJA', $frame, 0, $align_center);
            $this->pdf->Ln($newline + 1);

            $this->pdf->Cell(10, $height, "NRO", $frame, 0, $align_center);
            $this->pdf->Cell(118, $height, "TIPO", $frame, 0, $align_center);
            $this->pdf->Cell(32, $height, "MONTO BS", $frame, 0, $align_center);
            $this->pdf->Cell(32, $height, "MONTO SUS", $frame, 0, $align_center);
            $this->pdf->Ln($newline);
            $nro = 1;

            $this->pdf->SetFont($style_font, '', $style_size);

            $number_rows = 0;
            $number_items = 0;
            $total_detail = 10;

            $sum_bs = 0;
            $sum_sus = 0;

            $this->pdf->SetWidths(array(10, 118, 32, 32));
            $this->pdf->SetAligns(array($align_center, $align_left, $align_right, $align_right));
            $total_pagado = 0;

                $number_rows++;

                $this->pdf->Row(array(
                    utf8_decode($number_rows),
                    utf8_decode('Monto Apertura'),
                    utf8_decode($data_cash_aperture_close['cash_aperture']->monto_apertura_bs),
                    utf8_decode($data_cash_aperture_close['cash_aperture']->monto_apertura_sus)
                ));
                $number_rows++;
                $this->pdf->Row(array(
                    utf8_decode($number_rows),
                    utf8_decode('Monto Efectivo'),
                    utf8_decode($data_cash_aperture_close['cash_income_total_bs']),
                    utf8_decode($data_cash_aperture_close['cash_income_total_sus'])
                ));
                $number_rows++;
                $this->pdf->Row(array(
                    utf8_decode($number_rows),
                    utf8_decode('Monto Tarjeta'),
                    utf8_decode($data_cash_aperture_close['cash_income_total_tarjeta']),
                    utf8_decode('0.00')
                ));
                $number_rows++;
                $this->pdf->Row(array(
                    utf8_decode($number_rows),
                    utf8_decode('Monto Cheque'),
                    utf8_decode($data_cash_aperture_close['cash_income_total_cheque']),
                    utf8_decode('0.00')
                ));
                $number_rows++;
                $this->pdf->Row(array(
                    utf8_decode($number_rows),
                    utf8_decode('Monto Egresos'),
                    utf8_decode($data_cash_aperture_close['cash_output_total_bs']),
                    utf8_decode($data_cash_aperture_close['cash_output_total_sus'])
                ));
            
                $this->pdf->Row(array(
                    utf8_decode(''),
                    utf8_decode('TOTALES'),
                    utf8_decode($data_cash_aperture_close['cash_total_bs']),
                    utf8_decode($data_cash_aperture_close['cash_total_sus'])
                ));
                $nro = $nro + 1;
                $number_items = $number_items + 1;

            $this->pdf->ln(18);

            $this->pdf->Cell(95, 5, '-------------------------------- ', '', 0, 'C');
            $this->pdf->Cell(75, 5, '-------------------------------- ', '', 0, 'C');
            $this->pdf->ln(2);
            $this->pdf->Cell(95, 5, 'Entregue Conforme ', '', 0, 'C');
            $this->pdf->Cell(75, 5, 'Recibi Conforme', '', 0, 'C');


            $print_day = date('Y-m-d');
            $print_hour = date('H:i:s');
            $year = substr($print_day, 0, 4);
            $month = substr($print_day, 5, 2);
            $day = substr($print_day, 8, 2);
            $print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(22, 5, 'Usuario Impresion:', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

            $this->pdf->Output("Recibo de Pago Nro. "  . ".pdf", 'I');
        } else {
            show_404();
        }
    }
}
