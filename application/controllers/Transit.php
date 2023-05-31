<?php

/**
 * Created by PhpStorm.
 * User: Ing. Reyes Fuentes Renato (Green Ranger)
 * Date: 24/05/2019
 * Time: 10:15 AM
 */

class Transit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('transit_model');
        $this->load->model('user_model');
        $this->load->model('brand_model');
        $this->load->model('model_model');
        $this->load->model('product_model');
        $this->load->model('warehouse_model');
        //$this->load->model('reception_model');
    }

    public function index()
    {
        $data['list_type_reason'] = $this->transit_model->get_type_reason_enable();
        $data['list_brand'] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();

        template('transit/index', $data);
    }

    public function get_transit_report()
    {
        if ($this->input->is_ajax_request()) {
            // Se recuperan los parametros enviados por datatable
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            $transit_date_start_loan = $this->input->post('transit_date_start_loan');
            $transit_date_end_loan = $this->input->post('transit_date_end_loan');
            $transit_date_start_return = $this->input->post('transit_date_start_return');
            $transit_date_end_return = $this->input->post('transit_date_end_return');
            $transit_reception_code = $this->input->post('transit_reception_code');
            $transit_state = $this->input->post('transit_state');
            $transit_type_reason = $this->input->post('transit_type_reason');
            $transit_brand = $this->input->post('transit_brand');
            $transit_model = $this->input->post('transit_model');
            $transit_product = $this->input->post('transit_product');

            // Se almacenan los parametros recibidos en un array para enviar al modelo
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'transit_date_start_loan' => $transit_date_start_loan,
                'transit_date_end_loan' => $transit_date_end_loan,
                'transit_date_start_return' => $transit_date_start_return,
                'transit_date_end_return' => $transit_date_end_return,
                'transit_reception_code' => $transit_reception_code,
                'transit_state' => $transit_state,
                'transit_type_reason' => $transit_type_reason,
                'transit_brand' => $transit_brand,
                'transit_model' => $transit_model,
                'transit_product' => $transit_product
            );

            echo json_encode($this->transit_model->get_transit_report($params));

        } else {
            show_404();
        }
    }

    public function output()
    {
        template('transit/transit_output', null);
    }

    public function new_transit_output()
    {
        $data['list_type_reason'] = $this->transit_model->get_type_reason_enable();
        $data['delivery'] = $this->user_model->get_user_id(get_user_id_in_session());
        $data['applicant_users'] = $this->user_model->get_list_users_repairman();
        $data['origin_warehouse'] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        $data['destination_warehouse'] = $this->warehouse_model->get_warehouse_technical(get_branch_id_in_session());

        template('transit/new_transit_output', $data);
    }

    public function get_order_work_autocomplete()
    {
        if ($this->input->is_ajax_request()) {
            $dato = $this->input->post('name_startsWith');
            $tipo = $this->input->post('type');
            $ENTREGADO = ENTREGADO;
            $CONCLUIDO = CONCLUIDO;
            switch ($tipo) {
                case 'code_work':
                    $this->db->select('*')
                        ->from('vista_lista_recepcion')
                        ->where('sucursal_id', get_branch_id_in_session())
                        ->where("orden_trabajo_id not in (select id from orden_trabajo where estado_trabajo='$ENTREGADO' OR estado_trabajo='$CONCLUIDO')")
                        ->group_start()
                        ->like('lower(codigo_recepcion)', strtolower($dato))
                        ->group_end()
                        ->order_by('id', 'ASC');
                    $res = $this->db->get();
                    if ($res->num_rows() > 0) {
                        foreach ($res->result_array() as $row) {
                            $data[$row['codigo_recepcion'] . '/' .
                            $row['nombre'] . '/' .
                            'CI:' . $row['ci']] = $row['codigo_recepcion'] . '/' .
                                $row['id'] . '/' .
                                $row['fecha_registro'] . '/' .
                                $row['ci'] . '/' .
                                $row['nombre'] . '/' .
                                $row['nombre_marca'] . '/' .
                                $row['nombre_modelo'] . '/' .
                                $row['imei'];
                        }
                        echo json_encode($data);
                    } else {
                        $data["No existen datos"] = "No existe datos";
                        echo json_encode($data);
                    }
                    break;
            }

        } else {
            show_404();
        }
    }

    public function register_transfer_inventory_output_transit()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->transit_model->register_transfer_inventory_output_transit());
        } else {
            show_404();
        }
    }

    public function register_transfer_inventory_entry_transit()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->transit_model->register_transfer_inventory_entry_transit());
        } else {
            show_404();
        }
    }

    public function get_transit_list()
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

            $data_transit = $this->transit_model->get_transit_list($params);

            $data = array();
            foreach ($data_transit['data'] as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['nro_prestamo'] = $row['nro_prestamo'];
                $array['fecha_transito_prestamo'] = $row['fecha_transito_prestamo'];
                $array['usuario_entregador_id_prestamo'] = $this->user_model->get_user_id($row['usuario_entregador_id_prestamo'])->usuario;
                $array['usuario_solicitante_id_prestamo'] = $this->user_model->get_user_id($row['usuario_solicitante_id_prestamo'])->usuario;
                if ($row['tipo'] == 1) {
                    $array['tipo'] = 'PARA PRUEBAS';
                } else {
                    $array['tipo'] = 'POR ORDEN DE RECEPCION';
                }
                $array['detalle_prestamo'] = $row['detalle_prestamo'];
                $array['observacion_prestamo'] = $row['observacion_prestamo'];
                $array['estado_transito'] = $row['estado_transito'];

                if ($row['codigo_recepcion'] == ''){
                    $new_ot = '';
                }else{
                    $new_ot = 'O.T.' . $row['codigo_recepcion'];
                }
                $array['codigo_recepcion'] = $new_ot;
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_transit['draw'],
                'recordsTotal' => $data_transit['recordsTotal'],
                'recordsFiltered' => $data_transit['recordsFiltered'],
                'data' => $data
            );

            echo json_encode($json_data);

        } else {
            show_404();
        }
    }

    public function transit_entry()
    {
        if ($this->input->post()) {
            $transit_id = $this->input->post('id');
            $transit = $this->transit_model->get_transit_by_id($transit_id);
            $data["detail"] = $this->inventory_model->get_detail_inventory_transit_entry($transit->ingreso_inventario_id_prestamo);
            $data['delivery_user'] = $this->user_model->get_user_id($transit->usuario_entregador_id_prestamo)->usuario;
            $data['applicant_user'] = $this->user_model->get_user_id($transit->usuario_solicitante_id_prestamo)->usuario;
            $data['destination_warehouse'] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
            $data['transit'] = $transit;
            template('transit/new_transit_entry', $data);
        } else {
            show_404();
        }
    }

    public function export_to_excel_transit()
    {
        $this->load->library("excel/PHPExcel");

        /*Parametros*/
        $transit_date_start_loan = $this->input->post('transit_date_start_loan');
        $transit_date_end_loan = $this->input->post('transit_date_end_loan');
        $transit_date_start_return = $this->input->post('transit_date_start_return');
        $transit_date_end_return = $this->input->post('transit_date_end_return');
        $transit_reception_code = $this->input->post('transit_reception_code');
        $transit_state = $this->input->post('transit_state');
        $transit_type_reason = $this->input->post('transit_type_reason');
        $transit_brand = $this->input->post('transit_brand');
        $transit_model = $this->input->post('transit_model');
        $transit_product = $this->input->post('transit_product');

        /*Array*/
        $params = array(
            'transit_date_start_loan' => $transit_date_start_loan,
            'transit_date_end_loan' => $transit_date_end_loan,
            'transit_date_start_return' => $transit_date_start_return,
            'transit_date_end_return' => $transit_date_end_return,
            'transit_reception_code' => $transit_reception_code,
            'transit_state' => $transit_state,
            'transit_type_reason' => $transit_type_reason,
            'transit_brand' => $transit_brand,
            'transit_model' => $transit_model,
            'transit_product' => $transit_product
        );

        $data = $this->transit_model->get_transit_report($params);

        $list_data = $data['data'];

        if ($transit_date_start_loan != '') {
            $date_start_loan = $transit_date_start_loan;
        } else {
            $date_start_loan = 'TODOS';
        }

        if ($transit_date_end_loan != '') {
            $date_end_loan = $transit_date_end_loan;
        } else {
            $date_end_loan = 'TODOS';
        }

        if ($transit_date_start_return != '') {
            $date_start_return = $transit_date_start_return;
        } else {
            $date_start_return = 'TODOS';
        }

        if ($transit_date_end_return != '') {
            $date_end_return = $transit_date_end_return;
        } else {
            $date_end_return = 'TODOS';
        }

        if ($transit_reception_code != '') {
            $reception_code = $transit_reception_code;
        } else {
            $reception_code = 'TODOS';
        }

        if ($transit_state != '0') {
            if ($transit_state == PRESTADO) {
                $state = 'PRESTADO';
            } else if ($transit_state == DEVUELTO) {
                $state = 'DEVUELTO';
            }
        } else {
            $state = 'TODOS';
        }

        if ($transit_type_reason != '') {
            $type_reason = $this->product_model->get_type_reason_by_id($transit_type_reason)->nombre;
        } else {
            $type_reason = 'TODOS';
        }

        if ($transit_brand != '') {
            $brand = $this->brand_model->get_brand_id($transit_brand)->nombre;
        } else {
            $brand = 'TODOS';
        }

        if ($transit_model != '') {
            $model = $this->model_model->get_model_id($transit_model)->nombre;
        } else {
            $model = 'TODOS';
        }

        if ($transit_product != '') {
            $product = $this->product_model->get_product_by_id($transit_product)->nombre_comercial;
        } else {
            $product = 'TODOS';
        }

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); //Titulo
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); //Fecha prestamo inicio
        $objPHPExcel->getActiveSheet()->mergeCells('F4:G4'); //Fecha prestamo fin
        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); //Fecha devolucion inicio
        $objPHPExcel->getActiveSheet()->mergeCells('F5:G5'); //Fecha devolucion fin
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Estado transito
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Tipo motivo
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Marca
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Modelo
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Producto

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE TRANSITO')
            ->setCellValue('A4', 'FECHA PRESTAMO INICIO: ')
            ->setCellValue('B4', $date_start_loan)
            ->setCellValue('A5', 'FECHA PRESTAMO FIN: ')
            ->setCellValue('B5', $date_end_loan)
            ->setCellValue('A6', 'FECHA DEVOLUCION INICIO: ')
            ->setCellValue('B6', $date_start_return)
            ->setCellValue('A7', 'FECHA DEVOLUCION FIN: ')
            ->setCellValue('B7', $date_end_return)
            ->setCellValue('A8', 'CODIGO DE RECEPCION: ')
            ->setCellValue('B8', $reception_code)
            ->setCellValue('E4', 'ESTADO: ')
            ->setCellValue('F4', $state)
            ->setCellValue('E5', 'MOTIVO: ')
            ->setCellValue('F5', $type_reason)
            ->setCellValue('E6', 'MARCA: ')
            ->setCellValue('F6', $brand)
            ->setCellValue('E7', 'MODELO')
            ->setCellValue('F7', $model)
            ->setCellValue('E8', 'PRODUCTO')
            ->setCellValue('F8', $product)

            //Encabezado de la tabla
            ->setCellValue('A10', 'NRO.')
            ->setCellValue('B10', 'NRO. PRESTAMO')
            ->setCellValue('C10', 'TIPO')
            ->setCellValue('D10', 'ESTADO')
            ->setCellValue('E10', 'O.T.')
            ->setCellValue('F10', 'FECHA PRESTAMO')
            ->setCellValue('G10', 'FECHA DEVOLUCION')
            ->setCellValue('H10', 'USUARIO PRESTADOR')
            ->setCellValue('I10', 'USUARIO SOLICITADOR')
            ->setCellValue('J10', 'PRODUCTO');

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)

            ->getActiveSheet()->getStyle('A10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I10')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J10')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('J10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('J10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 11; // Empieza a escribir desde la linea 11
        $i = 1;
        foreach ($list_data as $row) {

            if($row['estado_transito'] == PRESTADO){
                $data_state = 'PRESTADO';
            }else if($row['estado_transito'] == DEVUELTO){
                $data_state = 'DEVUELTO';
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['nro_prestamo'])
                ->setCellValue('C' . $fila, $row['nombre_tipo_motivo'])
                ->setCellValue('D' . $fila, $data_state)
                ->setCellValue('E' . $fila, $row['codigo_recepcion'])
                ->setCellValue('F' . $fila, $row['fecha_transito_prestamo'])
                ->setCellValue('G' . $fila, $row['fecha_transito_devolucion'])
                ->setCellValue('H' . $fila, $row['usuario_entregador_prestamo'])
                ->setCellValue('I' . $fila, $row['usuario_solicitante_prestamo'])
                ->setCellValue('J' . $fila, $row['detalle_prestamo']);

            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('J' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }

        // Establece anchura automatico a la celda
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(TRUE);

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte transito' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");
    }

    public function print_transit_output()
    {
        if ($this->input->post()) {
            $transit_id = $this->input->post('id');

            $transit = $this->transit_model->get_transit_by_id($transit_id);
            $detail_transit = $this->transit_model->get_detail_transit_by_transit_id($transit_id);

            $branch_office = $this->office_model->get_branch_office_id(get_branch_id_in_session());

            $this->load->library('pdf');
            $this->pdf = new Pdf('P', 'mm', 'Legal');

            $this->pdf->AddPage();
            // Define el alias para el número de página que se imprimirá en el pie
            $this->pdf->AliasNbPages();

            // Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
            $this->pdf->SetTitle("IMPRESION PRESTAMO PIEZA");
            // La variable $x se utiliza para mostrar un número consecutivo

            // titulo de ingreso
            $var_img = base_url() . 'assets/images/logo_empresa.jpg';
            $this->pdf->Image($var_img, 10, 10, 80, 28);
            //  NIT Y NRO FACTURA

            // 1ra fila
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(115, 5, '', 0, 0, 'C');
            $this->pdf->Cell(80, 5, utf8_decode('LAST LEVEL'), 0, 0, 'C');

            //2da fila
            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, 'PRESTAMO DE PIEZA', 0, 0, 'C');
            $this->pdf->SetFont('Arial', 'B', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 5, utf8_decode($branch_office->nombre_comercial), 0, 'C');

            //3ra fila
            $this->pdf->Ln(0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, utf8_decode('Nº' . $transit->nro_prestamo), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 4, utf8_decode($branch_office->direccion), 0, 'C');

            //4ta fila
            $this->pdf->Ln(0);
            $this->pdf->Cell(115, 5, '', 0, 0, 'C');
            $this->pdf->Cell(80, 4, '' , 0, 0, 'C');

            //5ta fila
            $this->pdf->Ln(4);
            $this->pdf->Cell(85, 5, '', 0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(40, 5,  utf8_decode(''), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->Cell(70, 4, utf8_decode($branch_office->ciudad_impuestos), 0, 0, 'C');

            $this->pdf->Ln(12);


            $anio = substr($transit->fecha_transito_prestamo, 0, 4);
            $mes = substr($transit->fecha_transito_prestamo, 5, 2);
            $dia = substr($transit->fecha_transito_prestamo, 8, 2);
            $transit_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;
            $applicant_user = $this->user_model->get_user_id($transit->usuario_solicitante_id_prestamo);
            $delivery_user = $this->user_model->get_user_id($transit->usuario_entregador_id_prestamo);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(192, 5, 'DATOS', 'TLBR',0 ,'C');
            $this->pdf->Ln(6);

            // LUGAR Y FECHA , NRO COMPROBANTE
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Lugar y Fecha : ', 'TL');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(82, 5, utf8_decode($branch_office->ciudad) . ', ' . $transit_date, 'T');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, 'Nro. O.T.:', 'T');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(60, 5, utf8_decode($transit->codigo_recepcion), 'TR');
            $this->pdf->Ln(5);



            // GLOSA
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, utf8_decode('Usuario Entregador:'), 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(82, 5, utf8_decode($transit->usuario_entregador_prestamo), '');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, 'Tipo Motivo:', '');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(60, 5, utf8_decode($transit->tipo_motivo), 'R');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, utf8_decode('Usuario Solicitante:'), 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(162, 5, utf8_decode($transit->usuario_solicitante_prestamo), 'R');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, utf8_decode('Observacion:'), 'BL');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(172, 5, utf8_decode($transit->observacion_prestamo), 'BR');
            $this->pdf->Ln(7);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(192, 5, 'REPUESTOS', 'TLBR',0 ,'C');
            $this->pdf->Ln(6);


            //  DETALLE DE ITEMS
            $this->pdf->SetMargins(10, 10, 10);
            $this->pdf->SetFont('Arial', 'B', 8);

            // Encabezado de la columna
            // Encabezado de la columna
            $this->pdf->Cell(8, 5, "NRO.", 1, 0, 'C');
            $this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
            $this->pdf->Cell(77, 5, "PRODUCTO", 1, 0, 'C');
            $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
            $this->pdf->Cell(30, 5, "ALMACEN ORIGEN", 1, 0, 'C');
            $this->pdf->Cell(30, 5, "ALMACEN DESTINO", 1, 0, 'C');
            $this->pdf->Ln(5);


            //  detalle
            $nro = 1;
            $this->pdf->SetFont('Arial', '', 8);
            $cantidad_filas = 0;
            $numero_items = 0;
            $estilo = 'RL';
            $total_detail = 10;

            //Table with 20 rows and 4 columns
            $this->pdf->SetWidths(array(8,27,77,20,30,30));
            $this->pdf->SetAligns(array('C','C','L','R','C','C'));


            foreach ($detail_transit as $detail) {
                $cantidad_filas++;
                $productos = $this->product_model->get_product_by_id($detail->producto_id);
                $this->pdf->Row(array(
                    utf8_decode($cantidad_filas),
                    utf8_decode($productos->codigo),
                    utf8_decode($productos->nombre_comercial),
                    utf8_decode($detail->cantidad),
                    utf8_decode($this->warehouse_model->get_warehouse_id($detail->almacen_origen_id)->nombre),
                    utf8_decode($transit->almacen_destino_prestamo)

                ));
                $nro = $nro + 1;
                $numero_items = $numero_items + 1;
            }

            while ($total_detail-1 >= $numero_items) {

                $cantidad_filas++;
                $estilo = 'RL';
                if ($nro == 1) {
                    $estilo = $estilo . 'T';
                }
                if ($cantidad_filas == $total_detail) {
                    $estilo = 'LRB';
                }

                $this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
                $this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
                $this->pdf->Cell(77, 4, '', $estilo, 0, 'L');
                $this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
                $this->pdf->Cell(30, 4, '', $estilo, 0, 'R');
                $this->pdf->Cell(30, 4, '', $estilo, 0, 'R');
                $this->pdf->Ln(4);
                $numero_items = $numero_items + 1;
            }

            $print_day = date('Y-m-d');
            $print_hour = date('H:i:s');
            $year = substr($print_day, 0, 4);
            $month = substr($print_day, 5, 2);
            $day = substr($print_day, 8, 2);
            $print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(7, 5, 'RECIBI CONFORME : .......................................................................', '', 0, 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(130, 5, '', '', 0, 'L');
            $this->pdf->Cell(5, 5, '', '', 0, 'L');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(50, 5, 'ENTREGUE CONFORME :  .......................................................................', '', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(70, 5, '', '', 0, 'R');

            $this->pdf->Ln(7);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

            $this->pdf->Output("Prestamo Pieza_".  date('Y-m-d') . ".pdf", 'I');
        } else {
            show_404();
        }
    }
    public function print_transit_entry()
    {
        if ($this->input->post()) {
            $transit_id = $this->input->post('id');

            $transit = $this->transit_model->get_transit_by_id($transit_id);
            $detail_transit = $this->transit_model->get_detail_transit_by_transit_id($transit_id);

            $branch_office = $this->office_model->get_branch_office_id(get_branch_id_in_session());

            $this->load->library('pdf');
            $this->pdf = new Pdf('P', 'mm', 'Legal');

            $this->pdf->AddPage();
            // Define el alias para el número de página que se imprimirá en el pie
            $this->pdf->AliasNbPages();

            // Se define el titulo, márgenes izquierdo, derecho y el color de relleno predeterminado
            $this->pdf->SetTitle("IMPRESION DEVOLUCION");
            // La variable $x se utiliza para mostrar un número consecutivo

            // titulo de ingreso
            $var_img = base_url() . 'assets/images/logo_empresa.jpg';
            $this->pdf->Image($var_img, 10, 10, 80, 28);
            //  NIT Y NRO FACTURA

            // 1ra fila
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(115, 5, '', 0, 0, 'C');
            $this->pdf->Cell(80, 5, utf8_decode('LAST LEVEL'), 0, 0, 'C');

            //2da fila
            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, 'DEVOLUCION', 0, 0, 'C');
            $this->pdf->SetFont('Arial', 'B', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 5, utf8_decode($branch_office->nombre_comercial), 0, 'C');

            //3ra fila
            $this->pdf->Ln(0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(85, 5, '', 0, 0, 'C');
            $this->pdf->Cell(40, 5, utf8_decode('Nº' . $transit->nro_prestamo), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->MultiCell(70, 4, utf8_decode($branch_office->direccion), 0, 'C');

            //4ta fila
            $this->pdf->Ln(0);
            $this->pdf->Cell(115, 5, '', 0, 0, 'C');
            $this->pdf->Cell(80, 4, '' , 0, 0, 'C');

            //5ta fila
            $this->pdf->Ln(4);
            $this->pdf->Cell(85, 5, '', 0);
            $this->pdf->SetFont('Arial', 'B', 12);
            $this->pdf->SetTextColor(248, 000, 000);
            $this->pdf->Cell(40, 5,  utf8_decode(''), 0, 0, 'C');
            $this->pdf->SetFont('Arial', '', 9);
            $this->pdf->SetTextColor(0, 000, 000);
            $this->pdf->Cell(70, 4, utf8_decode($branch_office->ciudad_impuestos), 0, 0, 'C');

            $this->pdf->Ln(12);


            $anio = substr($transit->fecha_transito_prestamo, 0, 4);
            $mes = substr($transit->fecha_transito_prestamo, 5, 2);
            $dia = substr($transit->fecha_transito_prestamo, 8, 2);
            $transit_date = $dia . ' de ' . get_month($mes) . ' del ' . $anio;

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(192, 5, 'DATOS', 'TLBR',0 ,'C');
            $this->pdf->Ln(6);

            // LUGAR Y FECHA , NRO COMPROBANTE
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Lugar y Fecha : ', 'TL');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(82, 5, utf8_decode($branch_office->ciudad) . ', ' . $transit_date, 'T');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, 'Nro. O.T.:', 'T');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(60, 5, utf8_decode($transit->codigo_recepcion), 'TR');
            $this->pdf->Ln(5);



            // GLOSA
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, utf8_decode('Usuario Entregador:'), 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(82, 5, utf8_decode($transit->usuario_entregador_devolucion), '');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, 'Tipo Motivo:', '');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(60, 5, utf8_decode($transit->tipo_motivo), 'R');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, utf8_decode('Usuario Solicitante:'), 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(162, 5, utf8_decode($transit->usuario_solicitante_devolucion), 'R');
            $this->pdf->Ln(5);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(20, 5, utf8_decode('Observacion:'), 'BL');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(172, 5, utf8_decode($transit->observacion_devolucion), 'BR');
            $this->pdf->Ln(7);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(192, 5, 'REPUESTOS', 'TLBR',0 ,'C');
            $this->pdf->Ln(6);


            //  DETALLE DE ITEMS
            $this->pdf->SetMargins(10, 10, 10);
            $this->pdf->SetFont('Arial', 'B', 8);

            // Encabezado de la columna
            $this->pdf->Cell(8, 5, "NRO.", 1, 0, 'C');
            $this->pdf->Cell(27, 5, "CODIGO", 1, 0, 'C');
            $this->pdf->Cell(77, 5, "PRODUCTO", 1, 0, 'C');
            $this->pdf->Cell(20, 5, "CANTIDAD", 1, 0, 'C');
            $this->pdf->Cell(30, 5, "ALMACEN ORIGEN", 1, 0, 'C');
            $this->pdf->Cell(30, 5, "ALMACEN DESTINO", 1, 0, 'C');
            $this->pdf->Ln(5);


            //  detalle
            $nro = 1;
            $this->pdf->SetFont('Arial', '', 8);
            $cantidad_filas = 0;
            $numero_items = 0;
            $estilo = 'RL';
            $total_detail = 10;

            //Table with 20 rows and 4 columns
            $this->pdf->SetWidths(array(8,27,77,20,30,30));
            $this->pdf->SetAligns(array('C','C','L','R','C','C'));


            foreach ($detail_transit as $detail) {
                $cantidad_filas++;
                $productos = $this->product_model->get_product_by_id($detail->producto_id);
                $this->pdf->Row(array(
                    utf8_decode($cantidad_filas),
                    utf8_decode($productos->codigo),
                    utf8_decode($productos->nombre_comercial),
                    utf8_decode($detail->cantidad),
                    utf8_decode($transit->almacen_destino_prestamo),
                    utf8_decode($this->warehouse_model->get_warehouse_id($detail->almacen_origen_id)->nombre)

                ));
                $nro = $nro + 1;
                $numero_items = $numero_items + 1;
            }

            while ($total_detail-1 >= $numero_items) {

                $cantidad_filas++;
                $estilo = 'RL';
                if ($nro == 1) {
                    $estilo = $estilo . 'T';
                }
                if ($cantidad_filas == $total_detail) {
                    $estilo = 'LRB';
                }

                $this->pdf->Cell(8, 4, '', $estilo, 0, 'C');
                $this->pdf->Cell(27, 4, '', $estilo, 0, 'L');
                $this->pdf->Cell(77, 4, '', $estilo, 0, 'L');
                $this->pdf->Cell(20, 4, '', $estilo, 0, 'R');
                $this->pdf->Cell(30, 4, '', $estilo, 0, 'R');
                $this->pdf->Cell(30, 4, '', $estilo, 0, 'R');
                $this->pdf->Ln(4);
                $numero_items = $numero_items + 1;
            }

            $print_day = date('Y-m-d');
            $print_hour = date('H:i:s');
            $year = substr($print_day, 0, 4);
            $month = substr($print_day, 5, 2);
            $day = substr($print_day, 8, 2);
            $print_date = $day . ' de ' . get_month($month) . ' del ' . $year;

            $this->pdf->Ln(5);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(7, 5, 'RECIBI CONFORME : .......................................................................', '', 0, 'L');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(130, 5, '', '', 0, 'L');
            $this->pdf->Cell(5, 5, '', '', 0, 'L');
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(50, 5, 'ENTREGUE CONFORME :  .......................................................................', '', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(70, 5, '', '', 0, 'R');

            $this->pdf->Ln(7);
            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(26, 5, 'Fecha Impresion :', 'TBL', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(40, 5, $print_date, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(30, 5, 'Hora Impresion :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(30, 5, $print_hour, 'BT', 0, 'L');

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->Cell(22, 5, 'Usuario :', 'BT', 0, 'R');
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->Cell(44, 5, get_user_name_in_session(), 'BTR', 0, 'L');

            $this->pdf->Output("Devolucion Pieza_".  date('Y-m-d') . ".pdf", 'I');
        } else {
            show_404();
        }
    }

    public function transit_borrowed_piece()
    {
        template('transit/transit_borrowed_piece', null);
    }

    public function transit_requested_pieces()
    {
        template('transit/transit_requested_pieces', null);
    }

    public function get_transit_borrowed_piece()
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

            $data_transit = $this->transit_model->get_transit_borrowed_piece($params);

            $data = array();
            foreach ($data_transit['data'] as $row) {
                $array = array();
                $array['id'] = $row['id'];
                $array['nro_prestamo'] = $row['nro_prestamo'];
                $array['fecha_transito_prestamo'] = $row['fecha_transito_prestamo'];
                $array['usuario_entregador_id_prestamo'] = $this->user_model->get_user_id($row['usuario_entregador_id_prestamo'])->usuario;
                $array['usuario_solicitante_id_prestamo'] = $this->user_model->get_user_id($row['usuario_solicitante_id_prestamo'])->usuario;
                if ($row['tipo'] == 1) {
                    $array['tipo'] = 'PARA PRUEBAS';
                } else {
                    $array['tipo'] = 'POR ORDEN DE RECEPCION';
                }
                $array['detalle_prestamo'] = $row['detalle_prestamo'];
                $array['observacion_prestamo'] = $row['observacion_prestamo'];
                $array['estado_transito'] = $row['estado_transito'];
                if ($row['codigo_recepcion'] == ''){
                    $new_ot = '';
                }else{
                    $new_ot = 'O.T.' . $row['codigo_recepcion'];
                }
                $array['codigo_recepcion'] = $new_ot;
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_transit['draw'],
                'recordsTotal' => $data_transit['recordsTotal'],
                'recordsFiltered' => $data_transit['recordsFiltered'],
                'data' => $data
            );

            echo json_encode($json_data);

        } else {
            show_404();
        }
    }

    public function transit_approved()
    {
        if ($this->input->post()) {
            $transit_id = $this->input->post('id');
            $transit = $this->transit_model->get_transit_by_id($transit_id);
            $data["detail"] = $this->inventory_model->get_detail_inventory_transit_entry($transit->ingreso_inventario_id_prestamo);
            $data['delivery_user'] = $this->user_model->get_user_id($transit->usuario_entregador_id_prestamo)->usuario;
            $data['applicant_user'] = $this->user_model->get_user_id($transit->usuario_solicitante_id_prestamo)->usuario;
            $data['destination_warehouse'] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
            $data['transit'] = $transit;
            template('transit/transit_approved', $data);
        } else {
            show_404();
        }
    }

    public function register_transit_approved()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->transit_model->register_transit_approved());
        } else {
            show_404();
        }
    }


}