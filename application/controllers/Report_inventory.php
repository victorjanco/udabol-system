<?php

/**
 * Created by PhpStorm.
 * User: Green Ranger
 * Date: 17/04/2018
 * Time: 11:31 AM
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit','2048M');
class Report_inventory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model');
        $this->load->model('brand_model');
        $this->load->model('report_inventory_model');
        $this->load->model('model_model');
        $this->load->model('product_model');
    }

    /* Inicia la vista de reporte Stock de Inventario*/
    public function report_for_stock_inventory()
    {
        $data["list_warehouse"] = $this->warehouse_model->get_warehouse_branch_office_id(get_branch_id_in_session());
        $data["list_brand"] = $this->brand_model->get_brand_enable();
        $data["list_model"] = $this->model_model->get_model_enable();
        $data["list_product"] = $this->product_model->get_product_enable();
        template('report/report_for_inventory', $data);
    }

    /*Retorna la consulta de Stock de inventario de la Vista de BD*/
    public function get_report_stock_inventory_list()
    {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $limit = $this->input->post('length');
            $search = $this->input->post('search')['value'];
            $order = $this->input->post('order')['0']['dir'];
            $column_num = $this->input->post('order')['0']['column'];
            $column = $this->input->post('columns')[$column_num]['data'];

            /*Parametros de busqueda sin datatable*/
            $report_warehouse = $this->input->post('report_warehouse');
            $report_brand = $this->input->post('report_brand');
            $report_model = $this->input->post('report_model');
            $report_product = $this->input->post('report_product');

            // Se almacenan los parametros recibidos en un array para enviar al model
            $params = array(
                'start' => $start,
                'limit' => $limit,
                'search' => $search,
                'column' => $column,
                'order' => $order,
                'report_warehouse' => $report_warehouse,
                'report_brand' => $report_brand,
                'report_model' => $report_model,
                'report_product' => $report_product
            );
            $data = $this->report_inventory_model->get_data_report_stock_inventory($params);
            echo json_encode($data);
        } else {
            show_404();
        }
    }

    /*Reporte Stock de Inventario*/
    public function export_to_excel_stock_inventory()
    {
        $this->load->library("excel/PHPExcel");

        /*Parametros*/
        $report_warehouse = $this->input->post('report_warehouse');
        $report_brand = $this->input->post('report_brand');
        $report_model = $this->input->post('report_model');
        $report_product = $this->input->post('report_product');

        /*Array*/
        $params = array(
            'report_warehouse' => $report_warehouse,
            'report_brand' => $report_brand,
            'report_model' => $report_model,
            'report_product' => $report_product
        );

        $data = $this->report_inventory_model->get_data_report_stock_inventory($params);
        $list_data = $data['data'];
        if ($report_warehouse != 0) {
            $warehouse = $this->warehouse_model->get_warehouse_id($report_warehouse)->nombre;
        } else {
            $warehouse = 'Todos';
        }
        if ($report_brand != 0) {
            $brand = $this->brand_model->get_brand_id($report_brand)->nombre;
        } else {
            $brand = 'Todos';
        }
        if ($report_model != 0) {
            $model = $this->model_model->get_model_id($report_model)->nombre;
        } else {
            $model = 'Todos';
        }
        if ($report_product != 0) {
            $product = $this->product_model->get_product_by_id($report_product)->nombre_comercial;
            $product_code = $this->product_model->get_product_by_id($report_product)->codigo;
        } else {
            $product = 'Todos';
        }
        $branch_office = get_branch_office_name_in_session();

        //membuat objek PHPExcel
        $objPHPExcel = new PHPExcel();

        //Unir celdas
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2'); //Titulo
        $objPHPExcel->getActiveSheet()->mergeCells('B4:C4'); //Almacen
        $objPHPExcel->getActiveSheet()->mergeCells('F4:G4'); //Marca
        $objPHPExcel->getActiveSheet()->mergeCells('B5:C5'); //Modelo
        $objPHPExcel->getActiveSheet()->mergeCells('F5:G5'); //Producto
        $objPHPExcel->getActiveSheet()->mergeCells('B6:C6'); //Sucursal

        // Datos
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'REPORTE DE CANTIDADES EN STOCK DE INVENTARIO')
            ->setCellValue('A4', 'ALMACEN: ')
            ->setCellValue('B4', $warehouse)
            ->setCellValue('E4', 'MARCA: ')
            ->setCellValue('F4', $brand)
            ->setCellValue('A5', 'MODELO: ')
            ->setCellValue('B5', $model)
            ->setCellValue('E5', 'PRODUCTO: ')
            ->setCellValue('F5', $product)
            ->setCellValue('A6', 'SUCURSAL: ')
            ->setCellValue('B6', $branch_office)
            //Encabezado de la tabla
            ->setCellValue('A8', 'NRO.')
            ->setCellValue('B8', 'ALMACEN')
            ->setCellValue('C8', 'MARCA')
            ->setCellValue('D8', 'MODELO')
            ->setCellValue('E8', 'CODIGO PRODUCTO')
            ->setCellValue('F8', 'PRODUCTO')
            ->setCellValue('G8', 'NOMBRE GENERICO')
            ->setCellValue('H8', 'COLOR')
            ->setCellValue('I8', 'CANTIDAD DISPONIBLE');

        //Negrita
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('A8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('B8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('C8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('D8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('E8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('F8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('G8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('H8')->getFont()->setBold(TRUE)
            ->getActiveSheet()->getStyle('I8')->getFont()->setBold(TRUE);

        //Centrar los titulos
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->getActiveSheet()->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //Pintar los bordes
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('C8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('D8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('E8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('F8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('G8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('H8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
            ->getActiveSheet()->getStyle('I8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $fila = 9; // Empieza a escribir desde la linea 9
        $i = 1;
        foreach ($list_data as $row) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $fila, $i)
                ->setCellValue('B' . $fila, $row['nombre_almacen'])
                ->setCellValue('C' . $fila, $row['nombre_marca'])
                ->setCellValue('D' . $fila, $row['nombre_modelo'])
                ->setCellValue('E' . $fila, $row['codigo_producto'])
                ->setCellValue('F' . $fila, $row['nombre_comercial_producto'])
                ->setCellValue('G' . $fila, $row['nombre_generico_producto'])
                ->setCellValue('H' . $fila, $row['dimension_producto'])
                ->setCellValue('I' . $fila, $row['stock']);

            //Pintar los bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('B' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('C' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('D' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('E' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('F' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('G' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('H' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
                ->getActiveSheet()->getStyle('I' . $fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
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

        //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        //sesuaikan headernya
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //ubah nama file saat diunduh
        header('Content-Disposition: attachment;filename="Reporte por Stock' . "_" . date('d-m-Y') . "_" . '.xlsx"');

        //unduh file
        $objWriter->save("php://output");

    }

}
