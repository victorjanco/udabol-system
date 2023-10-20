<?php
/**
 * Created by PhpStorm.
 * User: JANCO
 * Date: 20/04/2018
 * Time: 09:40 AM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Report_product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('brand_model');
        $this->load->model('report_product_model');
        $this->load->model('inventory_model');
        $this->load->model('model_model');
        $this->load->model('product_model');
    }

    /* Inicia la vista de reporte por Producto*/
    public function report_for_product()
    {
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        template('report/report_for_product', $data);
    }

    /*Retorna los productos de inventario mostrado cantidad entrante y saliente de la Vista de BD*/
    public function get_report_product_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin Datatable*/
            $report_start_date = $this->input->post('report_start_date');
            $report_end_date = $this->input->post('report_end_date');
            $report_brand = $this->input->post('report_brand');
            $report_model = $this->input->post('report_model');
            $report_product = $this->input->post('report_product');
            $report_warehouse = $this->input->post('report_warehouse');

            // Se almacenan los parametros recibidos en un array para enviar al model
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_start_date' => $report_start_date,
                'report_end_date' => $report_end_date,
                'report_brand' => $report_brand,
                'report_model' => $report_model,
                'report_product' => $report_product,
                'report_warehouse' => $report_warehouse
            );

            $data_product = $this->report_product_model->get_data_report_product($params);

            /*Set a la consulta por obtener la suma de la salida de inventario*/
            $data = array();
            foreach ($data_product['data'] as $row) {
                $array = array();
                $array['fecha'] = $row['fecha'];
                $array['nombre_almacen'] = $row['nombre_almacen'];
                $array['nombre_marca'] = $row['nombre_marca'];
                $array['codigo_producto'] = $row['codigo_producto'];
                $array['nombre_comercial'] = $row['nombre_comercial'];
                $array['codigo_modelo'] = $row['codigo_modelo'];
                $array['nombre_modelo'] = $row['nombre_modelo'];
                $array['ingresada'] = $row['ingresada'];

                $result_output = $this->inventory_model->get_detail_inventory_output_by_inventory_id($row['inventario_id']);
                if ($result_output == 0)
                    $array['cantidad_saliente'] = 0;
                else
                    $array['cantidad_saliente'] = $result_output;

                $array['saldo_disponible'] =  $array['ingresada'] - $array['cantidad_saliente'];
                $array['precio_costo'] = $row['precio_costo'];
                $array['precio_venta'] = $row['precio_venta'];
                
                if($array['ingresada']>=$array['cantidad_saliente']){
                    $data[] = $array;
                }
            }

            $json_data = array(
                'draw' => $data_product['draw'],
                'recordsTotal' => $data_product['recordsTotal'],
                'recordsFiltered' => $data_product['recordsFiltered'],
                'data' => $data,
            );

            echo json_encode($json_data);
        } else {
            show_404();
        }
    }
    public function report_for_brand()
    {
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        template('report/report_for_brand', $data);
    }
    /*Reporte Producto*/
    public function export_to_excel_product()
    {
        $this->load->library("excel/PHPExcel");

        /*Parametros*/
        $report_start_date = $this->input->post('report_start_date');
        $report_end_date = $this->input->post('report_end_date');
        $report_brand = $this->input->post('report_brand');
        $report_model = $this->input->post('report_model');
        $report_product = $this->input->post('report_product');
        $report_warehouse = $this->input->post('report_warehouse');

        /*Array*/
        $params = array(
            'report_start_date' => $report_start_date,
            'report_end_date' => $report_end_date,
            'report_brand' => $report_brand,
            'report_model' => $report_model,
            'report_product' => $report_product,
            'report_warehouse' => $report_warehouse
        );

        $data = $this->report_product_model->get_data_report_product($params);
        $list_data = $data['data'];
        if ($report_end_date != '') {
            $date_end = date('d-m-Y', strtotime($report_end_date));
        } else {
            $date_end = '';
        }
        if ($report_start_date != '') {
            $date_start = date('d-m-Y', strtotime($report_start_date));
        } else {
            $date_start = '';
        }
        if ($report_brand != 0) {
            $brand = $this->brand_model->get_brand_id($report_brand)->nombre;
        } else {
            $brand = 'Todos';
        }
        if ($report_model != 0) {
            $model = $this->model_model->get_model_id($report_model)->nombre;
            $model_code = $this->model_model->get_model_id($report_model)->codigo;
        } else {
            $model = 'Todos';
            $model_code = 'Todos';
        }
        if ($report_product != 0) {
            $product = $this->product_model->get_product_by_id($report_product)->nombre_comercial;
            $product_code = $this->product_model->get_product_by_id($report_product)->codigo;
        } else {
            $product = 'Todos';
            $product_code = 'Todos';
        }

        if ($report_warehouse != 0) {
            $warehouse = $this->warehouse_model->get_warehouse_id($report_warehouse)->nombre;
        } else {
            $warehouse = 'Todos';
        }

        $branch_office = get_branch_office_name_in_session();

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); //Titulo
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); //Fecha inicio
        $objPHPExcel->getActiveSheet()->mergeCells('F4:G4'); //Modelo
        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); //Fecha fin
        $objPHPExcel->getActiveSheet()->mergeCells('F5:G5'); //Marca
        $objPHPExcel->getActiveSheet()->mergeCells('F6:G6'); //Producto
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Sucursal
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3'); //Almacen

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE POR PRODUCTOS')
            ->setCellValue('A4', 'FECHA INICIO: ')
            ->setCellValue('B4', $date_start)
            ->setCellValue('E4', 'MODELO: ')
            ->setCellValue('F4', $model)
            ->setCellValue('A5', 'FECHA FIN: ')
            ->setCellValue('B5', $date_end)
            ->setCellValue('E5', 'MARCA: ')
            ->setCellValue('F5', $brand)
            ->setCellValue('E6', 'PRODUCTO:')
            ->setCellValue('F6', $product)
            ->setCellValue('A6', 'SUCURSAL:')
            ->setCellValue('B6', $branch_office)
            ->setCellValue('E3', 'ALMACEN:')
            ->setCellValue('F3', $warehouse)
            //Encabezado de la tabla
            ->setCellValue('A8', 'NRO.')
            ->setCellValue('B8', 'ALMACEN')
            ->setCellValue('C8', 'FECHA')
            ->setCellValue('D8', 'MARCA')
            ->setCellValue('E8', 'CODIGO PRODUCTO')
            ->setCellValue('F8', 'PRODUCTO')
            ->setCellValue('G8', 'CODIGO MODELO')
            ->setCellValue('H8', 'MODELO')
            ->setCellValue('I8', 'CANTIDAD ENTRANTE')
            ->setCellValue('J8', 'CANTIDAD SALIENTE')
            ->setCellValue('K8', 'SALDO DISPONIBLE')
            ->setCellValue('L8', 'COSTO')
            ->setCellValue('M8', 'PRECIO');

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E3')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('K8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('L8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('M8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('J8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('K8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('L8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('M8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 9; // Empieza a escribir desde la linea 9
        $i = 1;
        foreach ($list_data as $row) {
            $result_output = $this->inventory_model->get_detail_inventory_output_by_inventory_id($row['inventario_id']);
            if ($result_output == 0)
                $saliente = 0;
            else
                $saliente = $result_output;

            $saldo_disponible = $row['ingresada'] - $saliente;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['fecha'])
                ->setCellValue('C' . $fila, $row['nombre_almacen'])
                ->setCellValue('D' . $fila, $row['nombre_marca'])
                ->setCellValue('E' . $fila, $row['codigo_producto'])
                ->setCellValue('F' . $fila, $row['nombre_comercial'])
                ->setCellValue('G' . $fila, $row['codigo_modelo'])
                ->setCellValue('H' . $fila, $row['nombre_modelo'])
                ->setCellValue('I' . $fila, $row['ingresada'])
                ->setCellValue('J' . $fila, $saliente)
                ->setCellValue('K' . $fila, $saldo_disponible)
                ->setCellValue('L' . $fila, $row['precio_costo'])
                ->setCellValue('M' . $fila, $row['precio_venta']);

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
                ->getActiveSheet()->getStyle('M' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(TRUE);
        $objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(TRUE);

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte por Producto' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }

    public function report_for_product_reason()
    {
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();
        template('report/report_for_product_reason', $data);
    }

    public function get_report_product_reason_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin Datatable*/
            $report_brand = $this->input->post('report_brand');
            $report_model = $this->input->post('report_model');
            $report_product = $this->input->post('report_product');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');

            // Se almacenan los parametros recibidos en un array para enviar al model
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_brand' => $report_brand,
                'report_model' => $report_model,
                'report_product' => $report_product,
                'start_date' => $start_date,
                'end_date' => $end_date,
            );

            $data_product = $this->report_product_model->get_data_report_product_reason_list($params);
            echo json_encode($data_product);

        } else {
            show_404();
        }
    }

    public function export_to_excel_product_reason()
    {
        $this->load->library("excel/PHPExcel");

        // Parametros
        $report_brand = $this->input->post('report_brand');
        $report_model = $this->input->post('report_model');
        $report_product = $this->input->post('report_product');
        $report_start_date = $this->input->post('start_date');
        $report_end_date = $this->input->post('end_date');

        // Array
        $params = array(
            'report_brand' => $report_brand,
            'report_model' => $report_model,
            'report_product' => $report_product,
            'start_date' => $report_start_date,
            'end_date' => $report_end_date
        );

        $data = $this->report_product_model->get_data_report_product_reason_list($params);
        $list_data = $data['data'];

        if ($report_brand != 0) {
            $brand = $this->brand_model->get_brand_id($report_brand)->nombre;
        } else {
            $brand = 'Todos';
        }

        if ($report_model != 0) {
            $model = $this->model_model->get_model_id($report_model)->nombre;
            //$model_code = $this->model_model->get_model_id($report_model)->codigo;
        } else {
            $model = 'Todos';
            //$model_code = 'Todos';
        }

        if ($report_product != 0) {
            $product = $this->product_model->get_product_by_id($report_product)->nombre_comercial;
            //$product_code = $this->product_model->get_product_by_id($report_product)->codigo;
        } else {
            $product = 'Todos';
            //$product_code = 'Todos';
        }

        if ($report_start_date != ""){
            $start_date = $report_start_date;
        }else{
            $start_date = 'Todos';
        }

        if ($report_end_date != ""){
            $end_date = $report_end_date;
        }else{
            $end_date = 'Todos';
        }

        $branch_office = get_branch_office_name_in_session();

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); // TITULO
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); // MARCA
        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); // MODELO
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); // SUCURSAL
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE POR PRODUCTOS MOTIVOS')
            ->setCellValue('A4', 'MARCA: ')
            ->setCellValue('B4', $brand)
            ->setCellValue('A5', 'MODELO: ')
            ->setCellValue('B5', $model)
            ->setCellValue('A6', 'PRODUCTO:')
            ->setCellValue('B6', $product)
            ->setCellValue('D4', 'FECHA INICIO:')
            ->setCellValue('E4', $start_date)
            ->setCellValue('D5', 'FECHA FIN:')
            ->setCellValue('E5', $end_date)

            //Encabezado de la tabla
            ->setCellValue('A8', 'NRO.')
            ->setCellValue('B8', 'CODIGO PRODUCTO')
            ->setCellValue('C8', 'PRODUCTO')
            ->setCellValue('D8', 'CODIGO MODELO')
            ->setCellValue('E8', 'MODELO')
            ->setCellValue('F8', 'ColorModel')
            ->setCellValue('G8', 'GRUPO')
            ->setCellValue('H8', 'MARCA')
            ->setCellValue('I8', 'MOTIVO')
            ->setCellValue('J8', 'OBSERVACION');

        // Datos

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE)

            ->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('J8')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('J8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 9; // Empieza a escribir desde la linea 9
        $i = 1;
        foreach ($list_data as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['codigo_producto'])
                ->setCellValue('C' . $fila, $row['nombre_comercial'])
                ->setCellValue('D' . $fila, $row['codigo_modelo'])
                ->setCellValue('E' . $fila, $row['nombre_modelo'])
                ->setCellValue('F' . $fila, $row['dimension'])
                ->setCellValue('G' . $fila, $row['nombre_grupo'])
                ->setCellValue('H' . $fila, $row['nombre_marca'])
                ->setCellValue('I' . $fila, $row['nombre_tipo_motivo'])
                ->setCellValue('J' . $fila, $row['observacion']);

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
        header('Content-Disposition: attachment;filename="Reporte por Producto motivo' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");
    }


}