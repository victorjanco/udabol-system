<?php
/**
 * Created by Visual Studio Code.
 * User: Victor Janco
 * Date: 31/03/2020
 * Time: 16:02 PM
 */
class Cash_output extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cash_output_model');
        $this->load->model('cash_model');
        $this->load->model('cash_output_type_model');
        $this->load->model('cash_income_model');
        $this->load->model('user_model');
        $this->load->model('office_model');
    }

    public function index()
    {
        template('cash_output/index');
    }
    public function new_cash_output(){
        
        $response['date_close']=date('Y-m-d H:i:s');
        $response['cash_output_types'] = $this->cash_output_type_model->get_cash_output_type_enable2();
        $response['cash_total_bs']=0;
        $response['cash_total_sus']=0;
        if(get_session_cash_id()!=0 && get_session_cash_aperture_id()!=0){
            $cash_aperture = $this->cash_model->get_cash_aperture_id(get_session_cash_aperture_id());
            $cash_income_total = $this->cash_income_model->get_total_amount_cash_income(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $cash_income_total_bs = isset($cash_income_total->total_bs)? $cash_income_total->total_bs:0;
            $cash_income_total_sus = isset($cash_income_total->total_sus)? $cash_income_total->total_sus:0;
            $cash_income_cambio = isset($cash_income_total->total_cambio)? $cash_income_total->total_cambio:0;

            $cash_output_total = $this->cash_output_model->get_total_amount_cash_output(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $cash_output_total_bs = isset($cash_output_total->total_bs)? $cash_output_total->total_bs:0;
            $cash_output_total_sus = isset($cash_output_total->total_sus)? $cash_output_total->total_sus:0;
            
            $response['cash_total_bs'] = $cash_aperture->monto_apertura_bs + $cash_income_total_bs - $cash_output_total_bs - $cash_income_cambio;  
            $response['cash_total_sus'] = $cash_aperture->monto_apertura_sus + $cash_income_total_sus - $cash_output_total_sus;  
        }
        $response['cash_id']=get_session_cash_id(); //caja aperturada
        $response['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id
        template('cash_output/new_cash_output', $response);
    }
    public function view(){
        $id_cash_output = $this->input->post('id');
        $response['cashs'] = $this->cash_model->get_cash_enable();
        $response['cash_output_types'] = $this->cash_output_type_model->get_cash_output_type_enable();
        $response['cash_output'] = $this->cash_output_model->get_cash_output_id($id_cash_output);
        
        template('cash_output/view_cash_output',$response);
    }
    public function edit(){
        $id_cash_output = $this->input->post('id');
        $response['cashs'] = $this->cash_model->get_cash_enable();
        $response['cash_output_types'] = $this->cash_output_type_model->get_cash_output_type_enable2();
        $response['cash_output'] = $this->cash_output_model->get_cash_output_id($id_cash_output);
        $response['date_close']=date('Y-m-d H:i:s');
        $response['cash_total_bs']=0;
        $response['cash_total_sus']=0;
        if(get_session_cash_id()!=0 && get_session_cash_aperture_id()!=false){
            $cash_income_total = $this->cash_income_model->get_total_amount_cash_income(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $cash_income_total_bs = isset($cash_income_total->total_bs)? $cash_income_total->total_bs:0;
            $cash_income_total_sus = isset($cash_income_total->total_sus)? $cash_income_total->total_sus:0;

            $cash_output_total = $this->cash_output_model->get_total_amount_cash_output(get_session_cash_id(), get_session_cash_aperture_id(), get_branch_id_in_session());
            $cash_output_total_bs = isset($cash_output_total->total_bs)? $cash_output_total->total_bs:0;
            $cash_output_total_sus = isset($cash_output_total->total_sus)? $cash_output_total->total_sus:0;
            
            $response['cash_total_bs'] = $cash_income_total_bs - $cash_output_total_bs;  
            $response['cash_total_sus'] = $cash_income_total_sus - $cash_output_total_sus; 
        }
        $response['cash_id']=get_session_cash_id(); //caja aperturada
        $response['cash_aperture_id']=get_session_cash_aperture_id(); //aperturada_caja_id
        template('cash_output/edit_cash_output',$response);
    }
    /*Mandar los parametros al modelo para registrar el ingreso de caja*/
    public function register_cash_output()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_output_model->register_cash_output();
        } else {
            show_404();
        }
    }

    /*Mandar los parametros al modelo para modificar el Ingreso de Caja*/
    public function edit_cash_output()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->cash_output_model->edit_cash_output();
        } else {
            show_404();
        }
    }

    /*Para eliminar un Ingreso de Caja seleccionado de la lista*/
    public function disable_cash_output()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            echo $this->cash_output_model->disable_cash_output($id);
        } else {
            show_404();
        }
    }

    /*Obtener todos ingresos de cajas*/
    public function get_cash_output_enable()
    {
        if ($this->input->is_ajax_request()) {
            $cash_output_list=$this->cash_output_model->get_cash_output_enable();
            echo json_encode($cash_output_list);
        } else {
            show_404();
        }
    }

    /*Para cargar la lista de ingresos de caja en el dataTable*/
    public function get_cash_output_list()
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

            echo json_encode($this->cash_output_model->get_cash_output_list($params));
        } else {
            show_404();
        }
    }

    public function print_cash_output()
    {
        $cash_output_id = $this->input->post('id');

        $data_cash_output = $this->cash_output_model->get_print_cash_output($cash_output_id);
        /* Datos generales para generar PDF */
        // Nota: el ancho total de la pagina es 192

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
        $var_img = base_url() . 'assets/images/'.$data_cash_output['branch_office']->imagen;
        $this->pdf->Image($var_img, 13, 10, 80, 28);

        /*  ENCABEZADO: DATOS SUCURSAL */
        // Primera fila
        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(130, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(60, $height, utf8_decode($data_cash_output['company']->nombre_empresa), $no_frame, 0, $align_center);

        // Segunda fila
        $this->pdf->Ln(5);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(95, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(30, $height, utf8_decode(' RECIBO GASTOS'), $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(70, $height, utf8_decode($data_cash_output['branch_office']->nombre_comercial), $no_frame, $align_center);

        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(000, 000, 000);
        $this->pdf->Cell(107, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(1, $height, 'Nro. ', $no_frame, 0, $align_center);
        $this->pdf->Cell(12, $height, utf8_decode($data_cash_output['cash_output']->nro_transaccion), $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, '', $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(79, $height, utf8_decode($data_cash_output['branch_office']->direccion), $no_frame, $align_center);

        $this->pdf->Ln(0);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, '', $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(70, $height - 1, utf8_decode('telf. ' . $data_cash_output['branch_office']->telefono), $no_frame, $align_center);

        $this->pdf->Ln(0);
        $this->pdf->SetFont($style_font, $style_border, $style_size + 4);
        $this->pdf->SetTextColor(248, 000, 000);
        $this->pdf->Cell(85, $height, '', $no_frame, 0, $align_center);
        $this->pdf->Cell(40, $height, '', $no_frame, 0, $align_center);
        $this->pdf->SetFont($style_font, '', $style_size + 1);
        $this->pdf->SetTextColor(0, 000, 000);
        $this->pdf->MultiCell(70, $height - 1, utf8_decode($data_cash_output['branch_office']->ciudad), $no_frame, $align_center);

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
        $this->pdf->Cell(70, $height,  ': ' . utf8_decode($data_cash_output['cash']->descripcion), $top);

        $year = substr($data_cash_output['cash_output']->fecha_egreso, 0, 4);
        $month = substr($data_cash_output['cash_output']->fecha_egreso, 5, 2);
        $day = substr($data_cash_output['cash_output']->fecha_egreso, 8, 2);
        $egress_date = $day . ' de ' . get_month($month) . ' del ' . $year;

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(22, $height, utf8_decode('Fecha Egreso'), $top);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(77, $height, ': ' . utf8_decode($data_cash_output['branch_office']->ciudad) . ', ' . $egress_date, $top);
        $this->pdf->Cell(1, $height, '', $top . $right);

        $this->pdf->Ln($newline);
        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(22, $height, utf8_decode('Tipo Egreso'), $left . $below);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(70, $height,  ': ' . utf8_decode($data_cash_output['cash_output_type']->descripcion), $below);

        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(22, $height, utf8_decode('Emitido '),  $below);
        $this->pdf->SetFont($style_font, '', $style_size);
        $this->pdf->Cell(78, $height,  ': ' . utf8_decode($data_cash_output['user']->usuario), $right. $below);

        // DETALLE ITEMS
        $this->pdf->Ln($newline + 2);
        $this->pdf->SetFont($style_font, $style_border, $style_size);
        $this->pdf->Cell(192, $height, 'DETALLE', $frame, 0, $align_center);
        $this->pdf->Ln($newline + 1);

        $this->pdf->Cell(10, $height, "NRO", $frame, 0, $align_center);
        $this->pdf->Cell(118, $height, "DESCRIPCION", $frame, 0, $align_center);
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
                utf8_decode($data_cash_output['cash_output']->detalle),
                utf8_decode($data_cash_output['cash_output']->monto_bs),
                utf8_decode($data_cash_output['cash_output']->monto_sus)
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
    }
}