<?php
/**
 * Created by PhpStorm.
 * User: Gomez
 * Date: 13/3/2018
 * Time: 5:08 PM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Report_sale extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_sale_model');
        $this->load->model('brand_model');
        $this->load->model('model_model');
        $this->load->model('product_model');
        $this->load->model('customer_model');
        $this->load->model('sale_model');
        //$this->load->model('reception_model');
    }

    public function report_sale_product()
    {
        $data["list_branch_office"] = $this->office_model->get_offices();
        $data["list_customer"] = $this->customer_model->get_customer_enable();
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();
        template('report/report_for_sale_product', $data);
    }
    public function report_sale_user()
    {
        $data["list_branch_office"] = $this->office_model->get_offices();
        $data["list_user"] = $this->user_model->get_userss();
        template('report/report_for_sale_user', $data);
    }

    public function get_report_sale_product()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin datatable*/
            $report_branch_office = $this->input->post('report_branch_office');
            $report_start_date = $this->input->post('report_start_date');
            $report_end_date = $this->input->post('report_end_date');
            $report_brand = $this->input->post('report_brand');
            $report_model = $this->input->post('report_model');
            $report_product = $this->input->post('report_product');
            $report_type_sale = $this->input->post('report_type_sale');
            $report_customer = $this->input->post('report_customer');
            $reporte_number_sale = intval($this->input->post('reporte_number_sale'));
            $reporte_number_reception = $this->input->post('reporte_number_reception');
            $report_type_product = $this->input->post('report_type_product');
            // Se almacenan los parametros recibidos en un array para enviar al modelo

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_branch_office' => $report_branch_office,
                'report_start_date' => $report_start_date,
                'report_end_date' => $report_end_date,
                'report_brand' => $report_brand,
                'report_model' => $report_model,
                'report_product' => $report_product,
                'report_type_sale' => $report_type_sale,
                'report_customer' => $report_customer,
                'reporte_number_sale' => $reporte_number_sale,
                'reporte_number_reception' => $reporte_number_reception,
                'report_type_product' => $report_type_product
            );
            $data_sale = $this->report_sale_model->get_report_sale_product($params);

            $data = array();
            foreach ($data_sale['data'] as $row) {
                $array = array();
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['codigo_recepcion'] = $row['codigo_recepcion'];
                $array['nro_venta'] = $row['nro_venta'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['tipo_venta'] = $row['tipo_venta'];

                if ($row['tipo_producto_id'] == 1){
                    $array['tipo_producto_id'] = 'PRODUCTO';
                    $array['nombre_marca'] = $row['nombre_marca'];
                    $array['nombre_modelo'] = $row['nombre_modelo'];
                    $array['codigo_modelo'] = $row['codigo_modelo'];
					$array['codigo_producto'] = $row['codigo_producto'];
                }
                else if ($row['tipo_producto_id'] == 2){
                    $array['tipo_producto_id'] = 'SERVICIO';
                    $order_work=$this->reception_model->get_customer_device_by_code_reception(trim($row['codigo_recepcion']));
                    $array['nombre_marca'] = $order_work['nombre_marca'];
                    $array['nombre_modelo'] = $order_work['nombre_modelo'];
                    $array['codigo_modelo'] = $order_work['codigo_modelo'];
					$array['codigo_producto'] = 'COD-SERVICIO';
                }

                $array['descripcion'] = $row['descripcion'];
                $array['cantidad'] = $row['cantidad'];
                $array['precio_venta'] = $row['precio_venta'];
                $array['descuento'] = $row['descuento'];
                $array['precio_venta_descuento'] = $row['precio_venta_descuento'];
                $array['total'] = $row['total'];
                $array['utilidad'] = $row['utilidad'];
                $array['precio_compra'] = $row['precio_compra'];

                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_sale['draw'],
                'recordsTotal' => $data_sale['recordsTotal'],
                'recordsFiltered' => $data_sale['recordsFiltered'],
                'data' => $data
            );

            echo json_encode($json_data);
        // echo json_encode($params);
        } else {
            show_404();
        }
    }

    function obtener_mes($valor)
    {
        $result = '';
        switch ($valor) {
            case '01':
                $result = 'Enero';
                break;
            case '02':
                $result = 'Febrero';
                break;
            case '03':
                $result = 'Marzo';
                break;
            case '04':
                $result = 'Abril';
                break;
            case '05':
                $result = 'Mayo';
                break;
            case '06':
                $result = 'Junio';
                break;
            case '07':
                $result = 'Julio';
                break;
            case '08':
                $result = 'Agosto';
                break;
            case '09':
                $result = 'Septiembre';
                break;
            case '10':
                $result = 'Octubre';
                break;
            case '11':
                $result = 'Noviembre';
                break;
            case '12':
                $result = 'Diciembre';
                break;
        }
        return $result;
    }

    public function export_to_excel_sale_product()
    {
        $this->load->library("excel/PHPExcel");

        /*Parametros de busqueda sin datatable*/
        
        $report_branch_office = $this->input->post('report_branch_office');
        $report_start_date = $this->input->post('report_start_date');
        $report_end_date = $this->input->post('report_end_date');
        $report_brand = $this->input->post('report_brand');
        $report_model = $this->input->post('report_model');
        $report_product = $this->input->post('report_product');
        $report_type_sale = $this->input->post('report_type_sale');
        $report_customer = $this->input->post('report_customer');
        $reporte_number_sale = $this->input->post('reporte_number_sale');
        $reporte_number_reception = $this->input->post('reporte_number_reception');
        $report_type_product = $this->input->post('report_type_product');

        /*Array*/
        $params = array(
            'report_branch_office' => $report_branch_office,
            'report_start_date' => $report_start_date,
            'report_end_date' => $report_end_date,
            'report_brand' => $report_brand,
            'report_model' => $report_model,
            'report_product' => $report_product,
            'report_type_sale' => $report_type_sale,
            'report_customer' => $report_customer,
            'reporte_number_sale' => $reporte_number_sale,
            'reporte_number_reception' => $reporte_number_reception,
            'report_type_product' => $report_type_product
        );
        $data = $this->report_sale_model->get_report_sale_product_for_export($params);
        /*echo json_encode($params);
        exit();*/

        if ($report_start_date != '') {
            $report_start_date = $report_start_date;
        } else {
            $report_start_date = 'TODOS';
        }

        if ($report_end_date != '') {
            $report_end_date = $report_end_date;
        } else {
            $report_end_date = 'TODOS';
        }

        if ($report_type_sale == '0') {
            $report_type_sale = 'TODOS';
        }

        if ($report_branch_office != 0) {
            $branch_office = $this->office_model->get_branch_office_id($report_branch_office)->nombre;
        } else {
            $branch_office = 'TODOS';
        }

        if ($report_brand != 0) {
            $brand = $this->brand_model->get_brand_id($report_brand)->nombre;
        } else {
            $brand = 'TODOS';
        }

        if ($report_model != 0) {
            $model = $this->model_model->get_model_id($report_model)->nombre;
        } else {
            $model = 'TODOS';
        }

        if ($report_product != 0) {
            $product = $this->product_model->get_product_by_id($report_product)->nombre_comercial;
            $product_code = $this->product_model->get_product_by_id($report_product)->codigo;
        } else {
            $product = 'TODOS';
        }

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
        /* $objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
         $objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
         $objPHPExcel->getActiveSheet()->mergeCells('D4:E4');
         $objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
         $objPHPExcel->getActiveSheet()->mergeCells('J4:K4');
         $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
         $objPHPExcel->getActiveSheet()->mergeCells('D6:E6');
         $objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
         $objPHPExcel->getActiveSheet()->mergeCells('J6:K6');*/


        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE VENTAS DE PRODUCTOS')
            ->setCellValue('A3', 'SUCURSAL: ')
            ->setCellValue('B3', strtoupper($branch_office))
            ->setCellValue('A4', 'TIPO VENTA ')
            ->setCellValue('B4', strtoupper($report_type_sale))
            ->setCellValue('D4', 'MARCA: ')
            ->setCellValue('E4', strtoupper($brand))
            ->setCellValue('G4', 'MODELO: ')
            ->setCellValue('H4', strtoupper($model))
            ->setCellValue('J4', 'PRODUCTO: ')
            ->setCellValue('K4', strtoupper($product))
            ->setCellValue('A5', 'NRO. DE VENTA ')
            ->setCellValue('B5', $reporte_number_sale)
            ->setCellValue('D5', 'NRO. DE RECEPCION: ')
            ->setCellValue('E5', strtoupper($reporte_number_reception))
            ->setCellValue('G5', 'FECHA INICIO: ')
            ->setCellValue('H5', $report_start_date)
            ->setCellValue('J5', 'FECHA FIN: ')
            ->setCellValue('K5', $report_end_date)
            //Encabezado de la tabla
            ->setCellValue('A7', 'FECHA')
            ->setCellValue('B7', 'NRO. ORDEN TRABAJO')
            ->setCellValue('C7', 'NRO. VENTA')
            ->setCellValue('D7', 'NOMBRE CLIENTE')
            ->setCellValue('E7', 'TIPO VENTA')
            ->setCellValue('F7', 'TIPO PRODUCTO')
            ->setCellValue('G7', 'MARCA')
            ->setCellValue('H7', 'CODIGO PRODUCTO')
            ->setCellValue('I7', 'NOMBRE PRODUCTO')
            ->setCellValue('J7', 'MODELO')
            ->setCellValue('K7', 'CODIGO MODELO')
            ->setCellValue('L7', 'CANTIDAD')
            ->setCellValue('M7', 'PRECIO COSTO')
            ->setCellValue('N7', 'PRECIO VENTA')
            ->setCellValue('O7', 'DESCUENTO')
            ->setCellValue('P7', 'PRECIO VENTA DESCUENTO')
            ->setCellValue('Q7', 'TOTAL')
            ->setCellValue('R7', 'UTILIDAD');

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('K7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('L7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('M7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('N7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('O7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('P7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('Q7')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('R7')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('J7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('K7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('L7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('M7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('O7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('P7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('Q7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
            ->getActiveSheet()->getStyle('R7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('J7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('K7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('L7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('M7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('N7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('O7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('P7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('Q7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('R7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 8; // Empieza a escribir desde la linea 8
        $i = 1;
        $primera_fila=$fila;
        foreach ($data as $row) {

            if ($row['tipo_producto_id'] == 1){
                $type_product = 'PRODUCTO';
                $name_brand = $row['nombre_marca'];
                $name_model = $row['nombre_modelo'];
                $code_model = $row['codigo_modelo'];
                $code_product = $row['codigo_producto'];
            }
            else if ($row['tipo_producto_id'] == 2){
                $type_product = 'SERVICIO';
                $order_work=$this->reception_model->get_customer_device_by_code_reception(trim($row['codigo_recepcion']));
                $name_brand = $order_work['nombre_marca'];
                $name_model = $order_work['nombre_modelo'];
                $code_model  = $order_work['codigo_modelo'];
				$code_product = 'COD-SERVICIO';
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $row['fecha_registro'])
                ->setCellValue('B' . $fila, $row['codigo_recepcion'])
                ->setCellValue('C' . $fila, $row['nro_venta'])
                ->setCellValue('D' . $fila, $row['nombre_cliente'])
                ->setCellValue('E' . $fila, $row['tipo_venta'])
                ->setCellValue('F' . $fila, $type_product)
                ->setCellValue('G' . $fila, $name_brand)
				->setCellValue('H' . $fila, $code_product)
                ->setCellValue('I' . $fila, $row['descripcion'])
                ->setCellValue('J' . $fila, $name_model)
                ->setCellValue('K' . $fila, $code_model)
                ->setCellValue('L' . $fila, $row['cantidad'])
                ->setCellValue('M' . $fila, $row['precio_costo'])
                ->setCellValue('N' . $fila, $row['precio_venta'])
                ->setCellValue('O' . $fila, $row['descuento'])
                ->setCellValue('P' . $fila, $row['precio_venta_descuento'])
                ->setCellValue('Q' . $fila, $row['total'])
                ->setCellValue('R' . $fila, $row['utilidad']);

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
                ->getActiveSheet()->getStyle('J' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('K' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('L' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('M' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('N' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('O' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('P' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('Q' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('R' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $fila = $fila + 1;
            $i++;
        }

        $ultima_fila = $fila -1;
        $nro = $i - 1;

        $objPHPExcel->getActiveSheet()->getStyle('P' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('Q' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $fila, 'TOTALES');
        $objPHPExcel->getActiveSheet()->getStyle('P'. $fila)->getFont()->setBold(TRUE);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$fila, '=SUM(Q'.$primera_fila.':Q'.$ultima_fila.')');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$fila, '=SUM(R'.$primera_fila.':R'.$ultima_fila.')');
        $objPHPExcel->getActiveSheet()->getStyle('Q' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


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
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("R")->setAutoSize(TRUE);

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte Ventas de Productos' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }

    public function get_report_sale_user()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin datatable*/
            $report_start_date = $this->input->post('report_start_date');
            $report_end_date = $this->input->post('report_end_date');
            $report_branch_office = $this->input->post('report_branch_office');
            $report_user = $this->input->post('report_user');
            $report_type_sale = $this->input->post('report_type_sale');
            $report_sale_form = $this->input->post('report_sale_form');
            // Se almacenan los parametros recibidos en un array para enviar al modelo

            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_start_date' => $report_start_date,
                'report_end_date' => $report_end_date,
                'report_branch_office' => $report_branch_office,
                'report_user' => $report_user,
                'report_type_sale' => $report_type_sale,
                'report_sale_form' => $report_sale_form
            );
            $data_sale = $this->report_sale_model->get_report_sale_user($params);

            $data = array();
            foreach ($data_sale['data'] as $row) {
                $array = array();
                $array['fecha_registro'] = $row['fecha_registro'];
                $array['nro_venta'] = $row['nro_venta'];
                $array['nombre_cliente'] = $row['nombre_cliente'];
                $array['tipo_venta'] = $row['tipo_venta'];
                $array['total'] = $row['total'];
                $array['subtotal'] = $row['subtotal'];
                $array['nombre_usuario'] = $row['nombre_usuario'];
                $array['nombre_sucursal'] = $row['nombre_sucursal'];
                $array['descuento'] = isset($row['descuento'])? $row['descuento'] : 0;
                $array['descuento_producto'] = isset($row['descuento_producto'])? $row['descuento_producto'] : 0;
                
                $array['precio_costo_total'] = isset($row['precio_costo_total'])? $row['precio_costo_total'] : 0;
                // $discoun = $this->sale_model->getSumDiscount($row['id'])->descuento;
                // $array['descuento'] = isset($discoun)? $discoun : 0;
                
                $array['utilidad'] = isset($row['precio_costo_total'])? $row['total'] - $row['precio_costo_total']: 0;
                $data[] = $array;
            }

            $json_data = array(
                'draw' => $data_sale['draw'],
                'recordsTotal' => $data_sale['recordsTotal'],
                'recordsFiltered' => $data_sale['recordsFiltered'],
                'data' => $data
            );

            echo json_encode($json_data);
        //    echo json_encode($params);
        } else {
            show_404();
        }
    }
    public function export_to_excel_sale_user()
    {
        $array_cell=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $array_header=array(
            "FECHA",
            "NRO. VENTA",
            "NOMBRE CLIENTE",
            "TIPO VENTA",
            "USUARIO",
            "SUCURSAL",
            "COSTO TOTAL",
            "SUB TOTAL",
            //"DESCUENTO PRODUCTO",
            "DESCUENTO",
            "TOTAL",
            //"UTILIDAD",
        );
        $this->load->library("excel/PHPExcel");

        /*Parametros de busqueda sin datatable*/
        $report_start_date = $this->input->post('report_start_date');
        $report_end_date = $this->input->post('report_end_date');
        $report_branch_office = $this->input->post('report_branch_office');
        $report_user = $this->input->post('report_user');
        $report_type_sale = $this->input->post('report_type_sale');
        // $report_sale_form = $this->input->post('report_sale_form');
        /*Array*/
        $params = array(
            'report_start_date' => $report_start_date,
            'report_end_date' => $report_end_date,
            'report_branch_office' => $report_branch_office,
            'report_user' => $report_user,
            'report_type_sale' => $report_type_sale,
        );
        $data = $this->report_sale_model->get_report_sale_user_for_export($params);
        /*echo json_encode($params);
        exit();*/

        if ($report_start_date != '') {
            $report_start_date = $report_start_date;
        } else {
            $report_start_date = 'TODOS';
        }

        if ($report_end_date != '') {
            $report_end_date = $report_end_date;
        } else {
            $report_end_date = 'TODOS';
        }

        if ($report_type_sale == '0') {
            $report_type_sale = 'TODOS';
        }

        // if ($report_sale_form == '0') {
        //     $report_sale_form = 'TODOS';
        // }

        if ($report_branch_office != 0) {
            $branch_office = $this->office_model->get_branch_office_id($report_branch_office)->nombre;
        } else {
            $branch_office = 'TODOS';
        }

        if ($report_user != 0) {
            $user = $this->user_model->get_user_id($report_user)->nombre;
        } else {
            $user = 'TODOS';
        }


        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE VENTAS POR USUARIO')
            ->setCellValue('A3', 'SUCURSAL: ')
            ->setCellValue('B3', strtoupper($branch_office))
            ->setCellValue('A4', 'TIPO VENTA ')
            ->setCellValue('B4', strtoupper($report_type_sale))
            ->setCellValue('D4', 'USUARIO: ')
            ->setCellValue('E4', strtoupper($user))
            ->setCellValue('A5', 'FECHA INICIO: ')
            ->setCellValue('B5', $report_start_date)
            ->setCellValue('D5', 'FECHA FIN: ')
            ->setCellValue('E5', $report_end_date);

            //Encabezado de la tabla
            for ($i=0; $i < sizeof($array_header); $i++) { 
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($array_cell[$i].'8', $array_header[$i]);
            }
         

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J5')->getFont()->setBold(TRUE);

            for ($i=0; $i < sizeof($array_header); $i++) { 
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getFont()->setBold(TRUE);
                //Centrar los titulos
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //Pintar los bordes
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$i].'8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
      

        $fila = 9; // Empieza a escribir desde la linea 8
        $i = 1;
        foreach ($data as $row) {
            // $discoun = $this->sale_model->getSumDiscount($row['id'])->descuento;
            // $row['descuento'] = isset($discoun)? $discoun : 0;
                
            $row['utilidad'] = isset($row['precio_costo_total'])? $row['total'] - $row['precio_costo_total']: 0;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $row['fecha_registro'])
                ->setCellValue('B' . $fila, $row['nro_venta'])
                ->setCellValue('C' . $fila, $row['nombre_cliente'])
                ->setCellValue('D' . $fila, $row['tipo_venta'])
                ->setCellValue('E' . $fila, $row['nombre_usuario'])
                ->setCellValue('F' . $fila, $row['nombre_sucursal'])
                ->setCellValue('G' . $fila, $row['precio_costo_total'])
                ->setCellValue('H' . $fila, $row['subtotal'])
                //->setCellValue('I' . $fila, isset($row['descuento_producto'])? $row['descuento_producto'] : 0)
                ->setCellValue('I' . $fila, isset($row['descuento'])? $row['descuento'] : 0)
                ->setCellValue('J' . $fila, $row['total']);

           
            for ($j=0; $j < sizeof($array_header); $j++) { 
                $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j].$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
            $objPHPExcel->getActiveSheet()->getStyle('G' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('K' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $fila = $fila + 1;
            $i++;
        }

        for ($j=0; $j < sizeof($array_header); $j++){ 
            $objPHPExcel->getActiveSheet()->getStyle($array_cell[$j] . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $fila, 'TOTAL');
        $objPHPExcel->getActiveSheet()->getStyle('F'. $fila)->getFont()->setBold(TRUE);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$fila,'=SUM('.'J'.'9:'.'J'.($fila-1).')'); 
        $objPHPExcel->getActiveSheet()->getStyle('J' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$fila,'=SUM('.'I'.'9:'.'I'.($fila-1).')');
        $objPHPExcel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        
        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$fila,'=SUM('.'L'.'9:'.'L'.($fila-1).')');
        //$objPHPExcel->getActiveSheet()->getStyle('L' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Establece anchura automatico a la celda
        for ($i=0; $i < sizeof($array_header); $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension($array_cell[$i])->setAutoSize(TRUE);
        }
       

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte_Ventas_Usuario' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }

}
